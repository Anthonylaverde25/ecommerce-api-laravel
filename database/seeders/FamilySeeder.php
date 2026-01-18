<?php

namespace Database\Seeders;

use App\Models\Family;
use App\Models\Tax;
use Illuminate\Database\Seeder;

class FamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los taxes de IVA
        $iva0 = Tax::where('tax_code', '0003')->first(); // 0%
        $iva105 = Tax::where('tax_code', '0004')->first(); // 10.5%
        $iva21 = Tax::where('tax_code', '0005')->first(); // 21%
        $iva27 = Tax::where('tax_code', '0006')->first(); // 27%

        // Familias de productos con sus respectivos impuestos
        $families = [
            [
                'name' => 'Alimentos Básicos',
                'code' => 'ALM-BAS',
                'description' => 'Productos de la canasta básica alimentaria',
                'active' => true,
                'taxes' => [$iva105], // 10.5%
            ],
            [
                'name' => 'Bebidas',
                'code' => 'BEB',
                'description' => 'Bebidas alcohólicas y no alcohólicas',
                'active' => true,
                'taxes' => [$iva21], // 21%
            ],
            [
                'name' => 'Electrónica',
                'code' => 'ELEC',
                'description' => 'Productos electrónicos y tecnología',
                'active' => true,
                'taxes' => [$iva21], // 21%
            ],
            [
                'name' => 'Servicios Públicos',
                'code' => 'SERV-PUB',
                'description' => 'Telecomunicaciones, electricidad y gas',
                'active' => true,
                'taxes' => [$iva27], // 27%
            ],
            [
                'name' => 'Medicamentos',
                'code' => 'MED',
                'description' => 'Productos farmacéuticos y medicamentos',
                'active' => true,
                'taxes' => [$iva0], // 0% (exento)
            ],
            [
                'name' => 'Librería y Papelería',
                'code' => 'LIB-PAP',
                'description' => 'Libros, cuadernos y artículos escolares',
                'active' => true,
                'taxes' => [$iva21], // 21%
            ],
            [
                'name' => 'Indumentaria',
                'code' => 'IND',
                'description' => 'Ropa, calzado y accesorios',
                'active' => true,
                'taxes' => [$iva21], // 21%
            ],
            [
                'name' => 'Hogar y Decoración',
                'code' => 'HOG-DEC',
                'description' => 'Muebles, decoración y artículos para el hogar',
                'active' => true,
                'taxes' => [$iva21], // 21%
            ],
        ];

        foreach ($families as $familyData) {
            // Extraer los taxes antes de crear la familia
            $taxes = $familyData['taxes'];
            unset($familyData['taxes']);

            // Crear la familia
            $family = Family::create($familyData);

            // Asociar los impuestos a la familia
            if (!empty($taxes)) {
                $taxIds = array_map(fn($tax) => $tax->id, $taxes);
                $family->taxes()->attach($taxIds);
            }

            $this->command->info("✓ Created family: {$family->name} with " . count($taxes) . " tax(es)");
        }

        $this->command->info('');
        $this->command->info('✅ Families seeded successfully!');
        $this->command->info('📊 Created: ' . count($families) . ' families with tax associations');
    }
}
