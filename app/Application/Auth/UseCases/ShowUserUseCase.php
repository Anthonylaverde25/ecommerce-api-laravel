<?php
namespace App\Application\Auth\UseCases;

use App\Application\Auth\Contracts\UserCrudRepository;
use App\Domain\Auth\Entities\User;

class ShowUserUseCase
{
    public function __construct(
        private UserCrudRepository $repository,
    ) {
    }

    public function show(int $id): User
    {
        return $this->repository->show($id);
    }
}