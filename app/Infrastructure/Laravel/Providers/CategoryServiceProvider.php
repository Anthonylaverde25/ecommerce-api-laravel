<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Providers;

use App\Application\Category\Contracts\CategoryRepositoryInterface;
use Infrastructure\Persistence\Eloquent\Repositories\EloquentCategoryRepository;
use Illuminate\Support\ServiceProvider;

/**
 * CategoryServiceProvider - Infrastructure Layer
 * 
 * Registra las dependencias e implementaciones
 */
class CategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Binding: cuando alguien pida CategoryRepositoryInterface,
        // Laravel le dará EloquentCategoryRepository
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );
    }
}
