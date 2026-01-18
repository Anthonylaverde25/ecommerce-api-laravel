<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent\Repositories\Role;

use App\Application\Role\Contracts\RoleCrudRepository;
use App\Domain\Role\Entities\Role;
use App\Infrastructure\Persistence\Eloquent\Mappers\Role\RoleMapper;
use App\Models\Role as EloquentRole;

/**
 * EloquentRoleRepository - Infrastructure Layer
 * 
 * Implements RoleCrudRepository using Eloquent ORM
 */
class EloquentRoleRepository implements RoleCrudRepository
{
    /**
     * Get all roles
     * 
     * @return array<Role>
     */
    public function index(): array
    {
        $roles = EloquentRole::all();
        return $roles->map(fn($role) => RoleMapper::toDomain($role))->values()->all();
    }

    /**
     * Create a new role
     * 
     * @param Role $role Domain entity
     * @return Role Created role with ID assigned
     */
    public function create(Role $role): Role
    {
        $eloquentRole = EloquentRole::create(RoleMapper::toEloquent($role));
        return RoleMapper::toDomain($eloquentRole);
    }
}