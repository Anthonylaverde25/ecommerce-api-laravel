<?php
declare(strict_types=1);

namespace App\Domain\Warehouse\Exceptions;

class WarehouseUpdateException extends \Exception
{
    public function __construct(string $message, int $warehouseId)
    {
        parent::__construct("Failed to update warehouse {$warehouseId}: {$message}");
    }
}
