<?php
declare(strict_types=1);

namespace App\Presentation\API\Tax\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaxCollection extends ResourceCollection
{
    /**
     * Disable the wrapping of the outer-most resource array.
     *
     * @var string|null
     */
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'taxes' => $this->collection->map(function ($tax) {
                return [
                    'id' => $tax->getId(),
                    'tax_code' => $tax->getTaxCode(),
                    'name' => $tax->getName(),
                    'tax_type_id' => $tax->getTaxTypeId(),
                    'percentage' => $tax->getPercentage(),
                    'active' => $tax->isActive(),
                    'description' => $tax->getDescription(),
                ];
            })->values()->toArray(),
            'message' => 'Taxes retrieved successfully',
        ];
    }

    /**
     * Extra data for the response.
     */
    public function with(Request $request): array
    {
        return [];
    }
}
