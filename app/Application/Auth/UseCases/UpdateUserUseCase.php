<?php
namespace App\Application\Auth\UseCases;

use App\Application\Auth\Contracts\UserCrudRepository;
use App\Domain\Auth\DTOs\UpdateUserData;
use App\Domain\Auth\Entities\User;

class UpdateUserUseCase
{
    public function __construct(
        private UserCrudRepository $repository
    ) {
    }

    public function execute(int $id, UpdateUserData $data): User
    {
        $user = $this->repository->show($id);
        $user->update($data);
        return $this->repository->update($id, $user);
    }

}