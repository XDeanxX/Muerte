<?php

namespace Database\Seeders;

use App\Models\TipoReunion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoReunionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            [
                'titulo' => 'asamblea',
                'descripcion' => 'Reuniones para atender solictiudes, derechos de palabra y tomar desiciones.'
            ],[
                'titulo' => 'mesa de trabajo',
                'descripcion' => 'Reuniones para tomar planificar trabajos y decisiones internas del concejo municipal.'
            ],[
                'titulo' => 'sesión solemne',
                'descripcion' => 'Reuniones para conmemoración, evento o celebración de alguna fecha.'
            ],
        ];

        
        foreach ($tipos as $tipo) {
            TipoReunion::updateOrCreate(
                ['titulo' => $tipo['titulo']],
                $tipo
            );
        }
    }
}
