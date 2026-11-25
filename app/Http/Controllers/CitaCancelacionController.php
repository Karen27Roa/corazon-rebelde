<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\CitaNotificador;

class CitaCancelacionController extends Controller
{
    // Pantalla de confirmación (se abre desde el enlace del correo)
    public function confirmar(Cita $cita)
    {
        $cita->loadMissing(['servicio', 'especialista.user']);

        return view('citas.confirmar-cancelacion', compact('cita'));
    }

    // Cancelación real (el botón de la pantalla anterior envía un POST)
    public function cancelar(Cita $cita, CitaNotificador $notificador)
    {
        if ($cita->estado === 'pendiente') {
            $cita->update(['estado' => 'cancelada']);
            $notificador->citaCancelada($cita);
        }

        return view('citas.cancelada');
    }
}