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
            // Priority 1: Check route name first (most reliable)
            $route = $request->route();
            if ($route) {
                $routeName = $route->getName();
                if ($routeName) {
                    // If route name starts with 'admin.', redirect to admin login
                    if (strpos($routeName, 'admin.') === 0) {
                        return route('admin.login');
                    }
                    // If route name starts with 'user.', redirect to user login
                    if (strpos($routeName, 'user.') === 0) {
                        return route('user.login');
                    }
                }
                
                // Priority 2: Check route middleware to detect guard
                $middleware = $route->middleware();
                if (is_array($middleware)) {
                    foreach ($middleware as $mw) {
                        if (is_string($mw)) {
                            if (strpos($mw, 'auth:petugas') !== false || strpos($mw, 'petugas') !== false) {
                                return route('admin.login');
                            }
                            if (strpos($mw, 'auth:web') !== false) {
                                return route('user.login');
                            }
                        }
                    }
                }
            }
            
            // Priority 3: Check if request path is for admin area
            $path = $request->path();
            if ($path === 'admin' || strpos($path, 'admin/') === 0) {
                return route('admin.login');
            }
            
            // Priority 4: Check if request path is for user area
            if ($path === 'user' || strpos($path, 'user/') === 0) {
                return route('user.login');
            }
            
            // Default fallback to user login (NEVER use 'login' route)
            return route('user.login');
        }
    }
}
