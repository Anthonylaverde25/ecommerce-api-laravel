<?php
declare(strict_types=1);

namespace App\Presentation\API\Warehouse\Resources;

use App\Domain\Warehouse\Entities\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public static $wrap = 'warehouse';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        /** @var Warehouse $warehouse */
        $warehouse = $this->resource;

        return [
            'id' => $warehouse->getId(),
            'name' => $warehouse->getName(),
            'address' => $warehouse->getAddress(),
        ];
    }

    public function with(Request $request): array
    {
        return [
            'message' => 'Warehouse processed successfully',
        ];
    }
}
