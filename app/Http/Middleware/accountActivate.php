<?php

namespace App\Http\Middleware;

use Closure;

class accountActivate
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
        if($request->user()->active!= true){
            return response()->json([
                'message' => 'Acoount not activated',
            ], 401);
        } elseif ($request->user()->deleted_at != null){
            return response() ->json([
                'message' => 'Account Scheduled for deletion. Please contact administration'
            ], 401);
        }
       return $next($request);
    }
}
