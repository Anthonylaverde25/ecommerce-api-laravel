<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\Tax;

use App\Domain\Tax\Entities\Tax;
use App\Models\Tax as EloquentTax;

class TaxMapper
{
    /**
     * Mapear modelo Eloquent a entidad de dominio
     */
    public static function toDomain(EloquentTax $eloquentTax): Tax
    {
        // Convertir el TaxType de Eloquent a entidad de dominio si existe
        $taxTypeEntity = null;
        if ($eloquentTax->relationLoaded('taxType') && $eloquentTax->taxType) {
            $taxTypeEntity = TaxTypeMapper::toDomain($eloquentTax->taxType);
        }

        return Tax::fromPrimitives(
            id: $eloquentTax->id,
            tax_code: $eloquentTax->tax_code,
            name: $eloquentTax->name,
            tax_type_id: $eloquentTax->tax_type_id,
            percentage: $eloquentTax->porcentaje, // Nota: BD usa 'porcentaje', dominio usa 'percentage'
            active: (bool) $eloquentTax->active,
            description: $eloquentTax->description,
            taxType: $taxTypeEntity,
        );
    }

    /**
     * Mapear entidad de dominio a array para Eloquent
     * 
     * @return array<string, mixed>
     */
    public static function toEloquent(Tax $tax): array
    {
        return [
            'tax_code' => $tax->getTaxCode(),
            'name' => $tax->getName(),
            'tax_type_id' => $tax->getTaxTypeId(),
            'porcentaje' => $tax->getPercentage(), // Nota: BD usa 'porcentaje', dominio usa 'percentage'
            'active' => $tax->isActive(),
            'description' => $tax->getDescription(),
        ];
    }



}
