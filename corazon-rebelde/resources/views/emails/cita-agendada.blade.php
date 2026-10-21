<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Georgia, serif; background: #f5f3ff; padding: 30px; }
        .card {
            background: white; border-radius: 16px; padding: 32px;
            max-width: 520px; margin: auto;
            box-shadow: 0 4px 20px rgba(107,92,165,0.15);
        }
        h2 { color: #6b5ca5; margin-bottom: 6px; }
        .badge {
            display: inline-block; background: #f3f0fb;
            color: #6b5ca5; padding: 4px 14px;
            border-radius: 20px; font-size: 13px; margin-bottom: 20px;
        }
        .info-row { margin: 10px 0; font-size: 15px; color: #444; }
        .info-row span { color: #6b5ca5; font-weight: bold; }
        .btn-calendar {
            display: inline-block;
            margin-top: 24px;
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: bold;
        }
        .footer { margin-top: 24px; font-size: 12px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
<div class="card">

    {{-- ✅ Título y saludo según destinatario --}}
    @if($esCliente)
        <h2>💜 ¡Tu cita está confirmada!</h2>
        <div class="badge">Corazón Rebelde Nail Spa</div>
        <p style="color:#555; font-size:15px;">
            Hola <strong>{{ $cita->user->name }}</strong>, aquí están los detalles de tu cita:
        </p>
    @else
        <h2>💜 Nueva cita agendada</h2>
        <div class="badge">Corazón Rebelde Nail Spa</div>
        <p style="color:#555; font-size:15px;">
            Se ha registrado una nueva cita en el sistema.
        </p>
    @endif

    {{-- ✅ Detalles de la cita --}}
    <div class="info-row">💅 <span>Servicio:</span> {{ $cita->servicio->nombre }}</div>
    <div class="info-row">💆 <span>Especialista:</span> {{ $cita->especialista->user->name }}</div>
    <div class="info-row">📅 <span>Fecha:</span> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</div>
    <div class="info-row">🕐 <span>Hora:</span> {{ $cita->hora }}</div>

    {{-- ✅ Solo el negocio ve el email de la clienta --}}
    @if(!$esCliente)
        <div class="info-row">👤 <span>Cliente:</span> {{ $cita->user->name }}</div>
        <div class="info-row">📧 <span>Email:</span> {{ $cita->user->email }}</div>
    @endif

    @if($cita->notas)
        <div class="info-row">📝 <span>Notas:</span> {{ $cita->notas }}</div>
    @endif

    {{-- ✅ Botón Google Calendar --}}
    @php
        $inicio  = \Carbon\Carbon::parse($cita->fecha . ' ' . $cita->hora);
        $fin     = $inicio->copy()->addHour();
        $texto   = urlencode('💅 ' . $cita->servicio->nombre . ' - ' . $cita->user->name);
        $detalle = urlencode('Cliente: ' . $cita->user->name . ' | Especialista: ' . $cita->especialista->user->name . ($cita->notas ? ' | Notas: ' . $cita->notas : ''));
        $lugar   = urlencode('Corazón Rebelde Nail Spa');
        $fechaInicio = $inicio->format('Ymd\THis');
        $fechaFin    = $fin->format('Ymd\THis');
        $googleUrl   = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$texto}&dates={$fechaInicio}/{$fechaFin}&details={$detalle}&location={$lugar}";
    @endphp

    {{-- ✅ Mensaje de cierre según destinatario --}}
    @if($esCliente)
        <p style="color:#555; font-size:13px; margin-top:20px;">
            Si necesitas cancelar o modificar tu cita, contáctanos con anticipación. ¡Te esperamos! 💜
        </p>
    @endif

    <div class="footer">Corazón Rebelde Nail Spa · Sistema de citas</div>
</div>
</body>
</html>