<?php
namespace App\Application\Warehouse\Services;

use App\Application\Warehouse\UseCases\CreateWarehouseUseCase;
use App\Application\Warehouse\UseCases\IndexWarehouseUseCase;
use App\Application\Warehouse\UseCases\ShowWarehouseUseCase;
use App\Application\Warehouse\UseCases\UpdateWarehouseUseCase;
use App\Domain\Warehouse\DTOs\CreateWarehouseData;
use App\Domain\Warehouse\DTOs\UpdateWarehouseData;
use App\Domain\Warehouse\Entities\Warehouse;

class WarehouseService
{
    public function __construct(
        private readonly IndexWarehouseUseCase $indexWarehouseUseCase,
        private readonly ShowWarehouseUseCase $showWarehouseUseCase,
        private readonly CreateWarehouseUseCase $createWarehouseUseCase,
        private readonly UpdateWarehouseUseCase $updateWarehouseUseCase
    ) {
    }


    public function index(): array
    {
        return $this->indexWarehouseUseCase->execute();
    }

    public function show(int $id): Warehouse
    {
        return $this->showWarehouseUseCase->execute($id);
    }

    public function create(CreateWarehouseData $data): Warehouse
    {
        return $this->createWarehouseUseCase->execute($data);
    }

    public function update(int $id, UpdateWarehouseData $data): Warehouse
    {
        return $this->updateWarehouseUseCase->execute($id, $data);
    }
}