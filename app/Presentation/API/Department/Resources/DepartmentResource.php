<?php
namespace App\Presentation\API\Department\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public static $wrap = 'department';
    public function toArray(Request $request): array
    {
        $department = $this->resource;

        return [
            'id' => $department->id(),
            'name' => $department->name(),
            'code' => $department->code(),
            'description' => $department->description(),
            'status' => $department->status()
        ];
    }

    public function with(Request $request): array
    {
        return [
            'message' => 'Department created successfully'
        ];
    }



}