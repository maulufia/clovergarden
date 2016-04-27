<?php

namespace clovergarden\Http\Middleware;

use Closure, Route;
use Illuminate\Support\Facades\Auth;

class AuthenticateAdmin
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
                if (Route::currentRouteName() != 'admin/login') // 게스트인 경우 로그인 페이지로 들어오면 아무 행동도 취하지 않음
                    return redirect()->guest('admin');
            }
        }

        if (Auth::check()) {
            if (Auth::user()->user_state != 1 && Auth::user()->user_state != 10 && Auth::user()->user_state != 6) { // 관리자, 기관답담자가 아닌 경우 홈으로 리디렉트
                return redirect()->route('home');
            }
            
            if (Auth::user()->user_state == 6) {
                 if (Route::currentRouteName() != 'admin/clover' && Route::currentRouteName() != 'admin/service') { // 기관담당자가 어드민의 허용되지 않는 경로로 들어왔을 때
                   return redirect()->route('admin/clover', array('item' => 'news'));
                 }
            }
        }

        if (Auth::check() && Route::currentRouteName() == 'admin/login') { // 로그인 되어 있는 경우 로그인 페이지로 들어오면 다시 홈으로 내보냄
            if (Auth::user()->user_state == 1 || Auth::user()->user_state == 10 || Auth::user()->user_state == 6) {
                if (Auth::user()->user_state == 6) { // 기관담당자
                    return redirect()->route('admin/clover', array('item' => 'news'));
                }
                
                return redirect()->route('admin/main');
            } else { // 관리자가 아닌 유저가 로그인 한 경우
            }
        }
        
        return $next($request);
    }
}
