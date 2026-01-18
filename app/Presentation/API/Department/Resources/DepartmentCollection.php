<?php

namespace App\Presentation\API\Department\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DepartmentCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'departments' => $this->collection->map(function ($department) {
                return [
                    'id' => $department->id(),
                    'name' => $department->name(),
                    'description' => $department->description(),
                    'status' => $department->status(),
                    'code' => $department->code(),
                ];
            })->values()->toArray(),
        ];
    }
}