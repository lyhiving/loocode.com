<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use App\Models\User;

/**
 * Class UserController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "账户", icon: "person")]
class UserController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "会员管理", link: "/app/user/members")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @return Result
     */
    #[Route(title: "会员列表", parent: "会员管理")]
    public function members(): Result
    {
        $users = User::paginate(30);
        return Result::ok($users);
    }
}
