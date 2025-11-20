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
            // Check if request is for admin area using Laravel's is() method
            // This is the most reliable way to detect admin routes
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }
            
            // Check if request is for user area
            if ($request->is('user') || $request->is('user/*')) {
                return route('user.login');
            }
            
            // Check route name if available
            $route = $request->route();
            if ($route) {
                $routeName = $route->getName();
                if ($routeName && strpos($routeName, 'admin.') === 0) {
                    return route('admin.login');
                }
                if ($routeName && strpos($routeName, 'user.') === 0) {
                    return route('user.login');
                }
            }
            
            // Check route middleware to detect guard
            if ($route) {
                $middleware = $route->middleware();
                foreach ($middleware as $mw) {
                    if (strpos($mw, 'auth:petugas') !== false) {
                        return route('admin.login');
                    }
                    if (strpos($mw, 'auth:web') !== false) {
                        return route('user.login');
                    }
                }
            }
            
            // Default fallback to user login (never use 'login' route)
            return route('user.login');
        }
    }
}
