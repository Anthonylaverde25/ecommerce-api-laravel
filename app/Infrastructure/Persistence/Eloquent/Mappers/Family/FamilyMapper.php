<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\Family;

use App\Domain\Family\Entities\Family;
use App\Infrastructure\Persistence\Eloquent\Mappers\Tax\TaxMapper;
use App\Models\Family as EloquentFamily;

class FamilyMapper
{
    /**
     * Mapear modelo Eloquent a entidad de dominio
     */
    public static function toDomain(EloquentFamily $eloquentFamily): Family
    {
        // Mapear taxes completos si la relación está cargada
        $taxes = [];
        if ($eloquentFamily->relationLoaded('taxes') && $eloquentFamily->taxes) {
            $taxes = $eloquentFamily->taxes->map(function ($tax) {
                return TaxMapper::toDomain($tax);
            })->all();
        }

        return Family::fromPrimitives(
            id: $eloquentFamily->id,
            name: $eloquentFamily->name,
            code: $eloquentFamily->code,
            description: $eloquentFamily->description,
            active: (bool) $eloquentFamily->active,
            taxes: $taxes
        );
    }

    /**
     * Mapear entidad de dominio a array para Eloquent
     * 
     * @return array<string, mixed>
     */
    public static function toEloquent(Family $family): array
    {
        return [
            'name' => $family->getName(),
            'code' => $family->getCode(),
            'description' => $family->getDescription(),
            'active' => $family->isActive(),
            // Nota: tax_ids se sincroniza por separado usando sync() en el repositorio
        ];
    }
}
