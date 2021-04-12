<?php

namespace App\Http\Middleware;

use App\Http\Result;
use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectJsonIfAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (!Auth::guard($guard)->check()) {
                if ($request->isXmlHttpRequest()) {
                     return new JsonResponse(Result::err(401, "Unauthenticate"), 401);
                } else {
                     return redirect(RouteServiceProvider::HOME);
                }
            }
        }
        return $next($request);
    }
}
