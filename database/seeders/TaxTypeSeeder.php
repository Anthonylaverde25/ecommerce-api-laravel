<?php

namespace Database\Seeders;

use App\Models\TaxType;
use Illuminate\Database\Seeder;

class TaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxType::updateOrCreate(
            ['code' => 'IVA'],
            [
                'name' => 'IVA',
                'active' => true,
            ]
        );

        TaxType::updateOrCreate(
            ['code' => 'RET'],
            [
                'name' => 'Retención',
                'active' => true,
            ]
        );
    }
}
