<?php

namespace App\Services;

use App\Mail\CitaAgendadaMail;
use App\Models\Cita;
use App\Models\ListaEspera;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CitaNotificador
{
    public function __construct(
        private WhatsAppService $whatsapp,
        private GoogleCalendarService $calendar,
    ) {}

    public function citaAgendada(Cita $cita, bool $reagendada = false): void
    {
        $cita->loadMissing(['user', 'servicio', 'especialista.user']);

        // 1. Correos (clienta y estilista)
        try {
            Mail::to($cita->user->email)->send(new CitaAgendadaMail($cita, true));
            Mail::to($cita->especialista->user->email)->send(new CitaAgendadaMail($cita, false));
        } catch (\Throwable $e) {
            Log::error('Correo cita: ' . $e->getMessage());
        }

        // 2. Google Calendar de la estilista
        $this->calendar->crearEvento($cita);

        // 3. WhatsApp
        $fecha = $cita->inicio->format('d/m/Y');
        $hora  = $cita->inicio->format('h:i A');

        $tituloClienta = $reagendada ? 'Tu cita fue reagendada 🔄' : 'Tu cita quedó agendada ✅';
        $tituloEstilista = $reagendada ? 'Una cita fue reagendada 🔄' : 'Tienes una nueva cita ✅';

        // A la clienta
        $this->enviarWhatsApp(
            $cita->user->telefono,
            "Hola {$cita->user->name} 💖\n"
            . "{$tituloClienta}\n\n"
            . "Servicio: {$cita->servicio->nombre}\n"
            . "Especialista: {$cita->especialista->user->name}\n"
            . "Fecha: {$fecha}\n"
            . "Hora: {$hora}\n\n"
            . "¡Te esperamos en Corazón Rebelde!"
        );

        // A la estilista
        $this->enviarWhatsApp(
            $this->telefonoEspecialista($cita),
            "Hola {$cita->especialista->user->name} 💇‍♀️\n"
            . "{$tituloEstilista}\n\n"
            . "Clienta: {$cita->user->name}\n"
            . "Servicio: {$cita->servicio->nombre}\n"
            . "Fecha: {$fecha}\n"
            . "Hora: {$hora}"
        );

       // Al negocio
        $this->enviarWhatsApp(
          config('services.whatsapp.negocio'),
         "Nueva cita de {$cita->user->name} con {$cita->especialista->user->name}\n"
        . "{$cita->servicio->nombre} - {$fecha} a las {$hora}"
);  
    }

    public function citaCancelada(Cita $cita): void
    {
        $cita->loadMissing(['user', 'servicio', 'especialista.user']);

        $this->calendar->eliminarEvento($cita);

        $fecha = $cita->inicio->format('d/m/Y');
        $hora  = $cita->inicio->format('h:i A');

        // A la estilista
        $this->enviarWhatsApp(
            $this->telefonoEspecialista($cita),
            "Hola {$cita->especialista->user->name}\n"
            . "Se canceló una cita ❌\n\n"
            . "Clienta: {$cita->user->name}\n"
            . "Servicio: {$cita->servicio->nombre}\n"
            . "Fecha: {$fecha}\n"
            . "Hora: {$hora}"
        );

        // A la clienta
        $this->enviarWhatsApp(
            $cita->user->telefono,
            "Hola {$cita->user->name} 💖\n"
            . "Tu cita fue cancelada ❌\n\n"
            . "Servicio: {$cita->servicio->nombre}\n"
            . "Fecha: {$fecha}\n"
            . "Hora: {$hora}\n\n"
            . "Cuando quieras, agenda una nueva cita en nuestra página."
        );
    }

    /**
     * Recordatorio a la clienta el día antes de su cita.
     * Devuelve true solo si el WhatsApp salió, para poder reintentarlo después.
     */
    public function recordatorio(Cita $cita): bool
    {
        $cita->loadMissing(['user', 'servicio', 'especialista.user']);

        $cuando = $cita->inicio->isToday() ? 'hoy' : 'mañana';
        $hora   = $cita->inicio->format('h:i A');

        return $this->whatsapp->enviar(
            $cita->user->telefono,
            "Hola {$cita->user->name} 💜\n"
            . "Te recordamos tu cita de {$cuando} ⏰\n\n"
            . "Servicio: {$cita->servicio->nombre}\n"
            . "Especialista: {$cita->especialista->user->name}\n"
            . "Fecha: {$cita->inicio->format('d/m/Y')}\n"
            . "Hora: {$hora}\n\n"
            . "Si no puedes asistir, cancela o reagenda desde la página para que otra clienta pueda usar el horario 🙏\n"
            . route('citas.mias')
        );
    }

    /**
     * Aviso a una clienta de la lista de espera: se liberó un horario que le sirve.
     */
    public function horarioDisponible(ListaEspera $solicitud, Cita $citaLiberada, string $enlace): bool
    {
        $solicitud->loadMissing(['user', 'servicio']);
        $citaLiberada->loadMissing('especialista.user');

        return $this->whatsapp->enviar(
            $solicitud->telefono ?: $solicitud->user->telefono,
            "Hola {$solicitud->user->name} 💜\n"
            . "¡Se liberó un horario que estabas esperando! 🎉\n\n"
            . "Servicio: {$solicitud->servicio->nombre}\n"
            . "Especialista: {$citaLiberada->especialista->user->name}\n"
            . "Fecha: {$citaLiberada->inicio->format('d/m/Y')}\n"
            . "Hora: {$citaLiberada->inicio->format('h:i A')}\n\n"
            . "Agéndalo antes de que otra clienta lo tome 👇\n"
            . "👉 {$enlace}"
        );
    }

    /**
     * Teléfono de la estilista: primero el de su usuario,
     * y si está vacío, el de la tabla especialistas.
     */
    private function telefonoEspecialista(Cita $cita): ?string
    {
        return $cita->especialista->user->telefono
            ?: $cita->especialista->telefono;
    }

    /**
     * Envía un WhatsApp sin romper el flujo si falta el teléfono
     * o si el servicio Node está apagado.
     */
    private function enviarWhatsApp(?string $telefono, string $mensaje): void
    {
        if (empty($telefono)) {
            Log::warning('WhatsApp omitido: no hay teléfono.');
            return;
        }

        try {
            $this->whatsapp->enviar($telefono, $mensaje);
        } catch (\Throwable $e) {
            Log::error('WhatsApp cita: ' . $e->getMessage());
        }
    }
}