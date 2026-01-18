<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(
            \App\Application\Auth\Contracts\AuthRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Repositories\Auth\EloquentAuthRepository::class
        );

        $this->app->bind(
            \App\Application\Role\Contracts\RoleCrudRepository::class,
            \App\Infrastructure\Persistence\Eloquent\Repositories\Role\EloquentRoleRepository::class
        );
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
