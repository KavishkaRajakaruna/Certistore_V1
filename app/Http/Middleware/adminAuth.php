<?php

namespace App\Http\Middleware;

use Closure;

class adminAuth
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
        if ($request->id != '16apc2739 || 16apc2763' ){
            return response() ->json([
                'message' => 'user not authorized'
            ], 401);
        }
        return $next($request);
    }
}
