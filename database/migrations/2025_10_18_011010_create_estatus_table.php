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
        Schema::create('estatus', function (Blueprint $table) {
            $table->unsignedBigInteger('estatus_id')->primary()->autoIncrement();
            $table->string('estatus');
            $table->text('descripcion')->nullable();
            $table->string('sector_sistema');
            
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('sector_sistema')
                ->references('sector')
                ->on('sectores_sistema')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus');
    }
};
