<?php
namespace App\Application\Family\UseCases;

use App\Application\Family\Contracts\FamilyActionsRepository;

class UpdateActiveFamilyUseCase
{
    public function __construct(
        private FamilyActionsRepository $repository,
    ) {
    }

    public function execute(int $familyId, bool $active): bool
    {
        return $this->repository->updateIsActive($familyId, $active);
    }
}