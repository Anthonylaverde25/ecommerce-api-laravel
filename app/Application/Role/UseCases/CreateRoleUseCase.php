<?php
declare(strict_types=1);

namespace App\Application\Role\UseCases;

use App\Application\Role\Contracts\RoleCrudRepository;
use App\Domain\Role\DTOs\CreateRoleData;
use App\Domain\Role\Entities\Role;

/**
 * CreateRoleUseCase - Application Layer
 * 
 * Use case for creating a new role
 */
class CreateRoleUseCase
{
    public function __construct(
        private readonly RoleCrudRepository $roleRepository,
    ) {
    }

    /**
     * Execute the use case
     * 
     * @param CreateRoleData $data Validated DTO with role data
     * @return Role Created role entity
     */
    public function execute(CreateRoleData $data): Role
    {
        // Create domain entity from DTO
        $role = Role::create($data);

        // Persist through repository
        return $this->roleRepository->create($role);
    }
}
