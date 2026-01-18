<?php
namespace App\Application\Auth\UseCases;

use App\Application\Auth\Contracts\UserCrudRepository;
use App\Domain\Auth\DTOs\CreateUserData;
use App\Domain\Auth\Entities\User;

class CreateUserUseCase
{
    public function __construct(
        private readonly UserCrudRepository $repository
    ) {
    }

    public function execute(CreateUserData $data): User
    {
        $user = User::create($data);
        return $this->repository->create($user);
    }
}