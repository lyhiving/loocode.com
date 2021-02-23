<?php


namespace App\Http\Controllers\Backend;

use App\Attributes\Route;
use App\Http\Result;
use App\Models\Option;

#[Route(title: "设置", icon: "settings-2")]
class SettingController extends BackendController
{
    /**
     * @return Result
     */
    #[Route(title: "站点配置", link: "/app/system/configuration")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "配置列表", parent: "站点配置")]
    public function options(): Result
    {
        $options = Option::simplePaginate(30);
        return Result::ok($options);
    }



}
