<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories\Tax;

use App\Application\Tax\Contracts\TaxTypeCrudRepository;
use App\Domain\Tax\Entities\TaxType;
use App\Models\TaxType as EloquentTaxType;

class EloquentTaxTypeRepository implements TaxTypeCrudRepository
{
    public function index(): array
    {
        $taxTypes = EloquentTaxType::all();
        return $taxTypes->map(fn($t) => $this->mapToEntity($t))->values()->all();

    }



    private function mapToEntity(EloquentTaxType $taxType): TaxType
    {
        return TaxType::fromPrimitives(
            $taxType->id,
            $taxType->name,
            $taxType->code,
            $taxType->active,
        );
    }
}
