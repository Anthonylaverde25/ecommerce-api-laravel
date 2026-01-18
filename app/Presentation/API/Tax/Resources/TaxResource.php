<?php
declare(strict_types=1);

namespace App\Presentation\API\Tax\Resources;

use App\Domain\Tax\Entities\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Tax $tax */
        $tax = $this->resource;

        return [
            'id' => $tax->getId(),
            'tax_code' => $tax->getTaxCode(),
            'name' => $tax->getName(),
            'tax_type_id' => $tax->getTaxTypeId(),
            'percentage' => $tax->getPercentage(),
            'active' => $tax->isActive(),
            'description' => $tax->getDescription(),
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
            'message' => 'Tax processed successfully',
        ];
    }
}
