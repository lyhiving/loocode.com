<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use Corcel\Model\Taxonomy;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "文章", icon: "file")]
class CategoryController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "分类目录", link: "/code/content/category")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    public function index()
    {
        return Taxonomy::category()->where('parent', 0)->orderBy('taxonomy_id', 'desc');
    }
}
