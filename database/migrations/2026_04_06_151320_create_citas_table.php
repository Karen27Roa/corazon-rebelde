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
    Schema::create('citas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('especialista_id');
        $table->unsignedBigInteger('servicio_id');
        $table->date('fecha');
        $table->time('hora');
        $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
        $table->text('notas')->nullable();
        $table->timestamps();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('especialista_id')->references('id')->on('especialistas')->onDelete('cascade');
        $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
