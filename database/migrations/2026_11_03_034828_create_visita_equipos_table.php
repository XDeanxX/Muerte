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
        Schema::create('visita_equipos', function (Blueprint $table) {
    
            $table->id(); 
    $table->string('solicitud_id'); 
    $table->unsignedBigInteger('cedula');
    $table->foreign('solicitud_id')
          ->references('solicitud_id')
          ->on('visitas_visitas') 
          ->onUpdate('cascade')
          ->onDelete('cascade');
    $table->foreign('cedula')
          ->references('cedula')
          ->on('personas')
          ->onUpdate('cascade')
          ->onDelete('cascade');
    $table->unique(['solicitud_id', 'cedula']); 
    $table->softDeletes();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_equipos');
    }
};
