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
        Schema::create('visitas_visitas', function (Blueprint $table) {
            $table->string('solicitud_id')->primary();
            $table->date('fecha_inicial');
            $table->date('fecha_final');
            $table->unsignedBigInteger('estatus_id');
            $table->text('observacion')->nullable();
            $table->foreign('solicitud_id')->references('solicitud_id')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('estatus_id')->references('estatus_id')->on('estatus')->onUpdate('cascade')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitas_visitas');
    }
};
