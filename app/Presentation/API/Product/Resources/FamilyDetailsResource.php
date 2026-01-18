<?php
namespace App\Presentation\API\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyDetailsResource extends JsonResource
{
    public static $wrap = 'family';

    public function toArray(Request $request)
    {
        $family = $this->resource;
        $taxes = [];
        foreach ($family->getTaxes() as $tax) {
            $taxes[] = [
                'id' => $tax->getId(),
                'tax_code' => $tax->getTaxCode(),
                'name' => $tax->getName(),
                'tax_type_id' => $tax->getTaxTypeId(),
                'percentage' => $tax->getPercentage(),
                'active' => $tax->isActive(),
                'description' => $tax->getDescription(),
            ];
        }
        return [
            'id' => $family->getId(),
            'name' => $family->getName(),
            'code' => $family->getCode(),
            'description' => $family->getDescription(),
            'active' => $family->isActive(),
            'tax_ids' => $family->getTaxIds(),
            'taxes' => $taxes,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'message' => 'Family processed successfully',
        ];
    }
}