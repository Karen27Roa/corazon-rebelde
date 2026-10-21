<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #6b5ca5, #8b7bc4);
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 15px rgba(107,92,165,0.4);
        }
        nav img { width: 130px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer;
        }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #6b5ca5; font-size: 24px; margin-bottom: 28px; }
        .card {
            background: white; border-radius: 20px; padding: 32px;
            box-shadow: 0 4px 20px rgba(201,194,238,0.3); margin-bottom: 28px;
        }
        .alert-success {
            background: #d1f5e0; color: #276749;
            padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;
        }
        .alert-error {
            background: #ffe0e0; color: #c0392b;
            padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;
        }
        .empty { text-align: center; padding: 40px; color: #aaa; }
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        thead tr { background: #f3f0fb; }
        thead th {
            padding: 12px 10px; text-align: left;
            color: #6b5ca5; font-size: 12px; font-weight: bold;
            border-bottom: 2px solid #e8e4f8; white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid #f0edfb; transition: background 0.2s; }
        tbody tr:hover { background: #faf9ff; }
        tbody td { padding: 10px; font-size: 13px; color: #555; vertical-align: middle; }
        .badge {
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: bold; white-space: nowrap;
        }
        .pendiente  { background: #fff3cd; color: #856404; }
        .completada { background: #d1f5e0; color: #276749; }
        .cancelada  { background: #ffe0e0; color: #c0392b; }
        .acciones { display: flex; gap: 6px; align-items: center; flex-wrap: nowrap; }
        select.estado-select {
            padding: 5px 8px; border-radius: 20px;
            border: 2px solid #e8e4f8; background: #faf9ff;
            color: #6b5ca5; font-size: 11px; font-weight: bold;
            cursor: pointer; outline: none; max-width: 110px;
        }
        .btn-estado {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white; border: none;
            padding: 5px 10px; border-radius: 20px;
            font-size: 11px; font-weight: bold;
            cursor: pointer; transition: all 0.2s; white-space: nowrap;
        }
        .btn-estado:hover { opacity: 0.9; }
        .btn-eliminar {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white; border: none;
            padding: 5px 10px; border-radius: 20px;
            font-size: 11px; cursor: pointer; transition: all 0.2s;
        }
        .btn-eliminar:hover { opacity: 0.9; }
        .filtros { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; align-items: center; }
        .filtro-btn {
            padding: 7px 18px; border-radius: 20px;
            border: 2px solid #e8e4f8; background: white;
            color: #6b5ca5; font-size: 13px; font-weight: bold;
            cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .filtro-btn:hover, .filtro-btn.active {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white; border-color: transparent;
        }
        .total-badge {
            background: #f3f0fb; color: #6b5ca5;
            padding: 4px 12px; border-radius: 20px;
            font-size: 13px; font-weight: bold; margin-left: auto;
        }
    </style>
</head>
<body>
<nav>
    <x-logo />
    <div class="nav-links">
        <a href="{{ route('admin.dashboard') }}">✦ Dashboard</a>
        <a href="{{ route('admin.servicios') }}">✦ Servicios</a>
        <a href="{{ route('admin.especialistas') }}">✦ Especialistas</a>
        <a href="{{ route('admin.citas') }}">✦ Citas</a>
        <a href="{{ route('admin.galeria') }}">✦ Galería</a>
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

    @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <h1>📅 Gestionar Citas</h1>

    <div class="card">
        <div class="filtros">
            <a href="{{ route('admin.citas') }}"
               class="filtro-btn {{ !request('estado') ? 'active' : '' }}">Todas</a>
            <a href="{{ route('admin.citas', ['estado' => 'pendiente']) }}"
               class="filtro-btn {{ request('estado') === 'pendiente' ? 'active' : '' }}">🟡 Pendientes</a>
            <a href="{{ route('admin.citas', ['estado' => 'completada']) }}"
               class="filtro-btn {{ request('estado') === 'completada' ? 'active' : '' }}">🟢 Completadas</a>
            <a href="{{ route('admin.citas', ['estado' => 'cancelada']) }}"
               class="filtro-btn {{ request('estado') === 'cancelada' ? 'active' : '' }}">🔴 Canceladas</a>
            <span class="total-badge">Total: {{ $citas->count() }}</span>
        </div>

        @if($citas->isEmpty())
            <div class="empty">No hay citas registradas aún 💜</div>
        @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Especialista</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Notas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $i => $cita)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong>{{ $cita->user->name }}</strong><br>
                            <small style="color:#aaa">{{ $cita->user->email }}</small>
                        </td>
                        <td>{{ $cita->servicio->nombre }}</td>
                        <td>{{ $cita->especialista->user->name }}</td>
                        <td style="white-space:nowrap">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                        <td style="white-space:nowrap">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                        <td>{{ $cita->notas ?? '—' }}</td>
                        <td><span class="badge {{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
                        <td>
                            <div class="acciones">
                                <form action="{{ route('admin.citas.estado', $cita) }}" method="POST" style="display:flex; gap:4px;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="estado" class="estado-select">
                                        <option value="pendiente"  {{ $cita->estado === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                        <option value="completada" {{ $cita->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="cancelada"  {{ $cita->estado === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    <button type="submit" class="btn-estado">✔</button>
                                </form>
                                <form action="{{ route('admin.citas.destroy', $cita) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta cita?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-eliminar">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
</body>
</html>