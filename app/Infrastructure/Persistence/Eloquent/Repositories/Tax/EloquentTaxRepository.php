<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories\Tax;

use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Domain\Tax\Entities\Tax;
use App\Domain\Tax\Exceptions\TaxNotFoundException;
use App\Domain\Tax\Exceptions\TaxUpdateException;
use App\Infrastructure\Persistence\Eloquent\Mappers\Tax\TaxMapper;
use App\Models\Tax as EloquentTax;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;


class EloquentTaxRepository implements TaxCrudRepository
{
    public function index(): array
    {
        $taxes = EloquentTax::all();
        return $taxes->map(fn($t) => TaxMapper::toDomain($t))->values()->all();
    }


    public function create(Tax $tax): Tax
    {
        // Convertir entidad de dominio a array para Eloquent
        $eloquentTax = EloquentTax::create(TaxMapper::toEloquent($tax));

        // Convertir modelo Eloquent de vuelta a entidad de dominio
        return TaxMapper::toDomain($eloquentTax);
    }

    public function update(int $taxId, Tax $tax): Tax
    {
        try {
            $eloquentTax = EloquentTax::findOrFail($taxId);
            $eloquentTax->update(TaxMapper::toEloquent($tax));
            $eloquentTax->refresh();
            return TaxMapper::toDomain($eloquentTax);

        } catch (ModelNotFoundException $e) {
            throw new TaxNotFoundException($taxId);

        } catch (\Exception $e) {
            throw new TaxUpdateException($e->getMessage(), $taxId);
        }
    }

    public function show(int $taxId): Tax
    {
        try {
            $tax = EloquentTax::with('taxType')->findOrFail($taxId);
            return TaxMapper::toDomain($tax);

        } catch (ModelNotFoundException $e) {
            throw new TaxNotFoundException($taxId);
        }
    }
}
