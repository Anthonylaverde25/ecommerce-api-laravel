<?php

namespace App\Application\Auth\Contracts;

use App\Domain\Auth\Entities\User;

interface UserCrudRepository
{
    public function index(): array;
    public function show(int $id): User;

    public function create(User $user): User;
    public function update(int $id, User $user): User;
}