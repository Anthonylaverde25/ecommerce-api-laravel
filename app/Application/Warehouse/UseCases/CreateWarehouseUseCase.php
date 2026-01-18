<?php
declare(strict_types=1);
namespace App\Application\Warehouse\UseCases;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Domain\Warehouse\DTOs\CreateWarehouseData;
use App\Domain\Warehouse\Entities\Warehouse;

class CreateWarehouseUseCase
{
    public function __construct(
        private WarehouseCrudRepository $repository
    ) {
    }

    public function execute(CreateWarehouseData $data): Warehouse
    {
        $warehouse = Warehouse::create($data);
        return $this->repository->create($warehouse);
    }
}
