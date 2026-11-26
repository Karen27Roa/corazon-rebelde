<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Guarda el horario exacto que se le ofreció a la clienta cuando se liberó un cupo,
 * para que el enlace del WhatsApp sea corto: /cupo/{id}
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('lista_espera', 'cupo_fecha')) {
            return;
        }

        Schema::table('lista_espera', function (Blueprint $table) {
            $table->foreignId('cupo_especialista_id')->nullable()->constrained('especialistas')->nullOnDelete();
            $table->date('cupo_fecha')->nullable();
            $table->time('cupo_hora')->nullable();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('lista_espera', 'cupo_fecha')) {
            Schema::table('lista_espera', function (Blueprint $table) {
                $table->dropConstrainedForeignId('cupo_especialista_id');
                $table->dropColumn(['cupo_fecha', 'cupo_hora']);
            });
        }
    }
};
