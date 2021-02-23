<?php
declare(strict_types=1);


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Events\Posts;
use App\Http\Result;
use App\Models\Post;
use App\Models\Term;
use App\Models\TermTaxonomy;
use Illuminate\Http\Request;

/**
 * Class PostsController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "内容", icon: "file")]
class PostsController extends BackendController
{

    #[Route(title: "主题管理", link: "/app/content/posts")]
    public function managerAnchor(): Result
    {
        return Result::ok();
    }

    #[Route(title: "创建更新主题", link: "/app/content/posts-action")]
    public function postsActionAnchor(): Result
    {
        return Result::ok();
    }


    #[Route(title: "主题列表", parent: "主题管理")]
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
    public function store(Request $request): Result
    {
        $data = $request->all();
        $requestTime = date('Y-m-d H:i:s', $request->server('REQUEST_TIME'));
        $posts = new Post([
            'post_modified' => $requestTime
        ]);
        $posts->post_date = $data['post_date'];
        $posts->post_author = isset($data['post_user']) ? (int) $data['post_user'] : auth()->id();
        $posts->post_title = $data['post_title'] ?? '';
        $posts->post_content = $data['post_content'] ?? '';
        $posts->post_excerpt = $data['post_desc'] ?? '';
        if (strtotime($data['post_date']) > time()) {
            $posts->post_status = Post::STATUS_DELAY;
        } else {
            $posts->post_status  = $data['post_status'] ?? Post::STATUS_PUBLISH;
        }
        $posts->comment_status = $data['comment_status'] ?? 0;
        $posts->ping_status = $data['ping_status'] ?? 0;
        $posts->post_password = $data['password'] ?? '';
        $posts->post_name = $data['post_name'] ?? '';
        $posts->post_type = $data['post_type'] ?? Post::TYPE_POST;
        if ($posts->save()) {
            $objectId = $posts->id;
            $relationId = [];
            if ($data['post_tag']) {
                $relationId = $this->createTerms(explode(',', $data['post_tag']), 'post_tag');
            }
            if ($data['category']) {
                $relationId = array_merge($relationId, $this->createTerms(explode(',', $data['category']), 'category'));
            }
            event(new Posts($objectId, $relationId, $data['meta'], $posts->post_type, 'create'));
        }
        return Result::ok();
    }

    public function update()
    {

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
            /**
             * @var $term Term
             */
            $term = Term::firstOrCreate(['slug' => $value], ['name' => $value]);
            if ($term->taxonomy == null) {
                $taxonomy = new TermTaxonomy();
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
