<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Especialista;
use App\Models\ListaEspera;
use App\Models\Servicio;
use App\Services\ListaEsperaService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListaEsperaController extends Controller
{
    /** Mismas horas que se ofrecen al agendar una cita. */
    public const HORAS = Cita::HORAS;

    public function index(Request $request)
    {
        $solicitudes = ListaEspera::where('user_id', auth()->id())
            ->with(['servicio', 'especialista.user'])
            ->orderByDesc('created_at')
            ->get();

        $servicios     = Servicio::where('activo', true)->get();
        $especialistas = Especialista::with('user')->get();
        $horas         = self::HORAS;

        return view('cliente.lista-espera', compact('solicitudes', 'servicios', 'especialistas', 'horas'));
    }

    public function store(Request $request, ListaEsperaService $listaEspera)
    {
        if ($request->filled('telefono')) {
            $request->merge(['telefono' => preg_replace('/\D/', '', $request->telefono)]);
        }

        $datos = $request->validate([
            'servicio_id'     => 'required|exists:servicios,id',
            'especialista_id' => 'nullable|exists:especialistas,id',
            'fecha'           => 'required|date|after_or_equal:today',
            'hora'            => ['nullable', Rule::in(self::HORAS)],
            'telefono'        => ['required', 'regex:/^3\d{9}$/'],
        ], [
            'telefono.required' => 'Escribe tu celular para avisarte por WhatsApp.',
            'telefono.regex'    => 'Escribe un celular válido de 10 dígitos, por ejemplo 3001234567.',
        ]);

        // No tiene sentido esperar un horario que ya pasó
        if (! empty($datos['hora']) && Carbon::parse($datos['fecha'] . ' ' . $datos['hora'])->isPast()) {
            return back()->withErrors(['hora' => 'Esa hora ya pasó. Elige una hora más adelante.'])->withInput();
        }

        // Si eligió especialista y hora exactas y ese horario está libre, mejor que agende de una vez
        if (! empty($datos['especialista_id']) && ! empty($datos['hora'])) {
            $prueba = (new Cita)->forceFill([
                'especialista_id' => $datos['especialista_id'],
                'fecha'           => $datos['fecha'],
                'hora'            => $datos['hora'],
            ]);

            if ($listaEspera->horarioSigueLibre($prueba)) {
                return redirect()
                    ->route('citas.create', $request->only('servicio_id', 'especialista_id', 'fecha', 'hora'))
                    ->with('info', '¡Buenas noticias! Ese horario está libre, agéndalo ahora 💜');
            }
        }

        // Evita anotarse dos veces a lo mismo
        $repetida = ListaEspera::activas()
            ->where('user_id', auth()->id())
            ->whereDate('fecha', $datos['fecha'])
            ->when(
                empty($datos['especialista_id']),
                fn ($q) => $q->whereNull('especialista_id'),
                fn ($q) => $q->where('especialista_id', $datos['especialista_id'])
            )
            ->get()
            ->contains(fn ($s) => $s->hora_corta === ($datos['hora'] ?? null));

        if ($repetida) {
            return back()->withErrors(['fecha' => 'Ya estás en la lista de espera para ese horario.'])->withInput();
        }

        auth()->user()->update(['telefono' => $datos['telefono']]);

        ListaEspera::create($datos + [
            'user_id' => auth()->id(),
            'estado'  => ListaEspera::ESPERANDO,
        ]);

        $fecha = Carbon::parse($datos['fecha'])->format('d/m/Y');

        return redirect()->route('lista-espera.index')
            ->with('success', "¡Listo! Te avisaremos por WhatsApp si se libera un horario el {$fecha} 💜");
    }

    /**
     * Enlace corto del WhatsApp (/cupo/{id}): abre el formulario de agendar
     * con el servicio, la especialista, la fecha y la hora ya seleccionados.
     */
    public function cupo(ListaEspera $listaEspera)
    {
        abort_if($listaEspera->user_id !== auth()->id(), 403);

        if (! $listaEspera->cupo_fecha) {
            return redirect()->route('lista-espera.index');
        }

        return redirect()->route('citas.create', [
            'servicio_id'     => $listaEspera->servicio_id,
            'especialista_id' => $listaEspera->cupo_especialista_id,
            'fecha'           => Carbon::parse($listaEspera->cupo_fecha)->format('Y-m-d'),
            'hora'            => Carbon::parse($listaEspera->cupo_hora)->format('H:i'),
        ]);
    }

    public function destroy(ListaEspera $listaEspera)
    {
        abort_if($listaEspera->user_id !== auth()->id(), 403);

        $listaEspera->update(['estado' => ListaEspera::CANCELADA]);

        return redirect()->route('lista-espera.index')->with('success', 'Saliste de la lista de espera.');
    }
}
