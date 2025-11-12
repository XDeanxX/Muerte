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
     * Agrega valores por defecto al campo 'asistencia' en las tablas pivot
     * concejales_reuniones e instituciones_reuniones
     */
    public function up(): void
    {
        // Actualizar registros existentes en concejales_reuniones
        DB::table('concejales_reuniones')
            ->whereNull('asistencia')
            ->update(['asistencia' => false]);
        
        // Actualizar registros existentes en instituciones_reuniones
        DB::table('instituciones_reuniones')
            ->whereNull('asistencia')
            ->update(['asistencia' => false]);
        
        // Modificar columnas para agregar el valor por defecto
        Schema::table('concejales_reuniones', function (Blueprint $table) {
            $table->boolean('asistencia')->default(false)->change();
        });
        
        Schema::table('instituciones_reuniones', function (Blueprint $table) {
            $table->boolean('asistencia')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concejales_reuniones', function (Blueprint $table) {
            $table->boolean('asistencia')->default(null)->change();
        });
        
        Schema::table('instituciones_reuniones', function (Blueprint $table) {
            $table->boolean('asistencia')->default(null)->change();
        });
    }
};
