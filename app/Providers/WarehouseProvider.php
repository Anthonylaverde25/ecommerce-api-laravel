<?php
declare(strict_types=1);
namespace App\Providers;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Warehouse\EloquentWarehouseRepository;
use Illuminate\Support\ServiceProvider;

class WarehouseProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(WarehouseCrudRepository::class, EloquentWarehouseRepository::class);
    }
    public function boot()
    {
        //
    }
}