<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Agrega valores por defecto a los campos citar_solicitante y asistencia_solicitante
     * en la tabla solicitudes_reuniones
     */
    public function up(): void
    {
        // Primero actualizar los registros existentes que tengan NULL
        DB::table('solicitudes_reuniones')
            ->whereNull('citar_solicitante')
            ->update(['citar_solicitante' => false]);
            
        DB::table('solicitudes_reuniones')
            ->whereNull('asistencia_solicitante')
            ->update(['asistencia_solicitante' => false]);
        
        // Ahora modificar las columnas para agregar el valor por defecto
        Schema::table('solicitudes_reuniones', function (Blueprint $table) {
            $table->boolean('citar_solicitante')->default(false)->change();
            $table->boolean('asistencia_solicitante')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitudes_reuniones', function (Blueprint $table) {
            $table->boolean('citar_solicitante')->default(null)->change();
            $table->boolean('asistencia_solicitante')->default(null)->change();
        });
    }
};
