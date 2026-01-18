<?php

namespace App\Providers;

use App\Application\Tax\Contracts\TaxActionRepository;
use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Application\Tax\Contracts\TaxTypeCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Tax\EloquentTaxActionRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Tax\EloquentTaxRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Tax\EloquentTaxTypeRepository;
use Illuminate\Support\ServiceProvider;


class TaxProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TaxCrudRepository::class, EloquentTaxRepository::class);
        $this->app->bind(TaxTypeCrudRepository::class, EloquentTaxTypeRepository::class);
        $this->app->bind(TaxActionRepository::class, EloquentTaxActionRepository::class);
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
