<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if( !Auth::guest() && (Auth::user()->role_id == 1 || Auth::user()->role_id == 2) ){
            return $next($request);
        }
        return redirect(route('home'));
    }
}
