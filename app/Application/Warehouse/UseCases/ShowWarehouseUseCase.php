<?php
namespace App\Application\Warehouse\UseCases;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Domain\Warehouse\Entities\Warehouse;

final class ShowWarehouseUseCase
{
    public function __construct(
        private readonly WarehouseCrudRepository $repository
    ) {
    }

    public function execute(int $id): Warehouse
    {
        return $this->repository->show($id);
    }
}
