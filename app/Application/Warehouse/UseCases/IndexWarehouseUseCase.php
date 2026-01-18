<?php
namespace App\Application\Warehouse\UseCases;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Domain\Warehouse\Entities\Warehouse;

class IndexWarehouseUseCase
{
    public function __construct(
        private WarehouseCrudRepository $repository
    ) {
    }

    /**
     * Summary of execute
     * @return Warehouse[]
     */
    public function execute(): array
    {
        return $this->repository->index();

    }
}