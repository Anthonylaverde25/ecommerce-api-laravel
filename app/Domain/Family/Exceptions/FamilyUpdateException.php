<?php
declare(strict_types=1);

namespace App\Domain\Family\Exceptions;

use Exception;

/**
 * Excepción lanzada cuando falla la actualización de una Family
 */
class FamilyUpdateException extends Exception
{
    private int $familyId;

    public function __construct(string $message, int $familyId)
    {
        $this->familyId = $familyId;
        parent::__construct("Failed to update family {$familyId}: {$message}", 500);
    }

    public function getFamilyId(): int
    {
        return $this->familyId;
    }
}
