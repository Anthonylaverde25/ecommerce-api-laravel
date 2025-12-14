<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🏭 Seeding Warehouses...\n";

        // Crear warehouses
        $warehouses = [
            [
                'name' => 'Almacén Central',
                'address' => 'Av. Principal 123, Ciudad Capital',
            ],
            [
                'name' => 'Almacén Norte',
                'address' => 'Calle Norte 456, Zona Norte',
            ],
            [
                'name' => 'Almacén Sur',
                'address' => 'Av. Sur 789, Zona Sur',
            ],
            [
                'name' => 'Almacén Este - Distribución',
                'address' => 'Parque Industrial Este, Lote 12',
            ],
        ];

        $createdWarehouses = [];
        foreach ($warehouses as $warehouseData) {
            $createdWarehouses[] = Warehouse::create($warehouseData);
            echo "  ✓ Created: {$warehouseData['name']}\n";
        }

        // Obtener todos los items
        $items = Item::all();

        if ($items->isEmpty()) {
            echo "  ⚠️  No items found. Please run ItemSeeder first.\n";
            return;
        }

        echo "\n📦 Associating items with warehouses...\n";

        $totalAssociations = 0;
        $totalQuantity = 0;

        // Asociar items a warehouses con cantidades realistas
        foreach ($items as $item) {
            // Cada item estará en 2-4 warehouses aleatorios
            $numWarehouses = rand(2, 4);
            $randomWarehouses = collect($createdWarehouses)->random($numWarehouses);

            foreach ($randomWarehouses as $warehouse) {
                // Generar cantidad realista basada en el precio
                // Items más baratos = mayor cantidad
                // Items más caros = menor cantidad
                $baseQuantity = match (true) {
                    $item->price < 50 => rand(100, 500),
                    $item->price < 200 => rand(50, 200),
                    $item->price < 500 => rand(20, 100),
                    default => rand(5, 50),
                };

                $item->warehouses()->attach($warehouse->id, [
                    'quantity' => $baseQuantity,
                ]);

                $totalAssociations++;
                $totalQuantity += $baseQuantity;
            }
        }

        echo "\n✅ Warehouses seeded successfully!\n";
        echo "📊 Summary:\n";
        echo "   - " . count($createdWarehouses) . " warehouses created\n";
        echo "   - {$totalAssociations} item-warehouse associations\n";
        echo "   - Total items in all warehouses: {$totalQuantity}\n";
        echo "   - Average items per warehouse: " . round($totalQuantity / count($createdWarehouses)) . "\n";
    }
}
