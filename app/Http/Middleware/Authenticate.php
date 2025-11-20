<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check the route path to determine which guard/login to use
            $path = $request->path();
            
            // If accessing admin routes, redirect to admin login
            if (str_starts_with($path, 'admin')) {
                return route('admin.login');
            }
            
            // For user routes or default, redirect to user login
            if (str_starts_with($path, 'user')) {
                return route('user.login');
            }
            
            // Try to detect guard from authenticated guards
            $guards = ['petugas', 'web'];
            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {
                    if ($guard === 'petugas') {
                        return route('admin.login');
                    }
                    if ($guard === 'web') {
                        return route('user.login');
                    }
                }
            }
            
            // Default fallback: check if request is for admin area
            if (str_starts_with($path, 'admin')) {
                return route('admin.login');
            }
            
            // Default to user login
            return route('user.login');
        }
    }
}
