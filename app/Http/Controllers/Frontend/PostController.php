<?php
declare(strict_types=1);

namespace App\Http\Controllers\Frontend;


use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use stdClass;

/**
 * Class PostsController
 * @package App\Http\Controllers\Frontend
 */
class PostController extends FrontendController
{

    /**
     * @param Request $request
     * @param int $id
     * @return View|Factory
     */
    public function show(Request $request, int $id): View
    {
        $post = $this->getPost($id);
        if ($post == null) {
            abort(404);
        }
        $sql = 'SELECT meta_key, meta_value FROM postmeta WHERE post_id = ?';
        $metas = DB::select($sql, [$id]);
        $post->metas = [];
        foreach ($metas as $item) {
            $post->metas[$item->meta_key] = $item->meta_value;
        }
        if (!isset($post->metas['_lc_post_views'])) {
            $post->metas['_lc_post_views'] = 0;
        }
        if (!isset($post->metas['_lc_post_like'])) {
            $post->metas['_lc_post_like'] = 0;
        }
        if ($post->metas['_lc_post_views'] >= 1000) {
            $post->metas['_lc_post_views'] = sprintf(
                "%.1fk",
                (int)$post->metas['_lc_post_views'] / 1000
            );
        }
        if ($post->metas['_lc_post_like'] >= 1000) {
            $post->metas['_lc_post_like'] = sprintf(
                "%.1fk",
                (int)$post->metas['_lc_post_like'] / 1000
            );
        }
        $sql = <<<EOF
SELECT t1.taxonomy, t2.object_id, t3.slug FROM term_taxonomy as t1
    LEFT JOIN term_relationships as t2 ON (t1.term_taxonomy_id=t2.term_taxonomy_id)
    LEFT JOIN terms as t3 ON (t1.term_id=t3.term_id)
    WHERE t2.object_id = ?
EOF;
        $taxonomy = DB::select($sql, [$id]);

        $sql = <<<EOF
SELECT
       t2.display_name as name, t2.avatar, t1.comment_author, t1.comment_ID, t1.comment_date, t1.comment_content
FROM comments AS t1 LEFT JOIN users AS t2
ON (t1.comment_author=t2.ID)
WHERE t1.comment_post_ID = ? AND (t1.comment_approved = 1 OR comment_author = ?) ORDER BY t1.comment_ID
EOF;
        $user = $request->user();
        $comments = DB::select($sql, [$id, $user->ID ?? 0]);
        $author = DB::selectOne('SELECT display_name as name, avatar FROM users WHERE id = ?', [$post->post_author]);
        $metas = DB::select('SELECT meta_key, meta_value FROM usermeta WHERE user_id = ?', [$post->post_author]);
        if (!$author) {
            $author = new stdClass();
        }
        $author->metas = [];
        if ($metas) {
            foreach ($metas as $meta) {
                $author->metas[$meta->meta_key] = $meta->meta_value;
            }
        }
        $post->tags = count($taxonomy) > 0
            ? array_map(function ($item) {
                if ($item->taxonomy == 'post_tag') {
                    return $item->slug;
                }
                return "";
            }, $taxonomy)
            : [];
        $post->author = $author;
        if ($id >= 10195) {
            $post->post_content = Str::markdown($post->post_content);
        }
        $seo = $this->getSeo($post->post_title);
        return view("show", [
            'post' => $post,
            'comments' => $comments,
            'seo' => $seo,
            'globalComment' => isset(self::$options['open_comment']) && self::$options['open_comment'] == "true"
        ]);
    }


