<?php
declare(strict_types=1);

namespace App\Application\Tax\Services;

use App\Application\Tax\UseCases\CreateTaxRateUseCase;
use App\Application\Tax\UseCases\IndexTaxTypeUseCase;
use App\Application\Tax\UseCases\IndexTaxUseCase;
use App\Application\Tax\UseCases\UpdateActiveTaxUseCase;
use App\Application\Tax\UseCases\ShowTaxUseCase;
use App\Application\Tax\UseCases\UpdateTaxUseCase;
use App\Domain\Tax\DTOs\CreateTaxData;
use App\Domain\Tax\DTOs\UpdateTaxData;
use App\Domain\Tax\Entities\Tax;
use App\Domain\Tax\Entities\TaxType;

/**
 * TaxService - Servicio unificado para operaciones de Tax
 * 
 * Este servicio agrupa todos los casos de uso de Tax en un solo lugar,
 * permitiendo una inyección más limpia en los controladores.
 * 
 * Patrón: Facade/Manager para casos de uso
 * 
 * Uso en controlador:
 * - $this->taxService->create($data)
 * - $this->taxService->update($id, $data)
 * - $this->taxService->delete($id)
 */
class TaxService
{
    public function __construct(
        private readonly CreateTaxRateUseCase $createTaxRateUseCase,
        private readonly UpdateTaxUseCase $updateTaxUseCase,
        private readonly IndexTaxUseCase $indexTaxUseCase,
        private readonly IndexTaxTypeUseCase $indexTaxTypeUseCase,
        private readonly ShowTaxUseCase $showTaxUseCase,
        private readonly UpdateActiveTaxUseCase $updateActiveTaxUseCase

    ) {
    }



    /**
     * Summary of index
     * @return Tax[]
     */
    public function index(): array
    {
        return $this->indexTaxUseCase->execute();
    }

    /**
     * Crear un nuevo Tax
     */
    public function create(CreateTaxData $data): Tax
    {
        return $this->createTaxRateUseCase->execute($data);
    }


    public function update(int $taxId, UpdateTaxData $data): Tax
    {
        return $this->updateTaxUseCase->execute($taxId, $data);
    }



    /**
     * Summary of indexTaxType
     * @return TaxType[]
     */

    public function indexTaxType(): array
    {
        return $this->indexTaxTypeUseCase->execute();
    }

    public function showTax(int $taxId): Tax
    {
        return $this->showTaxUseCase->execute($taxId);
    }

    public function updateActive(int $taxId, bool $active): bool
    {
        return $this->updateActiveTaxUseCase->execute($taxId, $active);
    }




}
