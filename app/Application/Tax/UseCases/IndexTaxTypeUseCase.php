<?php

namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxTypeCrudRepository;
use App\Domain\Tax\Entities\TaxType;

class IndexTaxTypeUseCase
{
    public function __construct(
        private TaxTypeCrudRepository $repository
    ) {
    }

    /**
     * @return TaxType[]
     */

    public function execute(): array
    {
        return $this->repository->index();
    }
}