<?php
declare(strict_types=1);

namespace App\Domain\Tax\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando no se encuentra un Tax
 */
class TaxNotFoundException extends Exception
{
    public function __construct(int $taxId)
    {
        parent::__construct("Tax with ID {$taxId} not found", 404);
    }

    public function getTaxId(): int
    {
        return (int) $this->getMessage();
    }
}
