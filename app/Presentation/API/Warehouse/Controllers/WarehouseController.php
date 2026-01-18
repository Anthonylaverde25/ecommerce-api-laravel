<?php
namespace App\Presentation\API\Warehouse\Controllers;

use App\Application\Warehouse\Services\WarehouseService;
use App\Domain\Warehouse\DTOs\CreateWarehouseData;
use App\Domain\Warehouse\DTOs\UpdateWarehouseData;
use App\Http\Controllers\Controller;
use App\Presentation\API\Warehouse\Requests\CreateWarehouseRequest;
use App\Presentation\API\Warehouse\Requests\UpdateWarehouseRequest;
use App\Presentation\API\Warehouse\Resources\WarehouseCollection;
use App\Presentation\API\Warehouse\Resources\WarehouseResource;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    public function __construct(
        private readonly WarehouseService $warehouseService
    ) {
    }

    public function index(): JsonResponse
    {
        $warehouses = $this->warehouseService->index();
        return (new WarehouseCollection($warehouses))
            ->response()
            ->setStatusCode(200);
    }

    public function show(int $id): JsonResponse
    {
        $warehouse = $this->warehouseService->show($id);
        return (new WarehouseResource($warehouse))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateWarehouseRequest $request): JsonResponse
    {
        $data = CreateWarehouseData::fromArray($request->validated());
        $warehouse = $this->warehouseService->create($data);

        return (new WarehouseResource($warehouse))
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateWarehouseRequest $request): JsonResponse
    {
        $data = UpdateWarehouseData::fromArray($request->validated());
        $warehouse = $this->warehouseService->update($id, $data);

        return (new WarehouseResource($warehouse))
            ->response()
            ->setStatusCode(200);
    }

}

