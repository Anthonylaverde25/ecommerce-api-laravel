<?php
declare(strict_types=1);

namespace App\Presentation\API\Warehouse\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseCollection extends ResourceCollection
{
    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'warehouses' => $this->collection->map(function ($warehouse) {
                return [
                    'id' => $warehouse->getId(),
                    'name' => $warehouse->getName(),
                    'address' => $warehouse->getAddress(),
                ];
            })
        ];
    }
}
