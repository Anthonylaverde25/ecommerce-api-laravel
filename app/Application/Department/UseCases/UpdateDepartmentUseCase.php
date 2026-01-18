<?php
namespace App\Application\Department\UseCases;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Domain\Department\DTOs\UpdateDepartmentData;
use App\Domain\Department\Entities\Department;


class UpdateDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentCrudRepository $repository
    ) {
    }

    public function execute(int $id, UpdateDepartmentData $data): Department
    {
        $department = $this->repository->show($id);
        $department->update($data);
        return $this->repository->update($id, $department);

    }
}