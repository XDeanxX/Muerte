<?php

namespace Database\Seeders;

use App\Models\SectoresSistema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectorSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectores = [
            [
                'sector' => 'visitas'
            ],[
                'sector' => 'solicitudes'
            ],
            [
                'sector' => 'reuniones'
            ]
        ];
        
        foreach($sectores as $sector){
            SectoresSistema::updateOrCreate(
                ['sector' => $sector['sector']],
                $sector
            );
        }
    }
}
