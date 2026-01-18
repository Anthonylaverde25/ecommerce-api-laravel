<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Providers;

use App\Application\Product\Contracts\ProductRepositoryInterface;
use Infrastructure\Persistence\Eloquent\Repositories\Product\EloquentProductRepository;
use Illuminate\Support\ServiceProvider;

/**
 * ProductServiceProvider - Infrastructure Layer
 * 
 * Registra las dependencias e implementaciones para productos
 */
class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding: cuando alguien pida ProductRepositoryInterface,
        // Laravel le dará EloquentProductRepository
        $this->app->bind(
            ProductRepositoryInterface::class,
            EloquentProductRepository::class
        );
    }
}
