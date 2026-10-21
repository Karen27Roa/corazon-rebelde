<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Especialista;
use App\Mail\CitaAgendadaMail;
use App\Services\CitaNotificador;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function create()
    {
        $servicios = Servicio::where('activo', true)->get();
        $especialistas = Especialista::with('user')->get();

        return view('cliente.agendar', compact('servicios', 'especialistas'));
    }

    public function store(Request $request, CitaNotificador $notificador)
    {
        $request->validate([
            'servicio_id'     => 'required|exists:servicios,id',
            'especialista_id' => 'required|exists:especialistas,id',
            'fecha'           => 'required|date|after_or_equal:today',
            'hora'            => 'required',
            'notas'           => 'nullable|string|max:300',
        ]);

        // 🔒 Validar que no esté ocupada esa hora con esa especialista
        $citaExistente = Cita::where('especialista_id', $request->especialista_id)
            ->whereDate('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->whereNotIn('estado', ['cancelada'])
            ->exists();

        if ($citaExistente) {
            return back()
                ->withErrors([
                    'hora' => 'Esta especialista ya tiene una cita en esa hora.',
                ])
                ->withInput();
        }

        // 💾 Crear cita
        $cita = Cita::create([
            'user_id'         => auth()->id(),
            'servicio_id'     => $request->servicio_id,
            'especialista_id' => $request->especialista_id,
            'fecha'           => $request->fecha,
            'hora'            => $request->hora,
            'notas'           => $request->notas,
            'estado'          => 'pendiente',
        ]);

        $cita->load(['servicio', 'especialista.user', 'user']);

        // 🔔 Correo a clienta y estilista + Google Calendar + WhatsApp
        $notificador->citaAgendada($cita);

        // 📧 Copia para el correo del negocio
        try {
            Mail::to('corazonrebelde700@gmail.com')
                ->send(new CitaAgendadaMail($cita, esCliente: false));
        } catch (\Throwable $e) {
            Log::error('Correo negocio: ' . $e->getMessage());
        }

        return redirect()->route('citas.mias')->with('success', '¡Cita agendada con éxito! 💜');
    }

    // ✅ CAMBIADO: solo citas pendientes que aún no pasaron + datos del calendario
    public function misCitas()
    {
        $citas = Cita::where('user_id', auth()->id())
            ->where('estado', 'pendiente')
            ->with(['servicio', 'especialista.user'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get()
            // oculta las que ya pasaron (se mantienen hasta 1 hora después de iniciar)
            ->filter(fn ($c) => $c->inicio->copy()->addHour()->isFuture())
            ->values();

        $eventos = $citas->map(fn ($c) => [
            'fecha'     => $c->inicio->format('Y-m-d'),
            'hora'      => $c->inicio->format('h:i A'),
            'estilista' => $c->especialista->user->name,
            'servicio'  => $c->servicio->nombre,
        ])->values();

        return view('cliente.mis-citas', compact('citas', 'eventos'));
    }

    public function edit(Cita $cita)
    {
        if ($cita->user_id !== auth()->id()) {
            abort(403);
        }

        if ($cita->estado === 'cancelada') {
            return redirect()->route('citas.mias')->withErrors([
                'error' => 'No puedes reagendar una cita cancelada.'
            ]);
        }

        $servicios = Servicio::where('activo', true)->get();
        $especialistas = Especialista::with('user')->get();

        return view('cliente.reagendar', compact('cita', 'servicios', 'especialistas'));
    }

    public function update(Request $request, Cita $cita, CitaNotificador $notificador, GoogleCalendarService $calendar)
    {
        if ($cita->user_id !== auth()->id()) {
            abort(403);
        }

        if ($cita->estado === 'cancelada') {
            return redirect()->route('citas.mias')->withErrors([
                'error' => 'No puedes reagendar una cita cancelada.'
            ]);
        }

        $request->validate([
            'servicio_id'     => 'required|exists:servicios,id',
            'especialista_id' => 'required|exists:especialistas,id',
            'fecha'           => 'required|date|after_or_equal:today',
            'hora'            => 'required',
            'notas'           => 'nullable|string|max:300',
        ]);

        // 🔒 Validar que no choque con otra cita
        $citaExistente = Cita::where('especialista_id', $request->especialista_id)
            ->whereDate('fecha', $request->fecha)
            ->where('hora', $request->hora)
            ->whereNotIn('estado', ['cancelada'])
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($citaExistente) {
            return back()
                ->withErrors([
                    'hora' => 'Esta especialista ya tiene una cita en esa hora.',
                ])
                ->withInput();
        }

        // 🗑️ Borrar el evento viejo del calendario
        $cita->loadMissing('especialista');
        $calendar->eliminarEvento($cita);

        $cita->update([
            'servicio_id'     => $request->servicio_id,
            'especialista_id' => $request->especialista_id,
            'fecha'           => $request->fecha,
            'hora'            => $request->hora,
            'notas'           => $request->notas,
            'estado'          => 'pendiente',
            'google_event_id' => null,
        ]);

        // 🔔 Nuevo correo, evento de calendario y WhatsApp
        $notificador->citaAgendada($cita->fresh());

        return redirect()->route('citas.mias')->with('success', '¡Cita reagendada con éxito! 💜');
    }

    public function cancelar(Cita $cita, CitaNotificador $notificador)
    {
        if ($cita->user_id !== auth()->id()) {
            abort(403);
        }

        if ($cita->estado === 'cancelada') {
            return redirect()->route('citas.mias')->withErrors([
                'error' => 'Esta cita ya está cancelada.'
            ]);
        }

        $cita->update([
            'estado' => 'cancelada',
        ]);

        // 🔔 Borra el evento del calendario y avisa a la estilista
        $notificador->citaCancelada($cita);

        return redirect()->route('citas.mias')->with('success', 'Cita cancelada con éxito.');
    }

    public function completar(Cita $cita)
    {
        $user = auth()->user();

        // 🔒 Solo el admin o la estilista dueña de la cita
        $esDuenaDeLaCita = $user->esEspecialista()
            && $user->especialista
            && $user->especialista->id === $cita->especialista_id;

        if (!$user->esAdmin() && !$esDuenaDeLaCita) {
            abort(403);
        }

        // No se puede completar una cita cancelada
        if ($cita->estado === 'cancelada') {
            return redirect()->back()->withErrors([
                'error' => 'No puedes completar una cita cancelada.'
            ]);
        }

        $cita->update(['estado' => 'completada']);

        return redirect()->back()->with('success', 'Cita marcada como completada 💜');
    }

    public function horasOcupadas(Request $request)
    {
        if (!$request->filled('especialista_id') || !$request->filled('fecha')) {
            return response()->json([]);
        }

        $query = Cita::where('especialista_id', $request->especialista_id)
            ->whereDate('fecha', $request->fecha)
            ->whereNotIn('estado', ['cancelada']);

        if ($request->filled('excepto_cita_id')) {
            $query->where('id', '!=', $request->excepto_cita_id);
        }

        $horas = $query->pluck('hora')
            ->map(function ($hora) {
                return Carbon::parse($hora)->format('H:i');
            })
            ->values()
            ->toArray();

        return response()->json($horas);
    }
}