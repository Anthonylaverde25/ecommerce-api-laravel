<?php
declare(strict_types=1);

namespace App\Domain\Family\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando no se encuentra una Family
 */
class FamilyNotFoundException extends Exception
{
    public function __construct(int $familyId)
    {
        parent::__construct("Family with ID {$familyId} not found", 404);
    }

    public function getFamilyId(): int
    {
        return (int) $this->getMessage();
    }
}
