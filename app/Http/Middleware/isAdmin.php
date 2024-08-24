<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $roleAsAdmin= env('ROLE_AS_ADMIN');
    
            if (Auth::user()->role_as == $roleAsAdmin) {
                return $next($request);
            }
           return redirect('/')->with('errorMesg','please login to access the system');
          

        }
        //staff=>staff$9Ajdsd!23
        //admin=>admin@3RFSFS1@4
        return redirect('/')->with('errorMesg','please login to access the system');
    }
}
