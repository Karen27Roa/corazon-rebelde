<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class CitaAgendadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Cita $cita,
        public bool $esCliente = true
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->esCliente
            ? '💜 ¡Tu cita está confirmada! - ' . $this->cita->servicio->nombre
            : '📅 Nueva cita agendada - ' . $this->cita->servicio->nombre;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        // Enlace firmado para cancelar desde el correo (vence cuando empieza la cita)
        $cancelUrl = URL::temporarySignedRoute(
            'citas.cancelar.firmada',
            $this->cita->inicio,
            ['cita' => $this->cita->id]
        );

        return new Content(
            view: 'emails.cita-agendada',
            with: [
                'cita'      => $this->cita,
                'esCliente' => $this->esCliente,
                'cancelUrl' => $cancelUrl,
            ],
        );
    }

    public function attachments(): array
    {
        $cita     = $this->cita;
        $inicio   = $cita->inicio;
        $fin      = $inicio->copy()->addHour();
        $uid      = 'cita-' . $cita->id . '@corazonrebelde.com';
        $cliente  = $cita->user->name;
        $servicio = $cita->servicio->nombre;
        $notas    = $cita->notas ?? 'Sin notas';

        $ics = "BEGIN:VCALENDAR\r\n"
             . "VERSION:2.0\r\n"
             . "PRODID:-//Corazon Rebelde//ES\r\n"
             . "METHOD:PUBLISH\r\n"
             . "BEGIN:VEVENT\r\n"
             . "UID:{$uid}\r\n"
             . "DTSTAMP:" . now()->utc()->format('Ymd\THis\Z') . "\r\n"
             . "DTSTART;TZID=America/Bogota:" . $inicio->format('Ymd\THis') . "\r\n"
             . "DTEND;TZID=America/Bogota:"   . $fin->format('Ymd\THis')    . "\r\n"
             . "SUMMARY:💅 {$servicio} - {$cliente}\r\n"
             . "DESCRIPTION:Cliente: {$cliente}\\nServicio: {$servicio}\\nNotas: {$notas}\r\n"
             . "LOCATION:Corazón Rebelde Nail Spa\r\n"
             . "STATUS:CONFIRMED\r\n"
             . "END:VEVENT\r\n"
             . "END:VCALENDAR\r\n";

        return [
            Attachment::fromData(fn () => $ics, 'cita.ics')
                ->withMime('text/calendar'),
        ];
    }
}