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
    ->withSchedule(function ($schedule) {
        $schedule->command('spreadsheet:retry-pending --limit=50 --max-attempts=20 --cooldown=5')
            ->everyMinute()
            ->withoutOverlapping(10)
            ->runInBackground();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'maintenance' => \App\Http\Middleware\MaintenanceMiddleware::class,
        ]);
        
        // Apply maintenance middleware globally but exclude admin routes
        $middleware->web(append: [
            \App\Http\Middleware\MaintenanceMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
