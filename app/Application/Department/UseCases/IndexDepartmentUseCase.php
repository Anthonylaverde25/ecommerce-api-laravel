<?php
namespace App\Application\Department\UseCases;

use App\Application\Department\Contracts\DepartmentCrudRepository;

class IndexDepartmentUseCase
{
    public function __construct(
        private readonly DepartmentCrudRepository $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->index();
    }
}