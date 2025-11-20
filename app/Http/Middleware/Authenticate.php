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
            
            // Default fallback to user login
            return route('user.login');
        }
    }
}
