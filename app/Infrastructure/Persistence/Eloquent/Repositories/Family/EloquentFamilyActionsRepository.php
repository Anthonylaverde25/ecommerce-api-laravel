<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Family;

use App\Application\Family\Contracts\FamilyActionsRepository;
use App\Models\Family as EloquentFamily;


class EloquentFamilyActionsRepository implements FamilyActionsRepository
{
    public function updateIsActive(int $familyId, bool $isActive): bool
    {
        $family = EloquentFamily::findOrFail($familyId);
        $family->active = $isActive;
        return $family->save();
    }
}
