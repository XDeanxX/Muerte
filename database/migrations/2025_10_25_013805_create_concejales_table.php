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
        Schema::create('concejales', function (Blueprint $table) {
            $table->id();

            // 💡 CORRECCIÓN: Cambiar ->index() a ->unique() para permitir la FK.
            $table->unsignedBigInteger('persona_cedula')->unique();

            $table->string('cargo_concejal');

            $table->foreign('persona_cedula')
                ->references('cedula')
                ->on('personas')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concejales');
    }
};
