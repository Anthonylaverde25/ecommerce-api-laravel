<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Family;

use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Domain\Family\Entities\Family;
use App\Domain\Family\Exceptions\FamilyNotFoundException;
use App\Domain\Family\Exceptions\FamilyUpdateException;
use App\Domain\Family\Validation\FamilyTaxTypeValidator;
use App\Infrastructure\Persistence\Eloquent\Mappers\Family\FamilyMapper;
use App\Models\Family as EloquentFamily;
use App\Models\Tax as EloquentTax;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class EloquentFamilyRepository implements FamilyCrudRepository
{

    public function index(): array
    {
        $families = EloquentFamily::with('taxes')->get();
        return $families->map(fn($f) => FamilyMapper::toDomain($f))->values()->all();
    }

    public function create(Family $family): Family
    {

        $family->validateTaxTypes();

        $eloquentFamily = EloquentFamily::create(FamilyMapper::toEloquent($family));

        if (!empty($family->getTaxIds())) {
            $eloquentFamily->taxes()->sync($family->getTaxIds());
        }

        $eloquentFamily->load('taxes');
        return FamilyMapper::toDomain($eloquentFamily);
    }

    public function update(int $familyId, Family $family): Family
    {
        try {
            $family->validateTaxTypes();
            $eloquentFamily = EloquentFamily::findOrFail($familyId);

            // Actualizar campos básicos usando el mapper
            $eloquentFamily->update(FamilyMapper::toEloquent($family));

            // Sincronizar relación many-to-many con taxes
            if (!empty($family->getTaxIds())) {
                $eloquentFamily->taxes()->sync($family->getTaxIds());
            } else {
                $eloquentFamily->taxes()->detach();
            }

            // Recargar relaciones y convertir a dominio
            $eloquentFamily->refresh();
            $eloquentFamily->load('taxes');
            return FamilyMapper::toDomain($eloquentFamily);

        } catch (ModelNotFoundException $e) {
            throw new FamilyNotFoundException($familyId);

        } catch (\Exception $e) {
            throw new FamilyUpdateException($e->getMessage(), $familyId);
        }
    }

    public function show(int $familyId): Family
    {
        try {
            $family = EloquentFamily::with('taxes')->findOrFail($familyId);
            return FamilyMapper::toDomain($family);

        } catch (ModelNotFoundException $e) {
            throw new FamilyNotFoundException($familyId);
        }
    }



}
