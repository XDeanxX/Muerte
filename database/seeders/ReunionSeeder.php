<?php

namespace Database\Seeders;

use App\Models\Estatus;
use App\Models\Reunion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReunionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reunion::updateOrCreate(
            [
                'titulo' => 'derechos de humano',
                'descripcion' => 'para no flojear',
                'fecha_reunion' => '2025-12-27',
                'hora_reunion' => '03:04',
                'estatus' => Estatus::where('sector_sistema', 'reuniones')->where('estatus', 'espera')->value('estatus_id'),
                'tipo_reunion' => 1,
            ]
        );
    }
}
