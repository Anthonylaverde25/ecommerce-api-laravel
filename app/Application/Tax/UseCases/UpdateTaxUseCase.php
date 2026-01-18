<?php
namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Domain\Tax\DTOs\UpdateTaxData;
use App\Domain\Tax\Entities\Tax;

class UpdateTaxUseCase
{
    public function __construct(
        private readonly TaxCrudRepository $taxRepository
    ) {
    }

    public function execute(int $taxId, UpdateTaxData $data): Tax
    {
        // 1. Obtener la entidad existente
        $tax = $this->taxRepository->show($taxId);

        // 2. Delegar la lógica de actualización a la entidad (DDD)
        $tax->update($data);

        // 3. Persistir la entidad actualizada
        return $this->taxRepository->update($taxId, $tax);
    }
}