<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Recursos Humanos',
                'code' => 'RRHH',
                'description' => 'Gestión de personal, nóminas y contratación',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ventas',
                'code' => 'VEN',
                'description' => 'Equipo comercial y gestión de ventas',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Marketing digital y comunicación',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Contabilidad',
                'code' => 'CONT',
                'description' => 'Gestión contable y financiera',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tecnología',
                'code' => 'TI',
                'description' => 'Sistemas, desarrollo y soporte técnico',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Operaciones',
                'code' => 'OPS',
                'description' => 'Logística y operaciones diarias',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Compras',
                'code' => 'COM',
                'description' => 'Adquisición de materiales y servicios',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administración',
                'code' => 'ADM',
                'description' => 'Gestión administrativa general',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Servicio al Cliente',
                'code' => 'SAC',
                'description' => 'Atención y soporte al cliente',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Calidad',
                'code' => 'CAL',
                'description' => 'Control de calidad y mejora continua',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('departments')->insert($departments);
    }
}
