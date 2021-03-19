<?php


namespace App\Http\Controllers\Backend;


use App\Attributes\Route;
use App\Events\Post;
use App\Http\Result;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

/**
 * Class RoleController
 * @package App\Http\Controllers\Backend
 */
#[Route(title: "设置", sort: 111, icon: "settings-2")]
class RoleController extends BackendController
{

    /**
     * @return Result
     */
    #[Route(title: "角色", sort: 2, link: "/app/system/roles")]
    public function anchor(): Result
    {
        return Result::ok();
    }

    #[Route(title: "角色列表", parent: "角色", sort: 1)]
    public function roles(Request $request): Result
    {
        $builder = new Role();
        $size = $request->query->getInt("data_per_page", 30);
        $roles = $builder->without("permission")->orderBy('id', 'desc')
            ->paginate($size, ['*'], 'data_current_page');
        foreach ($roles as $role) {
            $role['permission'] = $size > 30 ? [] :
                Permission::select('menu_id')->where('role_id', $role->id)->get()->map(function ($item) {
                    return $item->menu_id;
                });
        }
        return Result::ok($roles);
    }

    #[Route(title: "添加角色", parent: "角色", sort: 2)]
    public function store(Request $request): Result
    {
        $body = $request->json()->all();
        if (empty($body['name'])) {
            return Result::err(500, "名称不能为空");
        }
        if (Role::where('name', $body['name'])->first()) {
            return Result::err(500, "存在相同名称角色");
        }
        $role = new Role();
        $role->name = $body['name'];
        $role->save();
        if (!empty($body['permission']) && is_array($body['permission'])) {
            $permissions = [];
            foreach ($body['permission'] as $id) {
                $permissions[] = new Permission(["menu_id" => $id]);
            }
            $role->permission()->saveMany($permissions);
        }
        return Result::ok([
            'id' => $role->id,
        ]);
    }

    #[Route(title: "更新角色", parent: "角色", sort: 3)]
    public function update(Request $request, int $id): Result
    {
        $body = $request->json()->all();
        if (empty($body['name'])) {
            return Result::err(500, "名称不能为空");
        }
        $role = Role::find($id);
        if ($role == null) {
            return Result::err(404, "角色不存在");
        }
        if (empty($body['permission']) || !is_array($body['permission'])) {
            return Result::ok();
        }
        $role->permission()->delete();
        $permissions = [];
        foreach ($body['permission'] as $id) {
            $permissions[] = new Permission(["menu_id" => $id]);
        }
        $role->permission()->saveMany($permissions);
        return Result::ok(null, "更新成功");
    }

    #[Route(title: "删除角色", parent: "角色", sort: 4)]
    public function delete(int $id): Result
    {
        return Result::ok();
    }
}
