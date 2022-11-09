<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckFrontEndUser
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
        if(Auth::check()){
            if (Auth::user()->role == 'ADMIN') {
                // Auth::logout();
                return redirect('/admin/dashboard')->with('message', 'You are not a frontend user.So please try with other credentials.');;
            }
        }

        return $next($request);
    }
}
