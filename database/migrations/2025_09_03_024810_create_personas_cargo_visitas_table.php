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
        Schema::create('personas_cargo_visitas', function (Blueprint $table) {
            $table->unsignedBigInteger('cedula')->primary();
            $table->string('cargo');

            $table->foreign('cedula')->references('cedula')->on('personas')->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas_cargo_visitas');
    }
};
