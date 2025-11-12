<?php

namespace Database\Seeders;

use App\Models\SubCategorias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategorias = [
            [
                'subcategoria' => 'educación inicial',
                'descripcion' => 'Relación con instituciones o espacios de educación inicial',
                'categoria' => 'social',
            ],[
                'subcategoria' => 'educación básica',
                'descripcion' => 'Relación con instituciones o espacios de educación básica',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'educación secundaria',
                'descripcion' => 'Relación con instituciones o espacios de secundaria',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'educación universitaria',
                'descripcion' => 'Relación con instituciones o espacios universitarios',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'seguridad ciudadana',
                'descripcion' => 'Relacionado a la atención, seguridad, cuidado y protección del ciudadano',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'emergencia médica',
                'descripcion' => 'Relacionado a suministros medicos o atención médica',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'alimentación',
                'descripcion' => 'Relacionado a la atención alimenticia del ciudadano o comunidad',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'maltrato animal',
                'descripcion' => 'Relaciodado a casos de maltrato animal',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'deporte y recreación',
                'descripcion' => 'Relaciodado a casos deportivos y recreativos',
                'categoria' => 'social'
            ],[
                'subcategoria' => 'aguas blancas',
                'descripcion' => 'Relaciodado a botes, fugas o problemas de aguas limpias o potable',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'aguas negras',
                'descripcion' => 'Relaciodado a botes, fugas o problemas de cloacas o aguas negras',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'electricidad',
                'descripcion' => 'Relaciodado a fallas electricas',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'telecomunicación',
                'descripcion' => 'Relacionado con problemas o circunstancias con medios de telecomunicación',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'gas comunal',
                'descripcion' => 'Relacionado a problemas de gas comunal o cilindro',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'gas por tuberia',
                'descripcion' => 'Relacionado a problemas con el gas a tuberia',
                'categoria' => 'servicio publico'
            ],[
                'subcategoria' => 'vivienda',
                'descripcion' => 'Relacionado a problemas o circunstancias con su vivienda',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'recidencial',
                'descripcion' => 'Relacionado a problemas o circunstancias con su recidencia',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'prestaciones',
                'descripcion' => 'Prestaciones de infraestructuras del Municipio u otros',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'reparación',
                'descripcion' => 'Relacionado con reparaciones de estructuras o edificios',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'construcción',
                'descripcion' => 'Relaciodado a la construncción de nuevas instalaciones',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'derrumbes',
                'descripcion' => 'Relacionado a edificos o viviendas que sufrieron algún tipo de derrumbe',
                'categoria' => 'infraestructura'
            ],[
                'subcategoria' => 'terremotos',
                'descripcion' => 'Relacionado a sucesos imprevistos con terremotos',
                'categoria' => 'ambiental'
            ],[
                'subcategoria' => 'huracanes',
                'descripcion' => 'Relacionado a sucesos imprevistos con huracanes',
                'categoria' => 'ambiental'
            ],[
                'subcategoria' => 'tormentas tropicales',
                'descripcion' => 'Relacionado a sucesos imprevistos con tormentas tropicales',
                'categoria' => 'ambiental'
            ],[
                'subcategoria' => 'incendios',
                'descripcion' => 'Relaciodado a sucesos imprevistos con insendios',
                'categoria' => 'ambiental'
            ]
        ];

        foreach ($subcategorias as $subcategoria) {
            SubCategorias::updateOrCreate(
                ['subcategoria' => $subcategoria['subcategoria']],
                $subcategoria
            );
        }
    }
}
