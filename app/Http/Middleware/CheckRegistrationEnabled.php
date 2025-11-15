<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRegistrationEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('auth.registration_enabled', true)) {
            return redirect()->route('admin.login')
                ->with('error', 'Pendaftaran akun baru dinonaktifkan.');
        }

        return $next($request);
    }
}
