<?php
namespace App\Application\Family\Services;

use App\Application\Family\UseCases\CreateFamilyUseCase;
use App\Application\Family\UseCases\IndexFamilyUseCase;
use App\Application\Family\UseCases\showFamilyUseCase;
use App\Application\Family\UseCases\UpdateActiveFamilyUseCase;
use App\Application\Family\UseCases\UpdateFamilyUseCase;
use App\Domain\Family\DTOs\CreateFamilyData;
use App\Domain\Family\DTOs\UpdateFamilyData;
use App\Domain\Family\Entities\Family;

class FamilyService
{
    public function __construct(
        private readonly CreateFamilyUseCase $createFamilyUseCase,
        private readonly IndexFamilyUseCase $indexFamilyUseCase,
        private readonly UpdateActiveFamilyUseCase $updateActiveFamilyUseCase,
        private readonly showFamilyUseCase $showFamilyUseCase,
        private readonly UpdateFamilyUseCase $updateFamilyUseCase
    ) {
    }

    public function create(CreateFamilyData $data): Family
    {
        return $this->createFamilyUseCase->execute($data);
    }

    public function index()
    {
        return $this->indexFamilyUseCase->execute();
    }

    public function show(int $familyId): Family
    {
        return $this->showFamilyUseCase->execute($familyId);
    }

    public function updateActive(int $familyId, bool $active): bool
    {
        return $this->updateActiveFamilyUseCase->execute($familyId, $active);
    }

    public function update(int $familyId, UpdateFamilyData $data): Family
    {
        return $this->updateFamilyUseCase->execute($familyId, $data);
    }
}