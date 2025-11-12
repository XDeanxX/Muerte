<?php

namespace Database\Seeders;

use App\Models\genero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        genero::updateOrCreate(
            ['genero' => 'Masculino',
            'description' => 'Personas autopercibidas del genero masculino'
            
            ]
        );

          genero::updateOrCreate(
            ['genero' => 'Femenino',
            'description' => 'Personas autopercibidas del genero femenino'
            ]
        );

        
          genero::updateOrCreate(
            ['genero' => 'Otro',
            'description' => 'Personas que abarcan un amplio espectro de generos'
            ]
        );
    }
}


