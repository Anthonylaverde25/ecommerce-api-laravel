<?php
declare(strict_types=1);

namespace App\Presentation\API\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FamilyCollection extends ResourceCollection
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'families' => $this->collection->map(function ($family) {
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
                    'taxes' => $taxes,
                ];
            })
        ];
    }
}