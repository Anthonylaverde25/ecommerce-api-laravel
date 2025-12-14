<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories;

use App\Models\Item as EloquentProductModel;
use App\Application\Product\Contracts\ProductRepositoryInterface;
use Domain\Product\Entities\Item;

/**
 * Implementación del repositorio de productos usando Eloquent.
 * Responsabilidades:
 * - Convertir entre Entities (Domain) y Models (Eloquent)
 * - Realizar las operaciones de persistencia
 */
final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function index(array $criteria = []): array
    {
        $query = EloquentProductModel::withStock();

        // Filter by category IDs if provided
        if (!empty($criteria['categoryIds'])) {
            $query->whereHas('categories', function ($q) use ($criteria) {
                $q->whereIn('categories.id', $criteria['categoryIds']);
            });
        }

        // Filter by minimum price if provided
        if (isset($criteria['minPrice'])) {
            $query->where('price', '>=', $criteria['minPrice']);
        }

        // Filter by maximum price if provided
        if (isset($criteria['maxPrice'])) {
            $query->where('price', '<=', $criteria['maxPrice']);
        }

        // Filter by search term if provided
        if (!empty($criteria['search'])) {
            $query->where(function ($q) use ($criteria) {
                $q->where('name', 'like', '%' . $criteria['search'] . '%')
                    ->orWhere('description', 'like', '%' . $criteria['search'] . '%');
            });
        }

        return $query->get()
            ->map(fn(EloquentProductModel $model) => $this->toDomain($model))
            ->toArray();
    }


    public function show(int $id): Item
    {
        $item = EloquentProductModel::findOrFail($id);
        return $this->toDomain($item);
    }

    /**
     * Convierte un Item (Eloquent Model)
     * a una instancia de la entidad Item del Dominio (Domain Entity)
     */
    private function toDomain(EloquentProductModel $model): Item
    {
        return new Item(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            price: (float) $model->price,
            cost_price: $model->cost_price ? (float) $model->cost_price : null,
            is_active: $model->is_active,
            stock: (int) $model->stock
        );
    }
}
