<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'user'];

        foreach ($roles as $roleName) {
            \App\Models\Role::updateOrCreate(
                ['name' => $roleName],
                ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
            );
        }
    }
}
