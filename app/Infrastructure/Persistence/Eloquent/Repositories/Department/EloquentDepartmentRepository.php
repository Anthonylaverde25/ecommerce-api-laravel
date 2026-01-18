<?php
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Department;

use App\Application\Department\Contracts\DepartmentCrudRepository;
use App\Domain\Department\Entities\Department;
use App\Infrastructure\Persistence\Eloquent\Mappers\Department\DepartmentMapper;
use App\Models\Department as EloquentDepartment;

class EloquentDepartmentRepository implements DepartmentCrudRepository
{
    public function index(): array
    {
        $departments = EloquentDepartment::all();
        return $departments->map(fn($d) => DepartmentMapper::toDomain($d))->values()->all();
    }

    public function show(int $id): Department
    {
        $department = EloquentDepartment::find($id);
        return DepartmentMapper::toDomain($department);
    }

    public function create(Department $data): Department
    {
        $eloquentDepartment = EloquentDepartment::create(DepartmentMapper::toEloquent($data));
        return DepartmentMapper::toDomain($eloquentDepartment);

    }

    public function update(int $id, Department $data): Department
    {
        $eloquentDepartment = EloquentDepartment::findOrFail($id);
        $eloquentDepartment->update(DepartmentMapper::toEloquent($data));
        return DepartmentMapper::toDomain($eloquentDepartment);
    }
}