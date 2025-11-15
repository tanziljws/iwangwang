<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class CustomKernel extends HttpKernel
{
    protected $middleware = [
        \App\Http\Middleware\Authenticate::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
    ];
}
