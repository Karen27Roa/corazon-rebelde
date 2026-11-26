<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lista de espera: clientas que quieren un horario que está lleno.
 * Cuando una cita se cancela o se reagenda, el sistema les avisa por WhatsApp.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lista_espera')) {
            return;
        }

        Schema::create('lista_espera', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
            // null = "cualquier especialista"
            $table->foreignId('especialista_id')->nullable()->constrained('especialistas')->cascadeOnDelete();
            $table->date('fecha');
            // null = "cualquier hora de ese día"
            $table->time('hora')->nullable();
            $table->string('telefono')->nullable();
            // esperando → notificada → agendada | cancelada | vencida
            $table->string('estado')->default('esperando');
            $table->timestamp('notificada_at')->nullable();
            $table->timestamps();

            $table->index(['fecha', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_espera');
    }
};
