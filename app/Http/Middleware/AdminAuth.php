<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check())
        {

              if(Auth::user()->role==1){
                //he is root user
                    return $next($request);

              }   
              else{
                return redirect()->route('emp_home')->with('error','Access Denied');

              }

        }else{
            return redirect()->route('login_form')->with('error','Please Login to access');


        }
    }
}