<?php
namespace App\Presentation\API\Department\Controllers;

use App\Application\Department\Services\DepartmentService;
use App\Domain\Department\DTOs\CreateDepartmentData;
use App\Domain\Department\DTOs\UpdateDepartmentData;
use App\Presentation\API\Department\Requests\CreateDepartmentRequest;
use App\Presentation\API\Department\Requests\UpdateDepartmentRequest;
use App\Presentation\API\Department\Resources\DepartmentCollection;
use App\Presentation\API\Department\Resources\DepartmentDetailsResource;
use App\Presentation\API\Department\Resources\DepartmentResource;
use Illuminate\Http\JsonResponse;

class DepartmentController
{
    public function __construct(
        private readonly DepartmentService $departmentService
    ) {
    }

    public function index()
    {
        $departments = $this->departmentService->index();
        return (new DepartmentCollection($departments))
            ->response()
            ->setStatusCode(200);
    }

    public function show(int $id): JsonResponse
    {
        $department = $this->departmentService->show($id);
        return (new DepartmentDetailsResource($department))
            ->response()
            ->setStatusCode(200);
    }

    public function store(CreateDepartmentRequest $request): JsonResponse
    {
        $data = CreateDepartmentData::fromArray($request->validated());
        $department = $this->departmentService->create($data);

        return (new DepartmentResource($department))
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateDepartmentRequest $request): JsonResponse
    {
        $data = UpdateDepartmentData::fromArray($request->validated());
        $department = $this->departmentService->update($id, $data);

        return (new DepartmentResource($department))
            ->response()
            ->setStatusCode(200);
    }
}