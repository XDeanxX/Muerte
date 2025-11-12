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
        Schema::create('solicitudes_reuniones', function (Blueprint $table) {
            $table->id();
            $table->string('solicitud_id');
            $table->unsignedBigInteger('reunion_id');
            $table->boolean('citar_solicitante')->default(false);
            $table->boolean('asistencia_solicitante')->default(false);

            $table->foreign('solicitud_id')->references('solicitud_id')->on('solicitudes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('reunion_id')->references('id')->on('reuniones')->onUpdate('cascade')->onDelete('cascade');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_reuniones');
    }
};
