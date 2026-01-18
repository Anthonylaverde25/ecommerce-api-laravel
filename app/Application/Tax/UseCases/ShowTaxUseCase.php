<?php
namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Domain\Tax\Entities\Tax;

class ShowTaxUseCase
{
    public function __construct(
        private readonly TaxCrudRepository $taxRepository
    ) {
    }
    public function execute(int $taxId): Tax
    {
        return $this->taxRepository->show($taxId);

    }
}