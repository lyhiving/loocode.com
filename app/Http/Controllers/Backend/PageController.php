<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use Corcel\Model\Post;
use Illuminate\Http\Request;

/**
 * Class PageController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "页面", sort: 2, icon: "file")]
class PageController extends BackendController
{
    /**
     * @return Result
     */
    #[Route(title: "所有页面", parent: "页面", sort: 1, link: "/app/page")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @param Request $request
     * @return Result
     */
    public function index(Request $request): Result
    {
        $posts = Post::select('id','post_author', 'post_title', 'post_status', 'post_modified', 'comment_count')
            ->type("page")
            ->orderBy('id', 'DESC');
        if ($request->query->has('id_like')) {
            $posts->where('ID',$request->query->get('id_like'));
        }
        if ($request->query->has('post_author_like')) {
            $posts->where('post_author', $request->query->getInt('post_author_like'));
        }
        if ($request->query->has('post_title_like')) {
            $posts->where('post_title', 'like', '%' . $request->query->get('post_title_like') . '%');
        }
        $posts = $posts->without("meta")->paginate(
            $request->query->getInt("data_per_page", 30),
            ['*'],
            'data_current_page',
        );
        return Result::ok($posts);
    }

    /**
     * @return Result
     */
    #[Route(title: "新建页面", parent: "页面", sort: 1, link: "/app/page/new")]
    public function new(): Result
    {
        return Result::ok();
    }
}
