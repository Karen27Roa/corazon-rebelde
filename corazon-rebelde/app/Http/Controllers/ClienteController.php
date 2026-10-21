<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use App\Models\Cita;

class ClienteController extends Controller
{
    public function dashboard()
    {
        $citas = Cita::where('user_id', auth()->id())
                     ->with(['servicio', 'especialista.user'])
                     ->orderBy('fecha', 'desc')
                     ->get();

        $servicios = Servicio::where('activo', true)->get();

        return view('cliente.dashboard', compact('citas', 'servicios'));
    }
}