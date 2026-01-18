<?php

declare(strict_types=1);
namespace App\Application\Product\Contracts;

use Domain\Product\Entities\Item;

/**
 * ProductRepositoryInterface - Applicaction Layer
 * Interfaz (contrato) para el repositorio de productos.
 * La implementación estará en Infrastructure Layer.
 */

interface ProductRepositoryInterface
{
    /**
     * Obtiene productos filtrados con metadata
     * @return array ['data' => Item[], 'meta' => ['priceRange' => ['min' => float, 'max' => float]]]
     */
    public function index(array $criteria = []): array;
    public function show(int $id): Item;
}