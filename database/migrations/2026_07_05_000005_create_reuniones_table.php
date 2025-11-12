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
        Schema::create('reuniones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_reunion'); // Cambiado de timestamp a dateTime para soportar fechas más allá de 2038
            $table->time('hora_reunion');
            $table->string('duracion_reunion')->nullable();
            $table->unsignedBigInteger('estatus');
            $table->unsignedBigInteger('tipo_reunion')->nullable();
            $table->text('resolución')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('tipo_reunion')->references('id')->on('tipo_reunions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('estatus')->references('estatus_id')->on('estatus')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniones');
    }
};