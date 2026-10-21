<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cancelar cita - Corazón Rebelde</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background:#f5f3ff; display:flex; justify-content:center; padding:40px 16px; margin:0; }
        .card { background:#fff; max-width:460px; width:100%; border-radius:20px; padding:32px; text-align:center; box-shadow:0 4px 20px rgba(201,194,238,0.4); }
        h2 { color:#6b5ca5; margin-top:0; }
        p { color:#555; line-height:1.7; }
        .btn { border:0; padding:12px 22px; border-radius:20px; color:#fff; font-size:15px; font-weight:bold; cursor:pointer; text-decoration:none; display:inline-block; margin:4px; }
        .danger { background:linear-gradient(135deg,#f87171,#ef4444); }
        .gray { background:#9ca3af; }
    </style>
</head>
<body>
<div class="card">
    <h2>¿Cancelar tu cita?</h2>
    <p>
        💅 {{ $cita->servicio->nombre }}<br>
        📆 {{ $cita->inicio->format('d/m/Y') }} a las {{ $cita->inicio->format('h:i A') }}<br>
        👩‍🎨 {{ $cita->especialista->user->name }}
    </p>

    @if($cita->estado === 'cancelada')
        <p><strong>Esta cita ya fue cancelada.</strong></p>
    @elseif($cita->estado === 'completada')
        <p><strong>Esta cita ya fue completada y no se puede cancelar.</strong></p>
    @else
        <form method="POST" action="{{ request()->fullUrl() }}">
            @csrf
            <button type="submit" class="btn danger">Sí, cancelar mi cita</button>
            <a href="{{ url('/') }}" class="btn gray">No, volver</a>
        </form>
    @endif
</div>
</body>
</html>