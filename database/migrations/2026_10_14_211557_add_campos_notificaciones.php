<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono')->nullable(); // con indicativo, ej: 573001234567
        });

        Schema::table('especialistas', function (Blueprint $table) {
            $table->string('google_calendar_id')->nullable();
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->string('google_event_id')->nullable();
            if (!Schema::hasColumn('citas', 'estado')) {
                $table->string('estado')->default('pendiente');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('telefono'));
        Schema::table('especialistas', fn (Blueprint $t) => $t->dropColumn('google_calendar_id'));
        Schema::table('citas', fn (Blueprint $t) => $t->dropColumn('google_event_id'));
    }
};