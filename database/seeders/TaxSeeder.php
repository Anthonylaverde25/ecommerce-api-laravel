<?php

namespace Database\Seeders;

use App\Models\Tax;
use App\Models\TaxType;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el tax_type IVA
        $ivaTaxType = TaxType::where('code', 'IVA')->first();

        if (!$ivaTaxType) {
            $this->command->error('Tax type IVA not found. Please run TaxTypeSeeder first.');
            return;
        }

        // Alícuotas oficiales de IVA según AFIP
        $ivaRates = [
            [
                'code' => '0003',
                'percentage' => 0.00,
                'name' => '0,00 %',
                'description' => 'Exento - Productos y servicios no gravados por IVA'
            ],
            [
                'code' => '0004',
                'percentage' => 10.50,
                'name' => '10,50 %',
                'description' => 'Productos de la canasta básica (pan, leche, frutas, verduras, etc.)'
            ],
            [
                'code' => '0005',
                'percentage' => 21.00,
                'name' => '21,00 %',
                'description' => 'Alícuota general - Mayoría de productos y servicios'
            ],
            [
                'code' => '0006',
                'percentage' => 27.00,
                'name' => '27,00 %',
                'description' => 'Servicios de telecomunicaciones, electricidad y gas'
            ],
            [
                'code' => '0008',
                'percentage' => 5.00,
                'name' => '5,00 %',
                'description' => 'Bienes de capital, informática y telecomunicaciones'
            ],
            [
                'code' => '0009',
                'percentage' => 2.50,
                'name' => '2,50 %',
                'description' => 'Alícuota reducida - Productos específicos según normativa'
            ],
        ];

        foreach ($ivaRates as $rate) {
            Tax::create([
                'tax_type_id' => $ivaTaxType->id,
                'name' => "IVA {$rate['name']}",
                'tax_code' => $rate['code'],
                'porcentaje' => $rate['percentage'],
                'active' => true,
                'description' => $rate['description'],
            ]);
        }

        $this->command->info('IVA tax rates seeded successfully!');
    }
}
