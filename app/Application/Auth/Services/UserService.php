<?php
namespace App\Application\Auth\Services;

use App\Application\Auth\UseCases\CreateUserUseCase;
use App\Application\Auth\UseCases\IndexUserUseCase;
use App\Application\Auth\UseCases\ShowUserUseCase;
use App\Application\Auth\UseCases\UpdateUserUseCase;
use App\Domain\Auth\DTOs\CreateUserData;
use App\Domain\Auth\DTOs\UpdateUserData;
use App\Domain\Auth\Entities\User;

final class UserService
{
    public function __construct(
        private readonly IndexUserUseCase $indexUserUseCase,
        private readonly CreateUserUseCase $createUserUseCase,
        private readonly ShowUserUseCase $showUserUseCase,
        private readonly UpdateUserUseCase $updateUserUseCase
    ) {
    }

    public function index(): array
    {
        return $this->indexUserUseCase->index();
    }

    public function show(int $id): User
    {
        return $this->showUserUseCase->show($id);
    }

    public function create(CreateUserData $data): User
    {
        return $this->createUserUseCase->execute($data);
    }

    public function update(int $id, UpdateUserData $data): User
    {
        return $this->updateUserUseCase->execute($id, $data);
    }
}