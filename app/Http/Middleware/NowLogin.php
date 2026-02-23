<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NowLogin
{

    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && $request->is('login')) {

            return redirect()->route('home'); 
        }

        return $next($request);
    }
}
