<?php
namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxActionRepository;

class UpdateActiveTaxUseCase
{
    public function __construct(
        private TaxActionRepository $repository
    ) {
    }



    public function execute(int $taxId, bool $active): bool
    {
        return $this->repository->updateIsActive($taxId, $active);
    }
}