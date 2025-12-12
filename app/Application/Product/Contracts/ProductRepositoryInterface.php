<?php

declare(strict_types=1);
namespace Application\Product\Contracts;

use Domain\Product\Entities\Item;

/**
 * ProductRepositoryInterface - Applicaction Layer
 * Interfaz (contrato) para el repositorio de productos.
 * La implementación estará en Infrastructure Layer.
 */

interface ProductRepositoryInterface
{
    public function index():array;
    public function show(int $id): Item;
}