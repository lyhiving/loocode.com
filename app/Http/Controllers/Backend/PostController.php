<?php
declare(strict_types=1);


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Events\Post as PostEvent;
use App\Http\Result;
use Corcel\Model\Post;
use Corcel\Model\Taxonomy;
use Corcel\Model\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class PostsController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "文章", sort: 1, icon: "file")]
class PostController extends BackendController
{

    #[Route(title: "所有文章", sort: 0, link: "/app/content/posts")]
    public function managerAnchor(): Result
    {
        return Result::ok();
    }

    #[Route(title: "写文章", sort: 1, link: "/app/content/post-new")]
    public function postsActionAnchor(): Result
    {
        return Result::ok();
    }


    #[Route(title: "文章列表", parent: "所有文章")]
    public function posts(): Result
    {
        $posts = Post::select('id','post_author', 'post_title', 'post_status', 'post_modified', 'comment_count')
            ->orderBy('id', 'DESC')
            ->paginate(30);
        return Result::ok($posts);
    }

    /**
     * @param int $id
     * @return Result
     */
    #[Route(title: "文章详情", parent: "所有文章")]
    public function show(int $id): Result
    {
        $post = Post::find($id);
        if ($post == null) {
            return Result::err(404, "文章不存在");
        }
        $data = new \stdClass();
        $data->post_title = $post->post_title;
        $data->post_excerpt = $post->post_excerpt;
        $data->post_content = $post->post_content;
        $data->post_status = $post->post_status;
        $data->post_type = $post->post_type;
        $data->comment_status = $post->comment_status;
        $data->ping_status = $post->ping_status;
        $data->categories = [];
        $data->tags = [];
        $data->password = $post->post_password;
        $data->post_date = $post->post_date;
        $data->meta =  [
            "keyword" =>  $post->meta->keyword,
            "description" => $post->meta->description,
            "featured_media" => $post->meta->featured_media,
        ];
        $terms = [];
        if (($taxonomies = $post->taxonomies()->get())) {
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy->taxonomy == "post_tag") {
                    $data->tags[] = $taxonomy->term_taxonomy_id;
                    $terms[] = ["name" => $taxonomy->term->name, "id" => $taxonomy->term_taxonomy_id];
                }
                if ($taxonomy->taxonomy == "category") {
                    $data->categories[] = $taxonomy->term_taxonomy_id;
                }
            }
        }
        $data->terms = $terms;
        return Result::ok($data);
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "创建文章", parent: "所有文章")]
    public function store(Request $request): Result
    {
        $data = $request->json()->all();
        $data['post_author'] = isset($data['post_user']) && $data['post_user'] ? (int) $data['post_user'] : auth('backend')->id();
        $data['to_ping'] = $data['pinged'] = $data['post_content_filtered'] = "";
        if (empty($data['post_date'])) {
            $data['post_date'] = now();
        }
        $post = new Post($data);
        $post->post_name = $data['post_name'] ?? $data['post_title'];
        $post->post_status = $data['post_status'];
        $post->post_password = $data['password'];
        $post->post_author = $data['post_author'];
        $post->save();
        if ($post->ID) {
            $this->bindTagCategory($post->ID, $data['tags'], $data['categories'], $data['meta']);
        }
        return Result::ok([
            'id' => $post->ID,
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Result
     */
    #[Route(title: "更新文章", parent: "所有文章")]
    public function update(int $id, Request $request): Result
    {
        $data = $request->json()->all();
        $post = Post::find($id);
        if ($post == null) {
            return Result::err(404, "文章不存在");
        }
        $post->post_title = $data['post_title'];
        $post->post_content = $data['post_content'];
        $post->post_name = $data['post_name'] ?? $data['post_title'];
        $post->post_status = $data['post_status'];
        $post->post_excerpt = $data['post_excerpt'];
        $post->post_password = $data['password'];
        $post->post_modified = now();
        $post->post_modified_gmt = now();
        $post->save();
        if ($post->ID) {
            $this->bindTagCategory($post->ID, $data['tags'], $data['categories'], $data['meta'], 'update');
        }
        return Result::ok();
    }

    /**
     * @param int $id
     * @param array $tags
     * @param array $categories
     * @param array $meta
     * @param string $action
     */
    private function bindTagCategory(int $id, array $tags, array $categories, array $meta = [], string $action = 'create')
    {
        $relationId = [];
        if ($tags) {
            $relationId = $this->createTerms($tags, 'post_tag');
        }
        if ($categories) {
            $relationId = array_merge($relationId, $this->createTerms($categories, 'category'));
        }
        event(new PostEvent($id, $relationId, $meta, 'post', $action));
    }


    /**
     * @param array $terms
     * @param string $type
     * @return array
     */
    private function createTerms(array $terms, string $type): array
    {
        $relationId = [];
        foreach ($terms as $value) {
            if (is_numeric($value)) {
                $relationId[] = $value;
                continue;
            }
            /**
             * @var $term Term
             */
            $term = Term::firstOrCreate(['name' => $value], ['slug' => $value]);
            if ($term->taxonomy == null) {
                $taxonomy = new Taxonomy();
                $taxonomy->taxonomy = $type;
                $taxonomy->description = '';
                $taxonomy->parent = 0;
                $relationId[] = $term->taxonomy()->save($taxonomy)->taxonomy_id;
            } else {
                $relationId[] = $term->taxonomy->taxonomy_id;
            }
        }
        return $relationId;
    }

}
