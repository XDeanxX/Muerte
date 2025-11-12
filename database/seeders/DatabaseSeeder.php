<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            SectorSistemaSeeder::class,
            EstatusSeeder::class,
            CategoriasSeeder::class,
            SubCategoriasSeeder::class,
            SecurityQuestionSeeder::class,
            RoleSeeder::class,
            InstitucionSeeder::class,
            GeneroSeeder::class,
            NacionalidadSeeder::class,
            UserSeeder::class,
            ConcejalesSeeder::class,
            ParroquiaSeeder::class,
            ComunidadesSeeder::class,
            SolicitudesSeeder::class,
            TipoReunionSeeder::class,
            ReunionSeeder::class,

        ]);
    }
}

