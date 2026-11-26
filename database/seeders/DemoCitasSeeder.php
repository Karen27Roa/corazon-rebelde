<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Especialista;
use App\Models\ListaEspera;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

/**
 * Datos de DEMOSTRACIÓN para que el dashboard muestre gráficas en la sustentación.
 *
 *   php artisan db:seed --class=DemoCitasSeeder
 *
 * Crea 8 clientas de prueba (demo-clienta-N@demo.com, sin celular para que no se envíen
 * WhatsApp reales) con alrededor de 100 citas repartidas en los últimos 6 meses y las próximas 2 semanas.
 *
 * Para borrarlos: elimina en phpMyAdmin los usuarios cuyo correo empieza por "demo-clienta"
 * (sus citas y solicitudes se borran solas).
 */
class DemoCitasSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(2026); // siempre genera los mismos datos

        $especialistas = Especialista::pluck('id')->all();
        $servicios     = Servicio::where('activo', true)->pluck('id')->all();

        if (! $especialistas || ! $servicios) {
            $this->command?->warn('Primero debe haber especialistas y servicios.');
            return;
        }

        $clientas = collect(range(1, 8))->map(fn ($i) => User::firstOrCreate(
            ['email' => "demo-clienta-{$i}@demo.com"],
            [
                'name'     => ['Valentina', 'Camila', 'Mariana', 'Daniela', 'Laura', 'Sara', 'Paula', 'Juliana'][$i - 1] . ' (demo)',
                'password' => 'Demo2026*',
                'role'     => 'cliente',
                'created_at' => now()->subMonths(mt_rand(0, 6)),
            ]
        ));

        // Horas más pedidas en la mañana y al final de la tarde; viernes y sábado más llenos
        $horas = ['09:00', '10:00', '10:00', '10:30', '11:00', '11:00', '14:00', '15:00', '15:30', '16:00', '16:00', '17:00', '17:30', '18:00', '19:00'];
        $ocupados = [];
        $creadas = 0;

        // Sin eventos: no dispara WhatsApp, correos ni la lista de espera
        Cita::withoutEvents(function () use ($clientas, $especialistas, $servicios, $horas, &$ocupados, &$creadas) {
            for ($i = 0; $i < 260 && $creadas < 120; $i++) {
                $fecha = today()->subDays(mt_rand(-14, 180));

                // más probabilidad para viernes (5) y sábado (6); domingo casi nada
                $peso = [1 => 5, 2 => 5, 3 => 6, 4 => 7, 5 => 10, 6 => 12, 7 => 1][$fecha->dayOfWeekIso];
                if (mt_rand(1, 12) > $peso) {
                    continue;
                }

                $especialista = $especialistas[mt_rand(0, count($especialistas) - 1)];
                $hora = $horas[mt_rand(0, count($horas) - 1)];
                $clave = "{$especialista}|{$fecha->toDateString()}|{$hora}";
                if (isset($ocupados[$clave])) {
                    continue;
                }
                $ocupados[$clave] = true;

                $inicio = Carbon::parse($fecha->toDateString() . ' ' . $hora);
                $azar = mt_rand(1, 100);
                $estado = $inicio->isFuture()
                    ? ($azar <= 90 ? 'pendiente' : 'cancelada')
                    : ($azar <= 80 ? 'completada' : 'cancelada');

                $cita = new Cita([
                    'user_id'         => $clientas[mt_rand(0, $clientas->count() - 1)]->id,
                    'servicio_id'     => $servicios[mt_rand(0, min(count($servicios), 14) - 1)],
                    'especialista_id' => $especialista,
                    'fecha'           => $fecha->toDateString(),
                    'hora'            => $hora,
                    'estado'          => $estado,
                    'notas'           => 'Cita de demostración',
                ]);
                $cita->created_at = $inicio->copy()->subDays(mt_rand(1, 10));
                // ya "recordadas" para que el comando de recordatorios no las tome
                $cita->recordatorio_enviado_at = $estado !== 'cancelada' ? $inicio->copy()->subDay() : null;
                $cita->save();
                $creadas++;
            }
        });

        // Algunas solicitudes de lista de espera (activas y recuperadas)
        foreach (range(1, 6) as $i) {
            $recuperada = $i <= 3;
            ListaEspera::create([
                'user_id'         => $clientas[$i]->id,
                'servicio_id'     => $servicios[$i % count($servicios)],
                'especialista_id' => $i % 2 ? $especialistas[0] : null,
                'fecha'           => $recuperada ? today()->subDays($i * 7) : today()->addDays($i),
                'hora'            => $i % 3 ? '16:00' : null,
                'estado'          => $recuperada ? ListaEspera::AGENDADA : ListaEspera::ESPERANDO,
                'notificada_at'   => $recuperada ? today()->subDays($i * 7 + 1)->setTime(10, 0) : null,
            ]);
        }

        $this->command?->info("Listo: {$creadas} citas de demostración y 6 solicitudes de lista de espera.");
    }
}
