<?php

namespace App\Console\Commands;

use App\Models\Cita;
use App\Services\CitaNotificador;
use Illuminate\Console\Command;

/**
 * Envía por WhatsApp un recordatorio a las clientas que tienen cita en las próximas 24 horas.
 *
 * Se ejecuta solo cada 30 minutos con el Scheduler de Laravel (ver routes/console.php).
 * Para probarlo a mano:  php artisan citas:recordatorios
 */
class EnviarRecordatorios extends Command
{
    protected $signature = 'citas:recordatorios
                            {--horas=24 : Con cuántas horas de anticipación se recuerda la cita}';

    protected $description = 'Envía recordatorios por WhatsApp de las citas próximas';

    /**
     * Si la cita se agendó hace menos de este tiempo, no se recuerda:
     * la clienta acaba de recibir la confirmación.
     */
    private const MINUTOS_DESDE_AGENDADA = 120;

    public function handle(CitaNotificador $notificador): int
    {
        $ahora  = now();
        $limite = $ahora->copy()->addHours((int) $this->option('horas'));

        $citas = Cita::where('estado', 'pendiente')
            ->whereNull('recordatorio_enviado_at')
            ->whereDate('fecha', '>=', $ahora->toDateString())
            ->whereDate('fecha', '<=', $limite->toDateString())
            ->where('created_at', '<=', $ahora->copy()->subMinutes(self::MINUTOS_DESDE_AGENDADA))
            ->with(['user', 'servicio', 'especialista.user'])
            ->get()
            ->filter(fn (Cita $c) => $c->inicio->between($ahora, $limite));

        if ($citas->isEmpty()) {
            $this->info('No hay citas para recordar.');
            return self::SUCCESS;
        }

        $enviados = 0;

        foreach ($citas as $cita) {
            if ($notificador->recordatorio($cita)) {
                $cita->update(['recordatorio_enviado_at' => now()]);
                $enviados++;
                $this->line("✔ Recordatorio enviado a {$cita->user->name} ({$cita->inicio->format('d/m/Y h:i A')})");
            } else {
                // Si WhatsApp está apagado se vuelve a intentar en la próxima ejecución
                $this->warn("✘ No se pudo enviar a {$cita->user->name}. Se reintentará.");
            }
        }

        $this->info("Recordatorios enviados: {$enviados} de {$citas->count()}");

        return self::SUCCESS;
    }
}
