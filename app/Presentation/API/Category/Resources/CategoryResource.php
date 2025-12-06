<?php

declare(strict_types=1);

namespace Presentation\API\Category\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * CategoryResource - Presentation Layer
 * 
 * Transforma la respuesta del caso de uso a formato JSON
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array
     */
    public function toArray($request): array
    {
        // Si es un Model de Eloquent, acceso directo
        if ($this->resource instanceof \App\Models\Category) {
            return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'description' => $this->resource->description,
                'parent_id' => $this->resource->parent_id,
                'is_active' => (bool) $this->resource->is_active,
            ];
        }

        // Si es una Domain Entity, usar getters
        return [
            'id' => $this->resource->id(),
            'name' => $this->resource->name()->value(),
            'description' => $this->resource->description(),
            'parent_id' => $this->resource->parentId(),
            'is_active' => $this->resource->isActive(),
        ];
    }
}
