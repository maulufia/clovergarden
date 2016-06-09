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
        $this->passAuthWithToken($request->header('api_token'));
        
        if (!Auth::user()) {
            return response()->json(['error' => 'api_token_invalid'], 401);
        }
        
        return $next($request);
    }
    
    /**
     * Request에서 받아온 Token을 이용하여 Auth클래스 초기화
     * @param  [String] $token api-token
     * @return [None]
     */
    private function passAuthWithToken($token)
    {
        // token이 null일 경우 api_token 필드가 null인 data를 retrieve하므로 token을 ''로 초기화한다.
        if (!$token) {
            $token = '';
        }
        
        $user = DB::table('new_tb_member')->where('api_token', '=', $token)->where('user_state', '=', 6)->select('id')->first();
        if ($user) {
            Auth::loginUsingId($user->id);
        } else {
            return;
        }
    }
}
