<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserActiveMiddleware
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
        //Auth::guest() ya3ni yoser msh 3amel login
        if( !Auth::guest() && (Auth::user()->user_visibility == 1) ){
            return $next($request);
        }
        return redirect('logoutMiddleWare');
    }
}
