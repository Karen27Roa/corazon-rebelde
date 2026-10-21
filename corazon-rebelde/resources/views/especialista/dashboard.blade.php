<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Especialista - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #c9c2ee, #b8b0e0);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(201,194,238,0.4);
        }
        nav img { width: 130px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer;
        }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .bienvenida {
            background: white; border-radius: 20px; padding: 28px 32px;
            margin-bottom: 28px; box-shadow: 0 4px 20px rgba(201,194,238,0.3);
        }
        .bienvenida h1 { color: #6b5ca5; font-size: 22px; }
        .bienvenida p { color: #888; margin-top: 6px; font-size: 14px; }
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: white; border-radius: 16px; padding: 22px;
            text-align: center; box-shadow: 0 4px 15px rgba(201,194,238,0.2);
        }
        .stat-card .numero { font-size: 32px; font-weight: bold; color: #6b5ca5; }
        .stat-card .label { color: #888; font-size: 13px; margin-top: 4px; }
        h2 { color: #6b5ca5; font-size: 20px; margin-bottom: 16px; }
        .cita-card {
            background: white; border-radius: 16px; padding: 20px 24px;
            box-shadow: 0 4px 15px rgba(201,194,238,0.2);
            margin-bottom: 14px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .cita-info h3 { color: #6b5ca5; font-size: 16px; }
        .cita-info p { color: #888; font-size: 13px; margin-top: 4px; }
        .badge { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .pendiente { background: #fff3cd; color: #856404; }
        .completada { background: #d1f5e0; color: #276749; }
        .cancelada { background: #ffe0e0; color: #c0392b; }
        .btn-completar {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white; border: none; padding: 8px 18px;
            border-radius: 20px; font-size: 13px; font-weight: bold;
            cursor: pointer; transition: all 0.3s;
        }
        .btn-completar:hover { transform: translateY(-1px); }
        .actions { display: flex; align-items: center; gap: 10px; }
        .empty {
            text-align: center; padding: 50px; color: #aaa;
            background: white; border-radius: 16px;
        }
        .alert-success {
            background: #d1f5e0; color: #276749;
            padding: 14px 20px; border-radius: 12px;
            margin-bottom: 20px; font-size: 14px;
        }
    </style>
</head>
<body>

<nav>
    <x-logo />
    <div class="nav-links">
        <span style="color:white; font-size:14px;">✦ Panel Especialista</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="bienvenida">
        <h1>¡Hola, {{ auth()->user()->name }}! 💜</h1>
        <p>Aquí puedes ver y gestionar todas tus citas del día</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="numero">{{ $citas->count() }}</div>
            <div class="label">Total de citas</div>
        </div>
        <div class="stat-card">
            <div class="numero">{{ $citas->where('estado', 'pendiente')->count() }}</div>
            <div class="label">Pendientes</div>
        </div>
        <div class="stat-card">
            <div class="numero">{{ $citas->where('estado', 'completada')->count() }}</div>
            <div class="label">Completadas</div>
        </div>
    </div>

    <h2>✦ Mis citas</h2>

    @if($citas->isEmpty())
        <div class="empty">
            <p>💜 No tienes citas asignadas por el momento</p>
        </div>
    @else
        @foreach($citas as $cita)
        <div class="cita-card">
            <div class="cita-info">
                <h3>{{ $cita->servicio->nombre }}</h3>
                <p>👤 Cliente: {{ $cita->user->name }}</p>
                <p>📅 {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }} — 🕐 {{ $cita->hora }}</p>
                @if($cita->notas)
                    <p>📝 {{ $cita->notas }}</p>
                @endif
            </div>
            <div class="actions">
                <span class="badge {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span>
                @if($cita->estado === 'pendiente')
                    <form method="POST" action="{{ route('citas.completar', $cita->id) }}">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-completar">✓ Completar</button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    @endif

</div>
</body>
</html>