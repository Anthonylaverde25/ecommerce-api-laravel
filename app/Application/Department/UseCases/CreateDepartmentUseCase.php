<?php
namespace App\Application\Department\UseCases;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Domain\Department\DTOs\CreateDepartmentData;
use App\Domain\Department\Entities\Department;

class CreateDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentCrudRepository $repository
    ) {
    }

    public function execute(CreateDepartmentData $data): Department
    {
        $department = Department::create($data);
        return $this->repository->create($department);
    }
}