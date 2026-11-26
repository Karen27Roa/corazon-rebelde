<?php

use App\Services\ListaEsperaService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Tareas automáticas (Scheduler)
|--------------------------------------------------------------------------
| En desarrollo se encienden con:   php artisan schedule:work
| En un servidor real se usa una tarea cron que ejecute cada minuto:
|   php artisan schedule:run
*/

// 📱 Recordatorio por WhatsApp de las citas de las próximas 24 horas
Schedule::command('citas:recordatorios')
    ->everyThirtyMinutes()
    ->withoutOverlapping();

// ⏳ Las solicitudes de lista de espera de días pasados quedan vencidas
Schedule::call(fn () => app(ListaEsperaService::class)->marcarVencidas())
    ->name('lista-espera:vencer')
    ->dailyAt('00:05');
