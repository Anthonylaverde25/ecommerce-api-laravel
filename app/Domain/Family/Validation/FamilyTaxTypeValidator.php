<?php

namespace App\Domain\Family\Validation;

use App\Domain\Family\Entities\Family;
use App\Models\Tax as EloquentTax;
use Illuminate\Support\Collection;

class FamilyTaxTypeValidator
{
    /**
     * Códigos de impuestos que deben ser únicos por familia.
     */
    private static array $uniqueTaxCodes = ['IVA'];

    /**
     * Verifica si una colección de impuestos tiene códigos únicos.
     *
     * @param Collection $taxes
     * @return bool true si todos los códigos son únicos, false si hay duplicados
     */
    public static function hasUniqueCodes(Collection $taxes): bool
    {
        $codes = $taxes->pluck('taxType.code')->toArray();

        $counts = array_count_values($codes);

        $duplicates = array_filter($counts, fn($c) => $c > 1);

        return empty($duplicates);
    }

    /**
     * Valida que la familia no tenga impuestos duplicados en los códigos que deben ser únicos.
     *
     * @param Family $family
     * @return bool true si válido, false si hay duplicados
     */
    public static function validate(Family $family): bool
    {
        $taxIds = $family->getTaxIds();

        if (empty($taxIds)) {
            return true;
        }

        $taxes = EloquentTax::whereIn('id', $taxIds)
            ->whereHas('taxType', fn($q) => $q->whereIn('code', self::$uniqueTaxCodes))
            ->get();

        return $taxes->isNotEmpty() ? self::hasUniqueCodes($taxes) : true;
    }
}
