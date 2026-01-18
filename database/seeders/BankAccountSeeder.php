<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'name' => 'Banco Central - Cuenta Corriente',
                'account_holder' => 'Empresa ERP Solutions S.A.',
                'account_number' => '1234567890123456',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Banco Nacional - Cuenta de Ahorros',
                'account_holder' => 'Empresa ERP Solutions S.A.',
                'account_number' => '9876543210987654',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Banco Internacional - Cuenta USD',
                'account_holder' => 'Empresa ERP Solutions S.A.',
                'account_number' => '5555666677778888',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Banco Popular - Nómina',
                'account_holder' => 'Empresa ERP Solutions S.A.',
                'account_number' => '1111222233334444',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('bank_accounts')->insert($bankAccounts);
    }
}
