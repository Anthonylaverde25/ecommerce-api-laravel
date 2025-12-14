<?php

declare(strict_types=1);

namespace App\Presentation\API\Product\Controllers;

use App\Application\Product\UseCases\Product\ProductCrudUseCase;
use App\Presentation\API\Product\Requests\IndexProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
final class ProductController
{
    public function __construct(
        private readonly ProductCrudUseCase $productCrudUseCase
    ) {
    }

    public function index(IndexProductRequest $request): JsonResponse
    {
        $products = $this->productCrudUseCase->index($request->getCriteria());
        return Response::json([
            'products' => $products
        ]);

    }

    public function show(int $id): JsonResponse
    {
        $product = $this->productCrudUseCase->show($id);
        return Response::json([
            'product' => $product
        ]);
    }
}
