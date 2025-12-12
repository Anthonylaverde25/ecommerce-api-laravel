<?php
declare(strict_types=1);
namespace App\Application\Product\UseCases\Product;
use Application\Product\Contracts\ProductRepositoryInterface;
use Domain\Product\Entities\Item;

final readonly class ProductCrudUseCase
{
    public function __construct(private ProductRepositoryInterface $productRepository){ }

    public function index():array
    {
        return $this->productRepository->index();
    }

    public function show(int $id): Item
    {
        return $this->productRepository->show($id);
    }
}