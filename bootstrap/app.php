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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'zero.trust' => \App\Http\Middleware\ZeroTrust::class,
            'admin' => \App\Http\Middleware\AdminOnly::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
            'rate.limit' => \App\Http\Middleware\RateLimiter::class,
            'audit' => \App\Http\Middleware\ImmutableAuditMiddleware::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
            \App\Http\Middleware\RateLimiter::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ZeroTrust::class,
            \App\Http\Middleware\ImmutableAuditMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
