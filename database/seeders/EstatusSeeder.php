<?php

namespace Database\Seeders;

use App\Models\Estatus;
use App\Models\SectoresSistema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estatus = [
            [
                'estatus' => 'pendiente',
                'descripcion' => 'La solicitud se encuentra en espera de una respuesta o una acción',
                'sector_sistema' => 'solicitudes'
            ],[
                'estatus' => 'aprobada',
                'descripcion' => 'La petición de la solicitud fue aprobada por el consejo municipal',
                'sector_sistema' => 'solicitudes'
            ],[
                'estatus' => 'rechazada',
                'descripcion' => 'La petición de la solicitud fue rechazada por el consejo municipal',
                'sector_sistema' => 'solicitudes'
            ],[
                'estatus' => 'creacion',
                'descripcion' => 'La visita esta a la espera de ser creada',
                'sector_sistema' => 'visitas'
            ],[
                'estatus' => 'espera',
                'descripcion' => 'La visita esta a la espera de la fecha y resolucion del visitador asignado',
                'sector_sistema' => 'visitas'
            ],[
                'estatus' => 'terminada',
                'descripcion' => 'El visitador asignado concluyo la visita y dio su resolución',
                'sector_sistema' => 'visitas'
            ],[
                'estatus' => 'cancelada',
                'descripcion' => 'La visita fue cancelada por algun motivo',
                'sector_sistema' => 'visitas'
            ],[
                'estatus' => 'espera',
                'descripcion' => 'La reunión se encuentra en espera para iniciar',
                'sector_sistema' => 'reuniones'
            ],[
                'estatus' => 'en curso',
                'descripcion' => 'La reunión esta en sesión',
                'sector_sistema' => 'reuniones'
            ],[
                'estatus' => 'concluir',
                'descripcion' => 'La reunión se encuentra en espera para finalizar',
                'sector_sistema' => 'reuniones'
            ],[
                'estatus' => 'finalizado',
                'descripcion' => 'La reunión concluyo',
                'sector_sistema' => 'reuniones'
            ],[
                'estatus' => 'cancelada',
                'descripcion' => 'La reunión fue cancelada por algun motivo',
                'sector_sistema' => 'reuniones'
            ],
        ];

        foreach ($estatus as $estatu) {
            Estatus::updateOrCreate(
                ['estatus' => $estatu['estatus']],
                $estatu
            );
        }
    }
}
