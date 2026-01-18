<?php
declare(strict_types=1);

namespace App\Presentation\API\Role\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * RoleCollection - Presentation Layer
 *
 * Transforms collection of Role entities to JSON response
 */
class RoleCollection extends ResourceCollection
{
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'roles' => $this->collection->map(fn($r) => [
                'id' => $r->id(),
                'name' => $r->name(),
            ])->values()->toArray(),
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    // public function with(Request $request): array
    // {
    //     return [
    //         'message' => 'Roles retrieved successfully',
    //     ];
    // }
}
