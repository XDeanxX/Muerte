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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('persona_cedula');
            $table->string('tipo')->default('reunion'); // reunion, solicitud, etc
            $table->string('titulo');
            $table->text('mensaje');
            $table->unsignedBigInteger('reunion_id')->nullable();
            $table->string('solicitud_id')->nullable();
            $table->boolean('leida')->default(false);
            $table->timestamp('fecha_leida')->nullable();
            $table->timestamps();

            // Índices para mejorar el rendimiento
            $table->index('persona_cedula');
            $table->index('reunion_id');
            $table->index('solicitud_id');
            $table->index('leida');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
