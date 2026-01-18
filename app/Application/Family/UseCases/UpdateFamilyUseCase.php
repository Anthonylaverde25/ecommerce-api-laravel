<?php
declare(strict_types=1);

namespace App\Application\Family\UseCases;

use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Domain\Family\DTOs\UpdateFamilyData;
use App\Domain\Family\Entities\Family;

class UpdateFamilyUseCase
{
    public function __construct(
        private readonly FamilyCrudRepository $familyRepository
    ) {
    }

    public function execute(int $familyId, UpdateFamilyData $data): Family
    {
        // 1. Obtener la entidad existente
        $family = $this->familyRepository->show($familyId);

        // 2. Delegar la lógica de actualización a la entidad (DDD)
        $family->update($data);

        // 3. Persistir la entidad actualizada
        return $this->familyRepository->update($familyId, $family);
    }
}
