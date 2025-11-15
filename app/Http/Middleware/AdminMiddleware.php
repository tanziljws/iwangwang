<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated with petugas guard
        if (!Auth::guard('petugas')->check()) {
            // Store the intended URL for redirect after login
            if (!$request->is('admin/*')) {
                session(['url.intended' => $request->fullUrl()]);
            }
            
            return redirect()
                ->route('admin.login')
                ->with('error', 'Anda harus login terlebih dahulu!');
        }

        $user = Auth::guard('petugas')->user();

        // Check if user is active
        if ($user->status !== 'aktif') {
            Auth::guard('petugas')->logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()
                ->route('admin.login')
                ->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // If no specific roles are required, just check authentication
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        if (!in_array($user->jabatan, $roles)) {
            // Log unauthorized access attempt
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role' => $user->jabatan,
                'required_roles' => $roles,
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            // If AJAX request, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengakses halaman ini.'
                ], 403);
            }

            // For regular requests, redirect back with error
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}
