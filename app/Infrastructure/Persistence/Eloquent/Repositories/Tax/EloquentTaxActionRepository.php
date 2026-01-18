<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Tax;

use App\Application\Tax\Contracts\TaxActionRepository;
use App\Models\Tax as EloquentTax;

class EloquentTaxActionRepository implements TaxActionRepository
{
    public function updateIsActive(int $taxId, bool $isActive): bool
    {
        $tax = EloquentTax::findOrFail($taxId);

        $tax->active = $isActive;
        return $tax->save();
    }


}
