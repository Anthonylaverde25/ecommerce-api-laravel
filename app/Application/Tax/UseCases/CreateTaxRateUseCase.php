<?php
namespace App\Application\Tax\UseCases;

use App\Application\Tax\Contracts\TaxCrudRepository;
use App\Domain\Tax\DTOs\CreateTaxData;
use App\Domain\Tax\Entities\Tax;

/**
 * Caso de uso: Crear un nuevo Tax Rate
 * 
 * Responsabilidades:
 * - Orquestar la creación de un impuesto
 * - Aplicar reglas de negocio
 * - Delegar la persistencia al repositorio
 */
class CreateTaxRateUseCase
{
    public function __construct(
        private TaxCrudRepository $repository
    ) {
    }

    /**
     * Ejecutar el caso de uso
     * 
     * @param CreateTaxData $data DTO con los datos validados
     * @return Tax Entidad de dominio creada y persistida
     */
    public function execute(CreateTaxData $data): Tax
    {
        // 1. Crear la entidad de dominio desde el DTO
        $tax = Tax::create($data);

        // 2. Aplicar validaciones de negocio adicionales si es necesario
        // Por ejemplo: verificar que no exista duplicado, etc.
        // $this->validateBusinessRules($tax);

        // 3. Persistir usando el repositorio
        // El repositorio se encarga de toda la conversión Eloquent ↔ Entidad
        $createdTax = $this->repository->create($tax);

        // 4. Aquí podrías disparar eventos de dominio si es necesario
        // event(new TaxCreatedEvent($createdTax));

        return $createdTax;
    }

    /**
     * Validaciones de negocio adicionales (ejemplo)
     * 
     * @param Tax $tax
     * @throws \DomainException
     */
    // private function validateBusinessRules(Tax $tax): void
    // {
    //     // Ejemplo: Validar que el porcentaje esté en un rango específico
    //     if ($tax->getPercentage() > 50) {
    //         throw new \DomainException('Tax percentage cannot exceed 50%');
    //     }
    // }
}