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
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\TypeError $e, $request) {
            if (str_contains($e->getMessage(), 'hash_equals')) {
                \Illuminate\Support\Facades\Cookie::queue(\Illuminate\Support\Facades\Cookie::forget(\Illuminate\Support\Facades\Auth::getRecallerName()));
                if ($request->hasSession()) {
                    $request->session()->invalidate();
                }
                return redirect('/login')->with('error', 'Sesi Anda telah kedaluwarsa setelah database di-reset. Silakan login kembali.');
            }
        });
    })->create();
