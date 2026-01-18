<?php
namespace App\Application\Department\UseCases;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Domain\Department\Entities\Department;

class ShowDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentCrudRepository $repository
    ) {
    }

    public function execute(int $id): Department
    {
        return $this->repository->show($id);
    }
}