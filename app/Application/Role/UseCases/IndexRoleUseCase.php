<?php
declare(strict_types=1);

namespace App\Application\Role\UseCases;

use App\Application\Role\Contracts\RoleCrudRepository;

/**
 * IndexRoleUseCase - Application Layer
 * 
 * Use case for retrieving all roles
 */
class IndexRoleUseCase
{
    public function __construct(
        private readonly RoleCrudRepository $roleRepository,
    ) {
    }

    /**
     * Execute the use case
     * 
     * @return array<\App\Domain\Role\Entities\Role>
     */
    public function execute(): array
    {
        return $this->roleRepository->index();
    }
}