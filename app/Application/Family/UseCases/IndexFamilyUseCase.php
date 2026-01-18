<?php
namespace App\Application\Family\UseCases;

use App\Application\Family\Contracts\FamilyCrudRepository;
use App\Domain\Family\Entities\Family;

class IndexFamilyUseCase
{

    public function __construct(
        private FamilyCrudRepository $repository
    ) {
    }

    /**
     * 
     * @return Family[]
     */
    public function execute()
    {
        return $this->repository->index();
    }
}