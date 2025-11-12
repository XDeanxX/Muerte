<?php

namespace Database\Seeders;

use App\Models\Parroquias;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parroquias = [
            ['parroquia' => 'chivacoa'],
            ['parroquia' => 'campo elías']
        ];

        foreach($parroquias as $parroquia){
            Parroquias::updateOrCreate(
                ['parroquia' => $parroquia['parroquia']],
                $parroquia
            );
        }
    }
}
