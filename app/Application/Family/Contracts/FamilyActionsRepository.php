<?php

namespace App\Application\Family\Contracts;

interface FamilyActionsRepository
{
    public function updateIsActive(int $familyId, bool $isActive): bool;

}