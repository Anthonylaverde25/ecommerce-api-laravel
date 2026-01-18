<?php
declare(strict_types=1);
namespace App\Infrastructure\Persistence\Eloquent\Repositories\Warehouse;

use App\Application\Warehouse\Contracts\WarehouseCrudRepository;
use App\Domain\Warehouse\Entities\Warehouse;
use App\Domain\Warehouse\Exceptions\WarehouseCreateException;
use App\Domain\Warehouse\Exceptions\WarehouseNotFoundException;
use App\Domain\Warehouse\Exceptions\WarehouseUpdateException;
use App\Infrastructure\Persistence\Eloquent\Mappers\Warehouse\WarehouseMapper;
use App\Models\Warehouse as EloquentWarehouse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EloquentWarehouseRepository implements WarehouseCrudRepository
{
    public function index(): array
    {
        $warehouses = EloquentWarehouse::all();
        return $warehouses->map(fn($w) => WarehouseMapper::toDomain($w))->values()->all();
    }

    public function show(int $id): Warehouse
    {
        try {
            $eloquentWarehouse = EloquentWarehouse::findOrFail($id);
            return WarehouseMapper::toDomain($eloquentWarehouse);

        } catch (ModelNotFoundException $e) {
            throw new WarehouseNotFoundException($id);
        }
    }

    public function create(Warehouse $warehouse): Warehouse
    {
        try {
            $eloquentWarehouse = EloquentWarehouse::create(
                WarehouseMapper::toEloquent($warehouse)
            );
            return WarehouseMapper::toDomain($eloquentWarehouse);

        } catch (\Exception $e) {
            throw new WarehouseCreateException($e->getMessage());
        }
    }

    public function update(int $id, Warehouse $warehouse): Warehouse
    {
        try {
            $eloquentWarehouse = EloquentWarehouse::findOrFail($id);
            $eloquentWarehouse->update(WarehouseMapper::toEloquent($warehouse));
            $eloquentWarehouse->refresh();
            return WarehouseMapper::toDomain($eloquentWarehouse);

        } catch (ModelNotFoundException $e) {
            throw new WarehouseNotFoundException($id);

        } catch (\Exception $e) {
            throw new WarehouseUpdateException($e->getMessage(), $id);
        }
    }
}