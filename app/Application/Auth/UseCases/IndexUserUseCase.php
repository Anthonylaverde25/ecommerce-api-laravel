<?php
namespace App\Application\Auth\UseCases;

use App\Application\Auth\Contracts\UserCrudRepository;

final class IndexUserUseCase
{
    public function __construct(
        private UserCrudRepository $repository,
    ) {
    }

    public function index(): array
    {
        return $this->repository->index();
    }
}