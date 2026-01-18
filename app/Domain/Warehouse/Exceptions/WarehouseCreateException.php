<?php
declare(strict_types=1);

namespace App\Domain\Warehouse\Exceptions;

use Exception;

/**
 * Exception thrown when warehouse creation fails
 */
class WarehouseCreateException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("Failed to create warehouse: {$message}", 500);
    }
}
