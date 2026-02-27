<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPharmacist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $role = session('impersonate_role', $user->Type_Personnel);

            if ($role == 'Admin' || $role == 'Pharmacist') {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
