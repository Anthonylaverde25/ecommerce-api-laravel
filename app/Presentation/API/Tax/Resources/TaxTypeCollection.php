<?php
declare(strict_types=1);

namespace App\Presentation\API\Tax\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TaxTypeCollection extends ResourceCollection
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
            'tax_categories' => $this->collection->map(function ($taxType) {
                return [
                    'id' => $taxType->getId(),
                    'name' => $taxType->getName(),
                    'code' => $taxType->getCode(),
                    'active' => $taxType->isActive(),
                ];
            })->values()->toArray(),
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
