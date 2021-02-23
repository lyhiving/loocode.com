<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;

/**
 * Class TagController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "内容", icon: "file")]
class TagController extends BackendController
{
    #[Route(title: "标签管理", link: "/app/content/tag")]
    public function anchor(): Result
    {
        return Result::ok();
    }
}
