<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // If user is authenticated with 'petugas' guard, redirect to admin dashboard
                if ($guard === 'petugas') {
                    return redirect()->route('admin.dashboard');
                }
                // Default redirect for other guards
                return redirect('/home');
            }
        }

        return $next($request);
    }
}
