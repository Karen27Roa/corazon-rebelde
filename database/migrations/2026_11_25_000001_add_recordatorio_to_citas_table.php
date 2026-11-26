<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Guarda cuándo se envió el recordatorio por WhatsApp de cada cita,
 * para no enviarlo dos veces.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('citas', 'recordatorio_enviado_at')) {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->timestamp('recordatorio_enviado_at')->nullable();
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('citas', 'recordatorio_enviado_at')) {
            Schema::table('citas', function (Blueprint $table) {
                $table->dropColumn('recordatorio_enviado_at');
            });
        }
    }
};
