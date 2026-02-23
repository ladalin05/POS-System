<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['api'])
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api/v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->alias([
            'abilities' => \App\Http\Middleware\Abilities::class,
        ]);
        $middleware->web([
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if (in_array('sanctum', $e->guards()) && $request->is('api/*')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated.'
                ]);
            }
        });
        $exceptions->render(function (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ]);
        });
    })->create();
