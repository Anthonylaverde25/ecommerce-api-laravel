<?php
declare(strict_types=1);

namespace App\Application\Role\Services;

use App\Application\Role\UseCases\CreateRoleUseCase;
use App\Application\Role\UseCases\IndexRoleUseCase;
use App\Domain\Role\DTOs\CreateRoleData;
use App\Domain\Role\Entities\Role;

/**
 * RoleService - Application Layer
 * 
 * Service that orchestrates Role use cases
 */
class RoleService
{
    public function __construct(
        private readonly IndexRoleUseCase $indexRoleUseCase,
        private readonly CreateRoleUseCase $createRoleUseCase,
    ) {
    }

    /**
     * Get all roles
     * 
     * @return array<Role>
     */
    public function index(): array
    {
        return $this->indexRoleUseCase->execute();
    }

    /**
     * Create a new role
     * 
     * @param CreateRoleData $data
     * @return Role
     */
    public function create(CreateRoleData $data): Role
    {
        return $this->createRoleUseCase->execute($data);
    }
}