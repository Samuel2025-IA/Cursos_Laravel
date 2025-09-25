<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Passwords\PasswordBrokerManager;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class PasswordResetServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extender el PasswordBrokerManager para personalizar la creación de tokens
        $this->app->extend('auth.password', function ($passwordBroker, $app) {
            $config = $app['config']['auth.passwords.users'];
            
            // Crear un token repository personalizado
            $tokenRepository = new class($app['db']->connection(), $app['hash'], $config['table'], $config['expire']) extends DatabaseTokenRepository {
                protected function insert($email, $token, $createdAt)
                {
                    return $this->getTable()->insert([
                        'email' => $email,
                        'token' => $this->hasher->make($token),
                        'used' => false,
                        'used_at' => null,
                        'created_at' => $createdAt,
                    ]);
                }
                
                public function delete(\Illuminate\Contracts\Auth\CanResetPassword $user)
                {
                    // En lugar de eliminar, marcar como usado
                    return $this->getTable()
                        ->where('email', $user->getEmailForPasswordReset())
                        ->update([
                            'used' => true,
                            'used_at' => now()
                        ]);
                }
            };
            
            return new \Illuminate\Auth\Passwords\PasswordBroker(
                $tokenRepository,
                $app['auth']->createUserProvider($config['provider'] ?? null)
            );
        });
    }
}
