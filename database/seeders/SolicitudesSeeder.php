<?php

namespace Database\Seeders;

use App\Models\Solicitud;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SolicitudesSeeder extends Seeder
{

    public function run(): void
    {
        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Solicitud de agua potable',
                'descripcion' => 'Necesitamos mejorar el suministro de agua en nuestra comunidad.',
                'estatus' => 1,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'aguas blancas',
                'tipo_solicitud' => 'individual',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'VICENTE LAMBRUSCHINI',
                'direccion_detallada' => 'Calle Falsa 124',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Suministros para la escuela primaria de chivacoa',
                'descripcion' => 'necesiamos suministros alimenticios y medicos para la escuela',
                'estatus' => 1,
                'persona_cedula' => 1234567,
                'derecho_palabra' => true,
                'subcategoria' => 'educación básica',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'ALTO DEL RIO',
                'direccion_detallada' => 'Calle Falsa 12',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Reparacion de cancha deportiva',
                'descripcion' => 'Peticion para presupuesto para cancha deportivo en la comunidad',
                'estatus' => 3,
                'persona_cedula' => 29970399,
                'derecho_palabra' => true,
                'subcategoria' => 'deporte y recreación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'BARRIO NUEVO',
                'direccion_detallada' => 'Calle Falsa 123',
                'fecha_creacion' => now(),
            ]
        );

        Solicitud::updateOrCreate(
            ['solicitud_id' => now()->format('Ymd') . substr(md5(uniqid(rand(), true)), 0, 6)],
            [
                'titulo' => 'Reparación de cable telefonico ',
                'descripcion' => 'en nuestra comunidad se callo un cable telefonico y son 10 casas afectadas',
                'estatus' => 2,
                'persona_cedula' => 29970399,
                'derecho_palabra' => false,
                'subcategoria' => 'telecomunicación',
                'tipo_solicitud' => 'colectivo_institucional',
                'pais' => 'Venezuela',
                'estado_region' => 'Yaracuy',
                'municipio' => 'Bruzual',
                'comunidad' => 'GUATANQUIRE',
                'direccion_detallada' => 'Calle Falsa 8',
                'fecha_creacion' => now(),
            ]
        );
    }
}
