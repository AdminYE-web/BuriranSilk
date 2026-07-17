<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SuperAdminOnly;
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
        $middleware->encryptCookies(except: [
            'dev',
        ]);

        $middleware->web(append: [
            SetLocale::class,
        ]);
        $middleware->alias([
            'admin.auth' => AdminAuth::class,
            'super.admin' => SuperAdminOnly::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
