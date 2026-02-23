<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsNurseOrDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->isNurse() || $user->isDoctor())) {
            return $next($request);
        }

        return redirect('error')->with('fail', 'คุณไม่มีสิทธิ์เข้าถึงส่วนนี้ (สำหรับพยาบาลหรือหมอเท่านั้น)');
    }
}
