<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        if (request()->is('api/*') && request()->bearerToken()) {
            try {
                $decoded = JWT::decode(request()->bearerToken(), new Key(env('SANCTUM_STATEFUL_SECRET'), 'HS256'));
                if (isset($decoded->token)) {
                    request()->headers->set('Authorization', 'Bearer ' . $decoded->token);
                }
            } catch (\Exception $e) {
                // Return a response if the token is invalid or decoding fails
            }
        }

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
