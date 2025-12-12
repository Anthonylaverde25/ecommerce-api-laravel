<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories;

use App\Models\Item as EloquentProductModel;
use Application\Product\Contracts\ProductRepositoryInterface;
use Domain\Product\Entities\Item;

/**
 * Implementación del repositorio de productos usando Eloquent.
 * Responsabilidades:
 * - Convertir entre Entities (Domain) y Models (Eloquent)
 * - Realizar las operaciones de persistencia
 */
final class EloquentProductRepository implements ProductRepositoryInterface
{
    public function index(): array
    {
        return EloquentProductModel::all()
            ->map(fn(EloquentProductModel $model) => $this->toDomain(($model)))
            ->toArray();
    }


    public function show(int $id):Item
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
        );
    }
}
