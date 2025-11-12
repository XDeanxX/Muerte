<?php

namespace Database\Seeders;

use App\Models\nacionalidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NacionalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            nacionalidad::updateOrCreate(
            ['nacionalidad' => 'V',]
        );   

            nacionalidad::updateOrCreate(
            ['nacionalidad' => 'E',]
        );    
    }

        
}
