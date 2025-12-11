<?php

declare(strict_types=1);
namespace Application\Product\Contracts;

/**
 * ProductRepositoryInterface - Applicaction Layer
 * Interfaz (contrato) para el repositorio de productos.
 * La implementación estará en Infrastructure Layer.
 */

interface ProductRepositoryInterface
{
    public function index():array;
}