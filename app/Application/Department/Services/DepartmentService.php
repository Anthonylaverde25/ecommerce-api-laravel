<?php
namespace App\Application\Department\Services;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Application\Department\UseCases\CreateDepartmentUseCase;
use App\Application\Department\UseCases\IndexDepartmentUseCase;
use App\Application\Department\UseCases\ShowDepartmentUseCase;
use App\Application\Department\UseCases\UpdateDepartmentUseCase;
use App\Domain\Department\DTOs\CreateDepartmentData;
use App\Domain\Department\DTOs\UpdateDepartmentData;
use App\Domain\Department\Entities\Department;

class DepartmentService
{
    public function __construct(
        private readonly IndexDepartmentUseCase $indexDepartmentUseCase,
        private readonly CreateDepartmentUseCase $createDepartmentUseCase,
        private readonly ShowDepartmentUseCase $showDepartmentUseCase,
        private readonly UpdateDepartmentUseCase $updateDepartmentUseCase,
    ) {
    }

    public function index(): array
    {
        return $this->indexDepartmentUseCase->execute();
    }

    public function show(int $id): Department
    {
        return $this->showDepartmentUseCase->execute($id);
    }

    public function create(CreateDepartmentData $data): Department
    {
        return $this->createDepartmentUseCase->execute($data);
    }

    public function update(int $id, UpdateDepartmentData $data): Department
    {
        return $this->updateDepartmentUseCase->execute($id, $data);
    }
}