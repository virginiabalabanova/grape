<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Customize exception handling for JSON requests
        $exceptions->shouldRenderJsonWhen(function (\Illuminate\Http\Request $request, \Throwable $e) {
            // Check if the request explicitly asks for JSON
            if ($request->expectsJson()) {
                return true;
            }

            // Optionally, always return JSON for specific route prefixes (e.g., '/api/')
            // if ($request->is('api/*')) {
            //     return true;
            // }

            return false; // Fallback to default HTML rendering
        });
    })->create();
