<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "面板", sort: 0, icon: "settings-2", link: "/app/dashboard", home: true)]
class DashboardController extends BackendController
{
    /**
     *
     */
    public function main(): Result
    {
        return Result::ok();
    }
}
