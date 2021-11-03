<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user()->user_type == "SuperAdmin" || Auth::user()->user_type == "Admin") {
            return $next($request);
        }
        return response()->json( ['message'=> 'Not Authorized.'], 403 );
    }
}
