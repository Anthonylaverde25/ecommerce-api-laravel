<?php
namespace App\Providers;

use App\Application\Family\Contracts\FamilyActionsRepository;
use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Family\EloquentFamilyActionsRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Family\EloquentFamilyRepository;
use Illuminate\Support\ServiceProvider;

class FamilyProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FamilyCrudRepository::class, EloquentFamilyRepository::class);
        $this->app->bind(FamilyActionsRepository::class, EloquentFamilyActionsRepository::class);
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