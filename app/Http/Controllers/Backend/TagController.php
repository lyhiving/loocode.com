<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;

/**
 * Class TagController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "文章", icon: "file")]
class TagController extends BackendController
{
    #[Route(title: "标签", link: "/app/content/tag")]
    public function anchor(): Result
    {
        return Result::ok();
    }
}
