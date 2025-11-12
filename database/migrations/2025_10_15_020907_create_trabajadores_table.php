<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Redefine la tabla trabajadores como tabla de enlace (1:1 con personas).
     */
    public function up(): void
    {
        // 1. ELIMINAR LA ESTRUCTURA ANTIGUA
        Schema::dropIfExists('trabajadores'); 

        // 2. CREAR LA NUEVA TABLA DE ENLACE
        Schema::create('trabajadores', function (Blueprint $table) {
            
            // --- CLAVE PRINCIPAL Y FORÁNEA a 'personas' (Diseño 1:1) ---
            // 'persona_cedula' es la PK de esta tabla y enlaza a 'personas.cedula'.
            // Usamos unsignedBigInteger para coincidir con la PK de personas
            $table->unsignedBigInteger('persona_cedula')->primary(); 
            
            // --- ATRIBUTOS LABORALES ---
            $table->string('zona_trabajo')->nullable(); 

            // --- OTRAS CLAVES FORÁNEAS ---
            // FK a 'cargos' (1:M)
            $table->unsignedBigInteger('cargo_id');
            
            $table->softDeletes();
            $table->timestamps();
            
            // --- ASIGNACIÓN DE CLAVES FORÁNEAS ---

            // 1. FK a la tabla 'personas'
            $table->foreign('persona_cedula')
                    ->references('cedula')
                    ->on('personas')
                    ->onDelete('cascade'); // Si la persona se borra, el trabajador se borra (1:1)

            // 2. FK a la tabla 'cargos' (¡CORRECCIÓN APLICADA!)
            $table->foreign('cargo_id')
                    ->references('cargo_id') // <--- ¡Esto corrige el error #1005!
                    ->on('cargos')
                    ->onDelete('restrict') 
                    ->onUpdate('cascade'); // Añadido onUpdate('cascade') por buenas prácticas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};