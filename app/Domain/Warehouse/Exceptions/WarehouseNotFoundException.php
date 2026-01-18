<?php
declare(strict_types=1);

namespace App\Domain\Warehouse\Exceptions;

class WarehouseNotFoundException extends \Exception
{
    public function __construct(int $warehouseId)
    {
        parent::__construct("Warehouse with ID {$warehouseId} not found");
    }
}
