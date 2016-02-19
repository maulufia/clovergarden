<?php

namespace clovergarden\Http\Middleware;

use Closure, Route;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                if(Route::currentRouteName() != 'login') // 게스트인 경우 로그인 페이지로 들어오면 아무 행동도 취하지 않음음
                    return redirect()->guest('login');
            }
        }

        if (Auth::check() && Route::currentRouteName() == 'login') { // 로그인 되워 있는 경우 로그인 페이지로 들어오면 다시 홈으로 내보냄
            return redirect()->route('home');
        }
        
        return $next($request);
    }
}
