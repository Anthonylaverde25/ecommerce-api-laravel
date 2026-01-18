<?php
namespace App\Application\Warehouse\Contracts;

use App\Domain\Warehouse\Entities\Warehouse;

interface WarehouseCrudRepository
{
    public function index(): array;
    public function show(int $id): Warehouse;
    public function create(Warehouse $warehouse): Warehouse;
    public function update(int $id, Warehouse $warehouse): Warehouse;
}