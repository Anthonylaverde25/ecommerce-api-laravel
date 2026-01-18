<?php
declare(strict_types=1);

namespace App\Domain\Family\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando una familia tiene taxes duplicados del mismo tipo
 */
class DuplicateTaxTypeException extends Exception
{
    public function __construct(int $familyId)
    {
        parent::__construct("Family with ID {$familyId} has duplicate tax types", 422);
    }

    public function getFamilyId(): int
    {
        return (int) $this->getCode();
    }
}
