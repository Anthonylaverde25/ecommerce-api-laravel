<?php

namespace App\Application\Tax\Contracts;

interface TaxActionRepository
{
    public function updateIsActive(int $taxId, bool $isActive): bool;
}