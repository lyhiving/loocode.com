<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use App\Models\TermTaxonomy;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "内容", icon: "file")]
class CategoryController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "分类管理", link: "/code/content/category")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    public function index()
    {
        return TermTaxonomy::category()->where('parent', 0)->orderBy('taxonomy_id', 'desc')
    }
}
