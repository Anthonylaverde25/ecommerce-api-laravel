<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Auth;

use App\Application\Auth\Contracts\UserCrudRepository;
use App\Infrastructure\Persistence\Eloquent\Mappers\User\UserMapper;
use App\Domain\Auth\Entities\User;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserCrudRepository
{
    public function index(): array
    {
        $users = EloquentUser::with(['roles', 'departments'])->get();
        return $users->map(fn($u) => UserMapper::toDomain($u))->values()->all();

    }

    public function show(int $id): User
    {
        $user = EloquentUser::with(['roles', 'departments'])->findOrFail($id);
        return UserMapper::toDomain($user);
    }



    public function create(User $user): User
    {
        $eloquentUser = EloquentUser::create(UserMapper::toEloquent($user));
        $this->syncRelationships($eloquentUser, $user);

        return UserMapper::toDomain($eloquentUser);
    }

    public function update(int $id, User $user): User
    {
        $eloquentUser = EloquentUser::findOrFail($id);
        $eloquentUser->update(UserMapper::toEloquent($user));
        $this->syncRelationships($eloquentUser, $user);



        $eloquentUser->load(['roles', 'departments']);
        return UserMapper::toDomain($eloquentUser);
    }

    private function syncRelationships(EloquentUser $eloquentUser, User $user): void
    {
        $roleIds = $user->roleIds();
        $departmentIds = $user->departmentIds();

        if (!empty($roleIds)) {
            $eloquentUser->roles()->sync($roleIds);
        }

        if (!empty($departmentIds)) {
            $eloquentUser->departments()->sync($departmentIds);
        }
    }
}