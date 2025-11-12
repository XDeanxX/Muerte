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
        Schema::create('solicitudes', function (Blueprint $table) {

            $table->string('solicitud_id')->primary(); // Custom ID: YYYYMMDD + hash
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('estatus');
            $table->text('observaciones_admin')->nullable();

            $table->timestamp('fecha_actualizacion_usuario')->nullable();
            $table->timestamp('fecha_actualizacion_super_admin')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->softDeletes();

            $table->unsignedBigInteger('persona_cedula');
            $table->boolean('derecho_palabra')->nullable()->default(false);
            $table->string('subcategoria');
            $table->enum('tipo_solicitud', ['individual', 'colectivo_institucional'])->default('individual');
                
            $table->string('pais')->default('Venezuela');
            $table->string('estado_region')->default('Yaracuy');
            $table->string('municipio')->default('Bruzual');
            $table->string('comunidad');
            $table->text('direccion_detallada');

            $table->boolean('asignada_visita')->nullable();
            $table->boolean('asignada_reunion')->default(false)->nullable();
            $table->unsignedBigInteger('reunion_id_asignacion_visita')->nullable();
            
            $table->foreign('reunion_id_asignacion_visita')->references('id')->on('reuniones')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('persona_cedula')->references('cedula')->on('personas')->onUpdate('cascade');
            $table->foreign('estatus')->references('estatus_id')->on('estatus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subcategoria')->references('subcategoria')->on('sub_categorias')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('comunidad')->references('comunidad')->on('comunidades')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};