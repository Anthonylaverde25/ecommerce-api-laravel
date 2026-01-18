<?php
namespace App\Application\Department\Contracts;

use App\Domain\Department\Entities\Department;

interface DepartmentCrudRepository
{
    public function index(): array;
    public function show(int $id): Department;
    public function create(Department $data): Department;
    public function update(int $id, Department $data): Department;
}