<?php

namespace App\Services;

use App\Models\Cita;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Log;

class GoogleCalendarService
{
    protected Calendar $calendar;
    protected string $calendarId;

    public function __construct()
    {
        $client = new Client();
        $client->setApplicationName('Corazón Rebelde');
        $client->setAuthConfig(base_path(config('services.google_calendar.credentials')));
        $client->setScopes([Calendar::CALENDAR]);

        $this->calendar = new Calendar($client);
        $this->calendarId = config('services.google_calendar.calendar_id');
    }

    /**
     * Crea el evento en Google Calendar y guarda su ID en la cita.
     * Nunca lanza excepciones: si falla, solo lo registra en el log.
     */
    public function crearEvento(Cita $cita): ?string
    {
        try {
            $cita->loadMissing(['user', 'servicio', 'especialista.user']);

            $inicio = $cita->inicio;
            $minutos = $cita->servicio->duracion_minutos
                ?? $cita->servicio->duracion
                ?? 60;
            $fin = $inicio->copy()->addMinutes((int) $minutos);

            $descripcion = "Clienta: {$cita->user->name}\n"
                . "Teléfono: {$cita->user->telefono}\n"
                . "Servicio: {$cita->servicio->nombre}\n"
                . "Especialista: {$cita->especialista->user->name}"
                . ($cita->notas ? "\nNotas: {$cita->notas}" : '');

            $evento = new Event([
                'summary'     => "{$cita->servicio->nombre} - {$cita->user->name} ({$cita->especialista->user->name})",
                'description' => $descripcion,
                'start' => [
                    'dateTime' => $inicio->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'America/Bogota',
                ],
                'end' => [
                    'dateTime' => $fin->format('Y-m-d\TH:i:s'),
                    'timeZone' => 'America/Bogota',
                ],
            ]);

            $eventId = $this->calendar->events->insert($this->calendarId, $evento)->getId();

            $cita->update(['google_event_id' => $eventId]);

            return $eventId;
        } catch (\Throwable $e) {
            Log::error('Google Calendar (crear): ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina el evento de la cita (si tiene) y limpia el google_event_id.
     */
    public function eliminarEvento(Cita $cita): void
    {
        if (! $cita->google_event_id) {
            return;
        }

        try {
            $this->calendar->events->delete($this->calendarId, $cita->google_event_id);
        } catch (\Throwable $e) {
            // Si el evento ya no existe (404/410) o falla, solo se registra
            Log::error('Google Calendar (eliminar): ' . $e->getMessage());
        }

        $cita->update(['google_event_id' => null]);
    }
}