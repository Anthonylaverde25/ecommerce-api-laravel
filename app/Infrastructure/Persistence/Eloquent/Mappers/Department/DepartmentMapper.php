<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\Department;

use App\Domain\Department\Entities\Department;
use App\Models\Department as EloquentDepartment;

class DepartmentMapper
{
    public static function toDomain(EloquentDepartment $eloquentDepartment): Department
    {
        return Department::fromPrimitives(
            id: $eloquentDepartment->id,
            name: $eloquentDepartment->name,
            code: $eloquentDepartment->code,
            description: $eloquentDepartment->description,
            status: $eloquentDepartment->status,
        );
    }


    public static function toEloquent(Department $departments): array
    {
        return [
            'name' => $departments->name(),
            'code' => $departments->code(),
            'description' => $departments->description(),
            'status' => $departments->status(),
        ];
    }
}