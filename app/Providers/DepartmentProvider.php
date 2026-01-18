<?php
namespace App\Providers;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\Department\EloquentDepartmentRepository;
use Illuminate\Support\ServiceProvider;


class DepartmentProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DepartmentCrudRepository::class, EloquentDepartmentRepository::class);
    }

    public function boot()
    {
        //
    }
}