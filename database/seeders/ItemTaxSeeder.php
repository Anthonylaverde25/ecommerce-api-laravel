<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class ItemTaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener impuestos comunes
        $iva21 = Tax::where('tax_code', '0005')->first(); // IVA 21%
        $iva105 = Tax::where('tax_code', '0004')->first(); // IVA 10.5%
        $iva0 = Tax::where('tax_code', '0003')->first(); // IVA 0%

        if (!$iva21 || !$iva105 || !$iva0) {
            $this->command->error('Taxes not found. Please run TaxSeeder first.');
            return;
        }

        // Obtener todos los items
        $items = Item::all();

        if ($items->isEmpty()) {
            $this->command->warn('No items found to assign taxes.');
            return;
        }

        // Asignar impuestos a los items de forma aleatoria (puedes personalizar esta lógica)
        foreach ($items as $item) {
            // Por defecto, asignar IVA 21% a todos los items
            // Puedes personalizar esta lógica según tus necesidades de negocio
            $item->taxes()->attach($iva21->id);

            // Ejemplo: podrías asignar diferentes IVAs según categorías u otros criterios
            // if ($item->categories->contains('name', 'Alimentos')) {
            //     $item->taxes()->sync([$iva105->id]);
            // }
        }

        $this->command->info("Assigned IVA 21% to {$items->count()} items successfully!");
    }
}
