<?php

namespace Database\Seeders;

use App\Models\Categorias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'categoria' => 'social',
                'descripcion' => 'Para subcategorías relacionadas con el ambito social'
            ],[
                'categoria' => 'servicio publico',
                'descripcion' => 'Para subcategorías relacionadas con los servicos públicos'
            ],[
                'categoria' => 'infraestructura',
                'descripcion' => 'Para subcategorías relacionadas con edificaciones'
            ],[
                'categoria' => 'ambiental',
                'descripcion' => 'Para subcategorías relacionadas con el cuidado ambiental'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categorias::updateOrCreate(
                ['categoria' => $categoria['categoria']],
                $categoria
            );
        }
    }
}
