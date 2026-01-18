<?php

namespace App\Presentation\API\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'users' => $this->collection
                ->map(fn($user) => [
                    'id' => $user->id(),
                    'name' => $user->name(),
                    'email' => $user->emailAsString(),
                    'roles' => collect($user->roles())->map(fn($role) => [
                        'id' => $role->id(),
                        'name' => $role->name(),
                        'description' => $role->description(),
                        'active' => $role->active(),
                    ])->toArray(),
                    'departments' => collect($user->departments())->map(fn($department) => [
                        'id' => $department->id(),
                        'name' => $department->name(),
                    ])->toArray(),
                    'created_at' => $user->createdAt()->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updatedAt()->format('Y-m-d H:i:s'),
                ])
                ->values()
                ->toArray(),
        ];
    }

    public function with(Request $request): array
    {
        return [];
    }
}
