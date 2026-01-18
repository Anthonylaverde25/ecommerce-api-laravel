<?php
declare(strict_types=1);

namespace App\Application\Role\Contracts;

use App\Domain\Role\Entities\Role;

/**
 * RoleCrudRepository Contract - Application Layer
 * 
 * Defines the interface for Role persistence operations
 */
interface RoleCrudRepository
{
    /**
     * Get all roles
     * @return array<Role>
     */
    public function index(): array;

    /**
     * Create a new role
     * @param Role $role
     * @return Role
     */
    public function create(Role $role): Role;
}