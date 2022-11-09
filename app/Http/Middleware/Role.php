<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Role
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
        if (Auth::user()->role != 'ADMIN') {
            Auth::logout();
            return redirect('/')->with('message', 'Please try with other credentials.');;
        }
        return $next($request);
    }
}
