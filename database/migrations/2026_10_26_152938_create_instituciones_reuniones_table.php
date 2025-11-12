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
        Schema::create('instituciones_reuniones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institucion_id');
            $table->unsignedBigInteger('reunion_id');
            $table->boolean('asistencia')->default(false);

            $table->foreign('institucion_id')->references('id')->on('instituciones')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('instituciones_reuniones');
    }
};
