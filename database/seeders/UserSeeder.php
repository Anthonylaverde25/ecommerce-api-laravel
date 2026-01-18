<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles
        $superadminRole = \App\Models\Role::where('name', 'superadmin')->first();
        $adminRole = \App\Models\Role::where('name', 'admin')->first();
        $userRole = \App\Models\Role::where('name', 'user')->first();

        // Crear usuario superadmin
        $superadmin = \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@erp.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $superadmin->roles()->sync([$superadminRole->id]);

        // Crear usuarios administradores
        $admin1 = \App\Models\User::updateOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'name' => 'Admin Usuario',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $admin1->roles()->sync([$adminRole->id]);

        $admin2 = \App\Models\User::updateOrCreate(
            ['email' => 'maria.admin@erp.com'],
            [
                'name' => 'Maria Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $admin2->roles()->sync([$adminRole->id]);

        // Crear usuarios regulares
        $user1 = \App\Models\User::updateOrCreate(
            ['email' => 'juan.perez@erp.com'],
            [
                'name' => 'Juan Pérez',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $user1->roles()->sync([$userRole->id]);

        $user2 = \App\Models\User::updateOrCreate(
            ['email' => 'ana.garcia@erp.com'],
            [
                'name' => 'Ana García',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $user2->roles()->sync([$userRole->id]);

        $user3 = \App\Models\User::updateOrCreate(
            ['email' => 'carlos.rodriguez@erp.com'],
            [
                'name' => 'Carlos Rodríguez',
                'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $user3->roles()->sync([$userRole->id]);
    }
}
