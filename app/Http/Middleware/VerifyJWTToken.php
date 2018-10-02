<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::setRequest($request)->parseToken()->authenticate();
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'code_token' => 0,
                    'status' => 401, 
                    'msg' => 'Phiên làm việc của bạn đã hết hạn. Vui lòng đăng nhập lại để tiếp tục!'
                ], 200);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'code_token' => 0,
                    'status' => 401, 
                    'msg' => 'Token không đúng. Vui lòng đăng nhập lại!'
                ], 200);
            }else{
                return response()->json([
                    'code_token' => 0,
                    'status' => 401, 
                    'msg' => 'Token không được tìm thấy. Vui lòng đăng nhập lại!'
                ], 200);
            }
        }
        // Auth::login($user);
        return $next($request);
    }
}
