<?php
namespace App\Presentation\API\Family\Controllers;

use App\Application\Family\Services\FamilyService;
use App\Domain\Family\DTOs\CreateFamilyData;
use App\Domain\Family\DTOs\UpdateFamilyData;
use App\Http\Controllers\Controller;
use App\Presentation\API\Family\Requests\CreateFamilyRequest;
use App\Presentation\API\Family\Requests\UpdateActiveFamilyRequest;
use App\Presentation\API\Family\Requests\UpdateFamilyRequest;
use App\Presentation\API\Product\Resources\FamilyCollection;
use App\Presentation\API\Product\Resources\FamilyDetailsResource;
use App\Presentation\API\Product\Resources\FamilyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class FamilyController extends Controller
{
    public function __construct(
        private readonly FamilyService $service
    ) {
    }


    public function index(): JsonResponse
    {
        $families = $this->service->index();
        return (new FamilyCollection($families))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateFamilyRequest $request): JsonResponse
    {
        $data = CreateFamilyData::fromArray($request->validated());
        $family = $this->service->create($data);

        return (new FamilyResource($family))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $familyId): JsonResponse
    {
        $family = $this->service->show($familyId);
        return (new FamilyDetailsResource($family))
            ->response()
            ->setStatusCode(200);
    }

    public function update(UpdateFamilyRequest $request, int $familyId): JsonResponse
    {
        $data = UpdateFamilyData::fromArray($request->validated());
        $family = $this->service->update($familyId, $data);

        return (new FamilyResource($family))
            ->response()
            ->setStatusCode(200);
    }

    public function updateActive(int $familyId, UpdateActiveFamilyRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $updated = $this->service->updateActive($familyId, $validated['active']);
        if (!$updated) {
            return response()->json([
                'message' => 'No se pudo actualizar el estado de la familia',
                'family_id' => $familyId
            ], 500);
        }

        return response()->json([
            'message' => 'Estado de la familia fue actualizado exitosamente',
            'family_id' => $familyId,
            'active' => $validated['active'],
            'success' => true
        ], 200);
    }
}