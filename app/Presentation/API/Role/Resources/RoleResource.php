<?php
declare(strict_types=1);

namespace App\Presentation\API\Role\Resources;

use App\Domain\Role\Entities\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * RoleResource - Presentation Layer
 * 
 * Transforms Role domain entity to JSON response
 */
class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Role $role */
        $role = $this->resource;

        return [
            'id' => $role->id(),
            'name' => $role->name(),
            'description' => $role->description(),
            'active' => $role->active(),
            'permissions' => $role->permissions(),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'message' => 'Role processed successfully',
        ];
    }
}
