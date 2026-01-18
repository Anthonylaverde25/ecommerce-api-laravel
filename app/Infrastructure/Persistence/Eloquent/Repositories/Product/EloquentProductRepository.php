<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\Eloquent\Repositories\Product;

use App\Models\Item as EloquentProductModel;
use App\Application\Product\Contracts\ProductRepositoryInterface;
use Domain\Product\Entities\Item;
use Illuminate\Support\Facades\Log;

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
        // Log::info('criterios de busquedad', $criteria);
        // Start with a base query without stock
        $baseQuery = EloquentProductModel::query();

        // Apply filters to base query
        // Filter by category IDs if provided
        if (!empty($criteria['categoryIds'])) {
            $baseQuery->whereHas('categories', function ($q) use ($criteria) {
                $q->whereIn('categories.id', $criteria['categoryIds']);
            });
        }

        // Filter by minimum price if provided
        if (isset($criteria['minPrice'])) {
            $baseQuery->where('price', '>=', $criteria['minPrice']);
        }

        // Filter by maximum price if provided
        if (isset($criteria['maxPrice'])) {
            $baseQuery->where('price', '<=', $criteria['maxPrice']);
        }

        // Filter by search term if provided
        if (!empty($criteria['search'])) {
            $baseQuery->where(function ($q) use ($criteria) {
                $q->where('name', 'like', '%' . $criteria['search'] . '%')
                    ->orWhere('description', 'like', '%' . $criteria['search'] . '%');
            });
        }

        if (isset($criteria['sortBy'])) {
            $baseQuery->orderBy($criteria['sortBy'], $criteria['sortDirection']);
        }

        // Calculate price range from the filtered base query (without stock)
        $priceRange = (clone $baseQuery)->priceRange();

        $perPage = $criteria['perPage'] ?? 10;
        $paginatedProducts = (clone $baseQuery)
            ->withStock()
            ->paginate($perPage);

        // Get products with stock using the filtered query
        $products = $paginatedProducts
            ->map(fn(EloquentProductModel $model) => $this->toDomain($model))
            ->toArray();


        return [
            'data' => $products,
            'meta' => [
                'priceRange' => $priceRange,
                'pagination' => [
                    'total' => $paginatedProducts->total(),
                    'perPage' => $paginatedProducts->perPage(),
                    'currentPage' => $paginatedProducts->currentPage(),
                    'lastPage' => $paginatedProducts->lastPage(),
                    'from' => $paginatedProducts->firstItem(),
                    'to' => $paginatedProducts->lastItem(),
                    'nextPageUrl' => $paginatedProducts->nextPageUrl(),
                    'prevPageUrl' => $paginatedProducts->previousPageUrl(),
                    'path' => $paginatedProducts->path(),
                ]
            ]
        ];
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
            price_with_taxes: (float) $model->priceWithTaxes,
            cost_price: $model->cost_price ? (float) $model->cost_price : null,
            is_active: $model->is_active,
            stock: (int) $model->stock
        );
    }
}
