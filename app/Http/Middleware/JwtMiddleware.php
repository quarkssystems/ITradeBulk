<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
            $user = JWTAuth::parseToken()->authenticate();
           // $request->request->add(['login_user_id' => $user->id]);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                $data['status'] = 'false';
                $data['response'] = [
                    'message' => 'Token is Invalid'
                  ]; 
                  
                return response()->json($data,401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                 $data['status'] = 'false';
                $data['response'] = [
                    'message' => 'Token is Expired'
                  ]; 
                return response()->json($data,401);
            }else{

                $data['status'] = 'false';
                $data['response'] = [
                    'message' => 'Authorization Token not found'
                  ]; 
                return response()->json($data,401);
            }
        }
        return $next($request);
    }
}
