<?php
declare(strict_types=1);
namespace App\Presentation\API\Tax\Controllers;

use App\Application\Tax\Services\TaxService;
use App\Domain\Tax\DTOs\CreateTaxData;
use App\Domain\Tax\DTOs\UpdateTaxData;
use App\Http\Controllers\Controller;
use App\Presentation\API\Tax\Requests\CreateTaxRequest;
use App\Presentation\API\Tax\Requests\UpdateActiveRequest;
use App\Presentation\API\Tax\Requests\UpdateTaxRequest;
use App\Presentation\API\Tax\Resources\TaxCollection;
use App\Presentation\API\Tax\Resources\TaxDetailResource;
use App\Presentation\API\Tax\Resources\TaxResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TaxController extends Controller
{
    public function __construct(
        private readonly TaxService $taxService
    ) {
    }

    public function index(): JsonResponse
    {
        $taxes = $this->taxService->index();
        return (new TaxCollection($taxes))
            ->response()
            ->setStatusCode(200);
    }

    public function show(int $taxId): JsonResponse
    {
        $tax = $this->taxService->showTax($taxId);
        return (new TaxDetailResource($tax))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateTaxRequest $request): JsonResponse
    {
        $data = CreateTaxData::fromArray($request->validated());
        $tax = $this->taxService->create($data);

        return (new TaxResource($tax))
            ->response()
            ->setStatusCode(201);
    }


    public function update(UpdateTaxRequest $request, int $taxId): JsonResponse
    {
        $data = UpdateTaxData::fromArray($request->validated());
        $tax = $this->taxService->update($taxId, $data);

        return (new TaxResource($tax))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Actualizar el estado activo de un impuesto
     * 
     * @param int $taxId El ID del impuesto (viene de la ruta)
     * @param UpdateActiveRequest $request Contiene el campo 'active'
     * @return JsonResponse
     */
    public function updateActive(int $taxId, UpdateActiveRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $updated = $this->taxService->updateActive($taxId, $validated['active']);

        if (!$updated) {
            return response()->json([
                'message' => 'No se pudo actualizar el estado del impuesto',
                'tax_id' => $taxId
            ], 500);
        }

        return response()->json([
            'message' => 'Estado del impuesto actualizado exitosamente',
            'tax_id' => $taxId,
            'active' => $validated['active'],
            'success' => true
        ], 200);
    }
}