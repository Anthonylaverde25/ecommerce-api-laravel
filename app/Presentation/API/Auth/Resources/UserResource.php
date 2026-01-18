<?php

namespace App\Presentation\API\Auth\Resources;

use App\Domain\Auth\Entities\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = 'user';
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;
        return [
            'id' => $user->id(),
            'name' => $user->name(),
            'email' => $user->emailAsString(),
            'role_ids' => $user->roleIds(),
            'roles' => array_map(fn($role) => [
                'id' => $role->id(),
                'name' => $role->name(),
                // 'description' => $role->description(),
                // 'active' => $role->active(),
                // 'permissions' => $role->permissions(),
            ], $user->roles()),
            'department_ids' => $user->departmentIds(),
            'departments' => array_map(fn($department) => [
                'id' => $department->id(),
                'name' => $department->name(),
            ], $user->departments()),
            // 'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'message' => 'User processed successfully ',
        ];
    }
}
