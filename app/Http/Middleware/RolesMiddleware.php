<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Database\QueryException;

use Log;

class RolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
        Log::info("roles----------------");
        Log::info($roles);
        foreach($roles as $rol){
            Log::info($rol);
            if(auth()->user()->hasRol($rol)){
                return $next($request);
            }       
        }
     return redirect(RouteServiceProvider::HOME);
    }
}
