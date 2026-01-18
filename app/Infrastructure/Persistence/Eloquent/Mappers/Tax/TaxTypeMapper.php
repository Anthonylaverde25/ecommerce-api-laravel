<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\Tax;

use App\Domain\Tax\Entities\TaxType;
use App\Models\TaxType as EloquentTaxType;

class TaxTypeMapper
{
    /**
     * Mapear modelo Eloquent a entidad de dominio
     */
    public static function toDomain(EloquentTaxType $eloquentTaxType): TaxType
    {
        return TaxType::fromPrimitives(
            id: $eloquentTaxType->id,
            name: $eloquentTaxType->name,
            code: $eloquentTaxType->code,
            active: (bool) $eloquentTaxType->active,
        );
    }

    /**
     * Mapear entidad de dominio a array para Eloquent
     * 
     * @return array<string, mixed>
     */
    public static function toEloquent(TaxType $taxType): array
    {
        return [
            'id' => $taxType->getId(),
            'name' => $taxType->getName(),
            'code' => $taxType->getCode(),
            'active' => $taxType->isActive(),
        ];
    }
}
