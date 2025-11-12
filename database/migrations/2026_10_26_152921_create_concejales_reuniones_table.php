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
        Schema::create('concejales_reuniones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concejal_id');
            $table->unsignedBigInteger('reunion_id');
            $table->boolean('asistencia')->default(false);

            $table->foreign('concejal_id')->references('persona_cedula')->on('concejales')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('concejales_reuniones');
    }
};
