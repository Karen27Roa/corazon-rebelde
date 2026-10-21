<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Especialista;
use App\Models\Cita;
use App\Models\User;
use App\Models\Galeria;
use App\Services\CitaNotificador;
use App\Services\GoogleCalendarService;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalCitas = Cita::count();
        $totalClientes = User::where('role', 'cliente')->count();
        $totalEspecialistas = Especialista::count();
        $totalServicios = Servicio::count();
        $citasRecientes = Cita::with(['user', 'servicio', 'especialista.user'])
                              ->orderBy('created_at', 'desc')
                              ->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCitas', 'totalClientes', 'totalEspecialistas',
            'totalServicios', 'citasRecientes'
        ));
    }

    public function servicios()
    {
        $servicios = Servicio::all();
        return view('admin.servicios', compact('servicios'));
    }

    public function storeServicio(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'required|numeric',
        ]);

        Servicio::create([
            'nombre'      => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio'      => $request->precio,
            'activo'      => true,
        ]);

        return redirect()->route('admin.servicios')->with('success', 'Servicio agregado con éxito 💜');
    }

    public function destroyServicio(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('admin.servicios')->with('success', 'Servicio eliminado 💜');
    }

    public function especialistas()
    {
        $especialistas = Especialista::with('user')->get();
        $usuarios = User::where('role', 'especialista')->get();
        return view('admin.especialistas', compact('especialistas', 'usuarios'));
    }

    public function storeEspecialista(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8',
            'telefono' => 'nullable|string',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'especialista',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('especialistas', 'public');
        }

        Especialista::create([
            'user_id'  => $user->id,
            'telefono' => $request->telefono,
            'foto'     => $fotoPath,
        ]);

        return redirect()->route('admin.especialistas')->with('success', 'Especialista agregada con éxito 💜');
    }

    public function galeria()
    {
        $imagenes = Galeria::with('servicio')->get();
        $servicios = Servicio::all();
        return view('admin.galeria', compact('imagenes', 'servicios'));
    }

    public function storeGaleria(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'imagen'      => 'required|image|max:2048',
            'descripcion' => 'nullable|string',
        ]);

        $path = $request->file('imagen')->store('galeria', 'public');

        Galeria::create([
            'servicio_id' => $request->servicio_id,
            'imagen'      => $path,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('admin.galeria')->with('success', 'Imagen subida con éxito 💜');
    }

    public function destroyGaleria(Galeria $galeria)
    {
        \Storage::disk('public')->delete($galeria->imagen);
        $galeria->delete();
        return redirect()->route('admin.galeria')->with('success', 'Imagen eliminada con éxito 💜');
    }

    public function updateGaleria(Request $request, Galeria $galeria)
    {
        $request->validate([
            'descripcion' => 'nullable|string',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $galeria->update([
            'descripcion' => $request->descripcion,
            'servicio_id' => $request->servicio_id,
        ]);

        return redirect()->route('admin.galeria')->with('success', 'Imagen actualizada con éxito 💜');
    }

    public function updateServicio(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'precio'      => 'required|numeric',
            'descripcion' => 'nullable|string',
        ]);

        $servicio->update([
            'nombre'      => $request->nombre,
            'precio'      => $request->precio,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('admin.servicios')->with('success', 'Servicio actualizado con éxito 💜');
    }

    public function updateEspecialista(Request $request, Especialista $especialista)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $especialista->user->id,
            'telefono' => 'nullable|string',
            'foto'     => 'nullable|image|max:2048',
        ]);

        $especialista->user->name  = $request->name;
        $especialista->user->email = $request->email;

        if ($request->password) {
            $especialista->user->password = bcrypt($request->password);
        }

        $especialista->user->save();

        $especialista->telefono = $request->telefono;

        if ($request->hasFile('foto')) {
            if ($especialista->foto) {
                \Storage::disk('public')->delete($especialista->foto);
            }
            $especialista->foto = $request->file('foto')->store('especialistas', 'public');
        }

        $especialista->save();

        return redirect()->route('admin.especialistas')->with('success', 'Especialista actualizada con éxito 💜');
    }

    public function destroyEspecialista(Especialista $especialista)
    {
        if ($especialista->foto) {
            \Storage::disk('public')->delete($especialista->foto);
        }
        $especialista->user->delete();
        $especialista->delete();

        return redirect()->route('admin.especialistas')->with('success', 'Especialista eliminada 💜');
    }

    // ── Citas ──

    public function citas()
    {
        $query = Cita::with(['user', 'servicio', 'especialista.user'])
                     ->orderBy('fecha', 'desc')
                     ->orderBy('hora', 'desc');

        if (request('estado')) {
            $query->where('estado', request('estado'));
        }

        $citas = $query->get();

        return view('admin.citas', compact('citas'));
    }

    public function updateEstadoCita(
        Request $request,
        Cita $cita,
        CitaNotificador $notificador,
        GoogleCalendarService $calendar
    ) {
        $request->validate([
            'estado' => 'required|in:pendiente,completada,cancelada',
        ]);

        $estadoAnterior = $cita->estado;
        $estadoNuevo    = $request->estado;

        // Si reactiva una cita cancelada, verificar que la hora siga libre
        if ($estadoAnterior === 'cancelada' && $estadoNuevo !== 'cancelada') {
            $horaOcupada = Cita::where('especialista_id', $cita->especialista_id)
                ->whereDate('fecha', $cita->fecha)
                ->where('hora', $cita->hora)
                ->whereNotIn('estado', ['cancelada'])
                ->where('id', '!=', $cita->id)
                ->exists();

            if ($horaOcupada) {
                return redirect()->route('admin.citas')->withErrors([
                    'error' => 'No se puede reactivar: la especialista ya tiene otra cita en esa hora.',
                ]);
            }
        }

        $cita->update(['estado' => $estadoNuevo]);

        // Pasó a cancelada: borra el evento del calendario y avisa a la estilista
        if ($estadoNuevo === 'cancelada' && $estadoAnterior !== 'cancelada') {
            $notificador->citaCancelada($cita);
        }

        // Se reactivó una cita cancelada: vuelve a crear el evento en el calendario
        if ($estadoAnterior === 'cancelada' && $estadoNuevo !== 'cancelada') {
            $cita->update(['google_event_id' => null]);
            $calendar->crearEvento($cita->fresh(['user', 'servicio', 'especialista.user']));
        }

        return redirect()->route('admin.citas')->with('success', 'Estado de cita actualizado 💜');
    }

    public function destroyCita(Cita $cita, GoogleCalendarService $calendar)
    {
        // Borra el evento del calendario de la estilista antes de eliminar la cita
        if ($cita->estado !== 'cancelada') {
            $cita->loadMissing('especialista');
            $calendar->eliminarEvento($cita);
        }

        $cita->delete();

        return redirect()->route('admin.citas')->with('success', 'Cita eliminada 💜');
    }
}