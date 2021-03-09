<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ShareVariable
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!$request->isXmlHttpRequest()) {
            View::share([
                'static_domain' => config('app.asset_url'),
                'options' => [
                    'site_url' => '',
                    'site_title' => '',
                    'site_description' => '',
                ],
                'links' => [],
                'user' => $request->user(),
                'menu' => [],
            ]);
        }
        return $next($request);
    }
}
