<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Http\Result;
use App\Models\Role;
use Corcel\Model\User;
use Corcel\Services\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class UserController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "账户", sort: 100, icon: "person")]
class UserController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "会员管理", sort: 0, link: "/app/user/members")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    /**
     * @param Request $request
     * @return Result
     */
    #[Route(title: "会员列表", parent: "会员管理")]
    public function members(Request $request): Result
    {
        $users = User::paginate(
            $request->query->getInt("data_per_page", 30),
            ['*'],
            'data_current_page',
        );
        foreach ($users as $user) {
            $user['roles'] = json_decode($user->meta->roles ?? '[]', true);
            /**
             * @var $roles Collection
             */
            $roles = $user['roles']
                ? Role::without('permission')->whereIn('id', $user['roles'])->get()->map(function($item) {
                return $item->name;
            })
                : collect([]);
            $user['role_name'] = $roles ? $roles->implode(',') : "";
            $user['lasted_date'] = $user->meta->lasted_date;
        }
        return Result::ok($users);
    }

    #[Route(title: "添加会员", parent: "会员管理")]
    public function store(Request $request): Result
    {
        $body = $request->json()->all();
        $user = User::where("user_email", $body['email'])->first();
        if ($user) {
            return Result::err(500, "邮箱地址已存在");
        }
        $user = new User();
        $user->user_login = $body['user_login'];
        $user->user_pass = (new PasswordService())->makeHash($body['password']);
        $user->user_nicename = $body['user_login'];
        $user->user_email = $body['email'];
        $user->user_activation_key = Str::random(8);
        $user->display_name = $body['user_login'];
        if (!$user->save()) {
            return Result::err(500, "添加失败");
        }
        if (is_array($body['roles']) && $this instanceof ManagerController) {
            $user->saveMeta('roles', json_encode($body['roles']));
        }
        return Result::ok(null, "添加成功");
    }

    #[Route(title: "更新会员", parent: "会员管理")]
    public function update(Request $request, int $id): Result
    {
        $body = $request->json()->all();
        $user = User::where("user_email", $body['email'])->first();
        if ($user && $user->ID != $id) {
            return Result::err(500, "邮箱地址已存在");
        }
        $user = User::find($id);
        if ($user == null) {
            return Result::err(500, "用户不存在");
        }
        $user->user_login = $body['user_login'];
        if (!empty($body['password'])) {
            $user->user_pass = (new PasswordService())->makeHash($body['password']);
        }
        $user->user_nicename = $body['user_login'];
        $user->user_email = $body['email'];
        $user->display_name = $body['user_login'];
        if (!$user->save()) {
            return Result::err(500, "添加失败");
        }
        if (is_array($body['roles']) && $this instanceof ManagerController) {
            $user->saveMeta('roles', json_encode($body['roles']));
        }
        return Result::ok(null, "更新成功");
    }
}
