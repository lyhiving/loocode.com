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
     * @param Request $request
     * @return Result
     */
    #[Route(title: "创建文章", parent: "所有文章")]
    public function store(Request $request): Result
    {
        $data = $request->json()->all();
        $data['post_author'] = isset($data['post_user']) ? (int) $data['post_user'] : auth('backend')->id();
        $data['to_ping'] = $data['pinged'] = $data['post_content_filtered'] = "";
        if (empty($data['post_date'])) {
            $data['post_date'] = now();
        }
        $post = new Post($data);
        $post->post_name = $data['post_name'] ?? $data['post_title'];
        $post->post_status = $data['post_status'];
        $post->save();
        if ($post && $post->ID) {
            $relationId = [];
            if ($data['tags']) {
                $relationId = $this->createTerms($data['tags'], 'post_tag');
            }
            if ($data['categories']) {
                $relationId = array_merge($relationId, $this->createTerms($data['categories'], 'category'));
            }
            event(new PostEvent($post->ID, $relationId, $data['meta'], $post->post_type, 'create'));
        }
        return Result::ok();
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Result
     */
    #[Route(title: "更新文章", parent: "所有文章")]
    public function update(int $id, Request $request): Result
    {

        return Result::ok();
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
