<?php
declare(strict_types=1);

namespace App\Presentation\API\Tax\Resources;

use App\Domain\Tax\Entities\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public static $wrap = 'tax';

    public function toArray(Request $request): array
    {
        /** @var Tax $tax */
        $tax = $this->resource;

        $taxTypeData = null;
        if ($taxType = $tax->getTaxType()) {
            $taxTypeData = [
                'id' => $taxType->getId(),
                'name' => $taxType->getName(),
                'code' => $taxType->getCode(),
                'active' => $taxType->isActive(),
            ];
        }

        return [
            'id' => $tax->getId(),
            'tax_code' => $tax->getTaxCode(),
            'name' => $tax->getName(),
            'tax_type_id' => $tax->getTaxTypeId(),
            'percentage' => $tax->getPercentage(),
            'active' => $tax->isActive(),
            'description' => $tax->getDescription(),
            'tax_type' => $taxTypeData,
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
            // 'message' => 'Tax processed successfully',
        ];
    }
}
