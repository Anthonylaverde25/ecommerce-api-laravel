<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Eloquent\Mappers\Warehouse;

use App\Domain\Warehouse\Entities\Warehouse;
use App\Models\Warehouse as EloquentWarehouse;

class WarehouseMapper
{
    public static function toDomain(EloquentWarehouse $eloquentWarehouse): Warehouse
    {
        return Warehouse::fromPrimitives(
            id: $eloquentWarehouse->id,
            name: $eloquentWarehouse->name,
            address: $eloquentWarehouse->address,
        );
    }

    public static function toEloquent(Warehouse $warehouse): array
    {
        return [
            'name' => $warehouse->getName(),
            'address' => $warehouse->getAddress(),
        ];
    }
}