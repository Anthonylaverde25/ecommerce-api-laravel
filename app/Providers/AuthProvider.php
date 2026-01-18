<?php

namespace App\Providers;

use App\Application\Auth\Contracts\UserCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Auth\EloquentUserRepository;
use Carbon\Laravel\ServiceProvider;




class AuthProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserCrudRepository::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
