<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Especialista;

class EspecialistaController extends Controller
{
    public function dashboard()
    {
        $especialista = Especialista::where('user_id', auth()->id())->first();

        $citas = Cita::where('especialista_id', $especialista->id)
                     ->with(['user', 'servicio'])
                     ->orderBy('fecha', 'asc')
                     ->get();

        return view('especialista.dashboard', compact('citas', 'especialista'));
    }
}