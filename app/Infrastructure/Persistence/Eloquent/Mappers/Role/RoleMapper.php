<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Mappers\Role;

use App\Domain\Role\Entities\Role;
use App\Models\Role as EloquentRole;

/**
 * RoleMapper - Infrastructure Layer
 * 
 * Maps between Domain Role entity and Eloquent Role model
 */
class RoleMapper
{
    /**
     * Convert Eloquent model to Domain entity
     */
    public static function toDomain(EloquentRole $eloquentRole): Role
    {
        return Role::fromPrimitives(
            id: $eloquentRole->id,
            name: $eloquentRole->name,
            description: $eloquentRole->description,
            active: (bool) $eloquentRole->active,
            permissions: $eloquentRole->permissions ?? []
        );
    }

    /**
     * Convert Domain entity to array for Eloquent model
     * Returns only the fields that should be persisted
     */
    public static function toEloquent(Role $role): array
    {
        return [
            'name' => $role->name(),
            'description' => $role->description(),
            'active' => $role->active(),
            'permissions' => $role->permissions(),
        ];
    }
}