    /**
     * @param string $name
     * @return View
     */
    public function taxonomy(string $name): View
    {
        $sql = <<<EOF
SELECT term_taxonomy.term_taxonomy_id FROM terms JOIN term_taxonomy ON (terms.term_id = term_taxonomy.term_id) WHERE terms.slug = ? LIMIT 1
EOF;
        $taxonomy = DB::selectOne($sql, [$name]);
        if ($taxonomy == null) {
            abort(404);
        }
        $sql = <<<EOF
SELECT
       t3.id, t3.post_title, t3.post_date, t3.post_excerpt, t3.post_type, t4.display_name as name, t4.avatar, t3.post_modified
FROM term_relationships as t2
JOIN posts as t3 ON (t2.object_id=t3.id)
JOIN users as t4 ON (t4.ID=t3.post_author)
WHERE t2.term_taxonomy_id = ? AND t3.post_status = 'publish' AND t3.post_type = 'post' ORDER BY t3.id DESC
EOF;
        $posts = DB::select($sql, [$taxonomy->term_taxonomy_id]);
        if ($posts) {
            $objectIdSets = array_map(function ($item) {return $item->id;}, $posts);
            [$postMeta, $postTag] = $this->getIdSetsMetesAndTaxonomy($objectIdSets);
            foreach ($posts as  $item) {
                $item->tags = $postTag[$item->id] ?? [];
                $item->metas = $postMeta[$item->id] ?? [];
            }
        }
        return view("taxonomy", [
            'taxonomy' => $name,
            'posts' => $posts,
            'seo' => $this->getSeo($name),
            'hotPosts' => [],
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function view(int $id): JsonResponse
    {
        return $this->updatePostsMeta($id, '_lc_post_views');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function like(Request $request, int $id): JsonResponse
    {
        if ($request->user() == null) {
            return new JsonResponse(['code' => 401, 'message' => '未登录', 'data' => null]);
        }
        return $this->updatePostsMeta($id, '_lc_post_like');
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function comment(Request $request, int $id): JsonResponse
    {
        $data = ['code' => 500, 'data' => null, 'message' => ''];
        $response = new JsonResponse($data);
        if (!($user = $request->user())) {
            $data['code'] = 401;
            return $response->setData($data);
        }
        $contents = $request->request->all();
        $posts = $this->getPost($id);
        if (empty($posts)) {
            $data['code'] = 404;
            return $response->setData($data);
        }
        if (empty($contents['content'])) {
            $data['message'] = '发表内容不能为空!';
            return $response->setData($data);
        }
        $content = Str::markdown($contents['content']);
        $comment = [
            'comment_post_ID' => $id,
            'comment_parent' => 0,
            'comment_author' => $user->ID,
            'comment_author_ip' => $request->getClientIp(),
            'comment_date' => now(),
            'comment_content' => $content,
            'comment_approved' => 0,
            'comment_agent' => $request->userAgent(),
            'comment_type'  => 'comment'
        ];
        $id = DB::table('comments')->insertGetId($comment);
        if (empty($id)) {
            $data['message'] = "发表失败";
            return $response->setData($data);
        }
        $data['code'] = 200;
        $data['data'] = ['id' => $id];
        return $response->setData($data);
    }

    /**
     * @param int $id
     * @param string $metaKey
     * @return JsonResponse
     */
    private function updatePostsMeta(int $id, string $metaKey): JsonResponse
    {
        $p = $this->getPost($id);
        $data = ['code' => 500, 'data' => null, 'message' => ''];
        $response = new JsonResponse($data);
        if ($p == null) {
            return $response;
        }
        $bindValues = [$id, $metaKey];
        $meta = DB::selectOne('SELECT meta_id FROM postmeta WHERE post_id = ? AND meta_key = ?', $bindValues);
        if ($meta == null) {
            $sql = 'INSERT INTO postmeta (post_id, meta_key, meta_value) VALUE (?, ?, ?)';
            $bindValues[] = 1;
        } else {
            $sql = 'UPDATE postmeta SET meta_value = `meta_value` + 1 WHERE post_id = ? AND meta_key = ?';
        }
        $data['code'] = 200;
        DB::statement($sql, $bindValues);
        return $response->setData($data);
    }

    /**
     * @param int $id
     * @return stdClass|null
     */
    private function getPost(int $id): ?stdClass
    {

        $sql = <<<EOF
SELECT
   id, post_author, post_date, post_title, post_content,
   post_excerpt, post_status, post_name, post_modified, post_type,
   post_parent, comment_count
FROM posts WHERE id = ?
EOF;
        return DB::selectOne($sql, [$id]);
    }

}
