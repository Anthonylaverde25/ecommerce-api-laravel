<?php
namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Domain\Tax\Entities\Tax;

class IndexTaxUseCase
{
    public function __construct(
        private TaxCrudRepository $repository
    ) {
    }

    /**
     * @return Tax[]
     */
    public function execute(): array
    {
        return $this->repository->index();
    }

}