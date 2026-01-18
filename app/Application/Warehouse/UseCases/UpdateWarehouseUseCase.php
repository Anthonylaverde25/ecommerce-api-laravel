<?php
namespace App\Application\Warehouse\UseCases;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Domain\Warehouse\DTOs\UpdateWarehouseData;
use App\Domain\Warehouse\Entities\Warehouse;
use Database\Seeders\WarehouseSeeder;

final class UpdateWarehouseUseCase
{
    public function __construct(
        private readonly WarehouseCrudRepository $repository
    ) {
    }

    public function execute(int $id, UpdateWarehouseData $data): Warehouse
    {
        // 1. Obtener la entidad existente
        $warehouse = $this->repository->show($id);

        // 2. Delegar la lógica de actualización a la entidad (DDD)
        $warehouse->update($data);

        // 3. Persistir la entidad actualizada
        return $this->repository->update($id, $warehouse);
    }
}