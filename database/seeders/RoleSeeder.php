<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    
    public function run(): void
    {
        Role::updateOrCreate(
            ['role' => 1],
            [
                'name' => 'SuperAdministrador',
                'description' => 'Acceso completo a todas las funcionalidades del sistema'
            ]
        );

        Role::updateOrCreate(
            ['role' => 2],
            [
                'name' => 'Administrador',
                'description' => 'Acceso de solo lectura a solicitudes y visitas pendientes'
            ]
        );

        Role::updateOrCreate(
            ['role' => 3],
            [
                'name' => 'Usuario',
                'description' => 'Usuario básico con acceso limitado'
            ]
        );
    }
}