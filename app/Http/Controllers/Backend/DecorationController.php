<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;

#[Route(title: "外观", sort: 2, icon: "layout")]
class DecorationController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "导航", parent: "外观", sort: 2, link: "/app/decoration/navigation")]
    public function navigation(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "小挂件", parent: "外观", sort: 3, link: "/app/decoration/widget")]
    public function widget(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "主题", parent: "外观", sort: 1, link: "/app/decoration/theme")]
    public function theme(): Result
    {
        return Result::ok();
    }
}
