<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class ownerAuth
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
        if(Auth::id() != $request->route('user.id')){
            return response()->json([
                'message' => 'Not allowed to access others  '
            ],401);
        }else{
            return $next($request);
        }
    }
}
