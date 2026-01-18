<?php
namespace App\Infrastructure\Persistence\Eloquent\Mappers\User;

use App\Models\User as EloquentUser;
use App\Domain\Auth\Entities\User;
use App\Infrastructure\Persistence\Eloquent\Mappers\Role\RoleMapper;
use App\Infrastructure\Persistence\Eloquent\Mappers\Department\DepartmentMapper;


final class UserMapper
{
  public static function toDomain(EloquentUser $eloquentUser): User
  {
    $roles = [];
    $departments = [];


    if ($eloquentUser->relationLoaded('roles')) {
      $roles = $eloquentUser->roles->map(function ($eloquentRole) {
        return RoleMapper::toDomain($eloquentRole);
      })->all();
    }

    if ($eloquentUser->relationLoaded('departments')) {
      $departments = $eloquentUser->departments->map(
        fn($eloquentDepartment) =>
        DepartmentMapper::toDomain($eloquentDepartment)
      )->all();
    }



    return User::fromPrimitives(
      id: $eloquentUser->id,
      name: $eloquentUser->name,
      email: $eloquentUser->email,
      hashedPassword: $eloquentUser->password,
      createdAt: new \DateTimeImmutable($eloquentUser->created_at),
      updatedAt: new \DateTimeImmutable($eloquentUser->updated_at),
      roles: $roles,
      departments: $departments
    );
  }





  public static function toEloquent(User $user): array
  {
    return [
      'name' => $user->name(),
      'email' => $user->emailAsString(),
      'password' => $user->hashedPassword(),
      // role_ids and department_ids are handled separately via the relationship sync
    ];
  }
}