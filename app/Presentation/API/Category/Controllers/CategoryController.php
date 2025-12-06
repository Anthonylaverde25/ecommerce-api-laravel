<?php

declare(strict_types=1);

namespace Presentation\API\Category\Controllers;

use Application\Category\UseCases\CreateCategory\CreateCategoryDTO;
use Application\Category\UseCases\CreateCategory\CreateCategoryUseCase;
use Application\Category\UseCases\GetCategories\GetCategoriesUseCase;
use Illuminate\Http\JsonResponse;
use Presentation\API\Category\Requests\CreateCategoryRequest;
use Presentation\API\Category\Resources\CategoryResource;

/**
 * CategoryController - Presentation Layer
 * 
 * El controller es DELGADO. Solo:
 * 1. Recibe la request HTTP
 * 2. Convierte a DTO
 * 3. Llama al caso de uso
 * 4. Convierte la respuesta a JSON
 * 
 * NO contiene lógica de negocio.
 */
final class CategoryController
{
    public function __construct(
        private readonly CreateCategoryUseCase $createCategoryUseCase,
        private readonly GetCategoriesUseCase $getCategoriesUseCase
    ) {
    }

    /**
     * GET /api/categories
     */
    public function index(): JsonResponse
    {
        $categories = $this->getCategoriesUseCase->execute();

        return response()->json([
            'categories' => CategoryResource::collection($categories),
            'message' => 'Categories retrieved successfully'
        ]);
    }

    /**
     * GET /api/categories/root
     */
    public function rootCategories(): JsonResponse
    {
        $categories = $this->getCategoriesUseCase->executeRootCategories();

        return response()->json([
            'data' => CategoryResource::collection($categories),
            'message' => 'Root categories retrieved successfully'
        ]);
    }

    /**
     * POST /api/categories
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        // La validación ya fue hecha por CreateCategoryRequest
        $dto = CreateCategoryDTO::fromArray($request->validated());

        // Ejecutar el caso de uso
        $response = $this->createCategoryUseCase->execute($dto);

        // Retornar respuesta HTTP
        return response()->json([
            'data' => new CategoryResource($response),
            'message' => 'Category created successfully'
        ], 201);
    }

    /**
     * GET /api/public/categories/{id}
     */
    public function show(int $id): JsonResponse
    {
        // TODO: Implementar GetCategoryByIdUseCase
        // Por ahora, usar Eloquent directamente (temporal)
        $category = \App\Models\Category::with('children')->findOrFail($id);

        return response()->json([
            'data' => new CategoryResource($category),
            'message' => 'Category retrieved successfully'
        ]);
    }

    /**
     * PUT /api/admin/categories/{id}
     */
    public function update(int $id): JsonResponse
    {
        // TODO: Implementar UpdateCategoryUseCase
        return response()->json([
            'message' => 'Update not implemented yet'
        ], 501);
    }

    /**
     * DELETE /api/admin/categories/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        // TODO: Implementar DeleteCategoryUseCase
        return response()->json([
            'message' => 'Delete not implemented yet'
        ], 501);
    }
}
