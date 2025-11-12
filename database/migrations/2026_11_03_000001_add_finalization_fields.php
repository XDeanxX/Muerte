<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // NOTA: asignada_visita ya existe en solicitudes, no la agregamos

        // Agregar campo duracion_real a tabla reuniones
        Schema::table('reuniones', function (Blueprint $table) {
            $table->time('duracion_real')->nullable()->after('duracion_reunion');
        });

        // Agregar campo estatus_decision a solicitudes_reuniones
        Schema::table('solicitudes_reuniones', function (Blueprint $table) {
            $table->enum('estatus_decision', ['aprobada', 'rechazada', 'pendiente'])->nullable()->after('asistencia_solicitante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // NO eliminamos asignada_visita porque ya existía antes

        Schema::table('reuniones', function (Blueprint $table) {
            $table->dropColumn('duracion_real');
        });

        Schema::table('solicitudes_reuniones', function (Blueprint $table) {
            $table->dropColumn('estatus_decision');
        });
    }
};
