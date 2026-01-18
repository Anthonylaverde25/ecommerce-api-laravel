<?php
declare(strict_types=1);

namespace App\Domain\Tax\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando falla la actualización de un Tax
 */
class TaxUpdateException extends Exception
{
    private int $taxId;

    public function __construct(string $message, int $taxId)
    {
        $this->taxId = $taxId;
        parent::__construct("Failed to update tax {$taxId}: {$message}", 500);
    }

    public function getTaxId(): int
    {
        return $this->taxId;
    }
}
