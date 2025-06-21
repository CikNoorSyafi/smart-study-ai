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
        $middleware->alias([
            'session.auth' => \App\Http\Middleware\SessionAuth::class,
            'session.guest' => \App\Http\Middleware\SessionGuest::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
<<<<<<< HEAD
            'teacher' => \App\Http\Middleware\TeacherMiddleware::class,
        ]);

        // Ensure CSRF protection is enabled for web routes
        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
=======
            'teacher.admin' => \App\Http\Middleware\TeacherAdminMiddleware::class,
            'parent' => \App\Http\Middleware\ParentMiddleware::class,
>>>>>>> 12724e28d0bf753b46ad1b94ca09e93e652b95af
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
