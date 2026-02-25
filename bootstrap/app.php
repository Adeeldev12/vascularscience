<?php

use Illuminate\Foundation\Application;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
         $middleware->web([
        // your global web middleware
    ]);

    $middleware->alias([
        'auth' => Authenticate::class,
        // ... other aliases
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
