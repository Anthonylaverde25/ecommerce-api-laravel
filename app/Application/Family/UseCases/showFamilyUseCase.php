<?php
namespace App\Application\Family\UseCases;

use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Domain\Family\Entities\Family;

class showFamilyUseCase
{
    public function __construct(
        private readonly FamilyCrudRepository $familyRepository,
    ) {
    }

    public function execute(int $familyId): Family
    {
        return $this->familyRepository->show($familyId);
    }


}