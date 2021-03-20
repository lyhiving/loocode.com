<?php

namespace App\Http\Middleware;

use App\Attributes\Route;
use App\Http\Result;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Closure;
use Corcel\Model\User;
use Illuminate\Http\Request;

class RBAC
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \ReflectionException
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $controller = $route->getController();
        $ref = new \ReflectionMethod($controller, $route->getActionMethod());
        $attributes = $ref->getAttributes(Route::class);
        if (count($attributes) < 1) {
            return $next($request);
        }
        $arguments = $attributes[0]->getArguments();
        if (empty($arguments['title'])) {
            return $next($request);
        }
        $menu = Menu::select("id")->where('name', $arguments['title'])->first();
        if ($menu == null) {
            return $next($request);
        }
        /**
         * @var $user User
         */
        $user = $request->user('backend');
        $roles = json_decode($user->meta->roles ?? '[]', true);
        if (empty($roles)) {
            return response(Result::err(603, "无权限访问"));
        }
        $rolePermission = Permission::select('menu_id')->whereIn('role_id', $roles)->get()->map(function ($item) {
            return $item->menu_id;
        });
        if (!in_array($menu->id, $rolePermission->toArray())) {
            return response(Result::err(603, "无权限访问"));
        }
        return $next($request);
    }
}
