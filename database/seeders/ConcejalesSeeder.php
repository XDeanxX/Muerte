<?php

namespace Database\Seeders;

use App\Models\Concejales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConcejalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $concejales = [
            [
                'persona_cedula' => 12345678,
                'cargo_concejal' => 'comer gente'
            ],
            [
                'persona_cedula' => 87654321,
                'cargo_concejal' => 'deborar'
            ]
        ];

        foreach ($concejales as $concejale) {
            Concejales::updateOrCreate(
                ['persona_cedula' => $concejale['persona_cedula']],
                $concejale
            );
        }
    }
}
