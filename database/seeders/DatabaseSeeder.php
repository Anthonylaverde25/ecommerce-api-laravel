<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Ejecutar seeders
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            DepartmentSeeder::class,
            BankAccountSeeder::class,
            CategorySeeder::class,
            ItemSeeder::class,
            WarehouseSeeder::class,
            TaxTypeSeeder::class,
            TaxSeeder::class,
            FamilySeeder::class,
            ItemTaxSeeder::class,
        ]);
    }
}
