<?php
namespace App\Application\Family\UseCases;

use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Domain\Family\DTOs\CreateFamilyData;
use App\Domain\Family\Entities\Family;

class CreateFamilyUseCase
{
    public function __construct(
        private FamilyCrudRepository $repository
    ) {
    }

    /**
     * Ejecutar el caso de uso
     * 
     * @param CreateFamilyData $data DTO con los datos validados
     * @return Family Entidad de dominio creada y persistida
     */
    public function execute(CreateFamilyData $data): Family
    {
        $family = Family::create($data);
        $createFamily = $this->repository->create($family);
        return $createFamily;
    }
}