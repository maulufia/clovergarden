<?php

namespace clovergarden\Http\Middleware;

use DB;
use Closure, Route;
use Illuminate\Support\Facades\Auth;

class AuthenticateChargeToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'api')
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } else {
                return response()->json(['error' => 'need_login'], 401);
            }
        }
        
        $this->passAuthWithToken($request->input('api_token'));
        
        return $next($request);
    }
    
    /**
     * Request에서 받아온 Token을 이용하여 Auth클래스 초기화
     * @param  [String] $token api_token
     * @return [None]
     */
    private function passAuthWithToken($token)
    {
        $id = DB::table('new_tb_member')->where('api_token', '=', $token)->where('user_state', '=', 6)->select('id')->first()->id;
        Auth::loginUsingId($id);
    }
}
