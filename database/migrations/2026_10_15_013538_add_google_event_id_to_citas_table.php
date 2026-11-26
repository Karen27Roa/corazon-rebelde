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
        // La columna ya la puede haber creado la migración add_campos_notificaciones
        if (Schema::hasColumn('citas', 'google_event_id')) {
            return;
        }

        Schema::table('citas', function (Blueprint $table) {
            $table->string('google_event_id')->nullable()->after('notas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hace nada: la columna la elimina add_campos_notificaciones al revertirse.
    }
};