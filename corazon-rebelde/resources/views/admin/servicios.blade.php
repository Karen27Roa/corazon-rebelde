<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; min-height: 100vh; }
        nav {
            background: linear-gradient(135deg, #6b5ca5, #8b7bc4);
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        nav img { width: 130px; }
        .nav-links { display: flex; align-items: center; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 8px 18px; border-radius: 20px; font-weight: bold; cursor: pointer;
        }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
        .header h1 { color: #6b5ca5; font-size: 24px; }
        .card {
            background: white; border-radius: 20px; padding: 32px;
            box-shadow: 0 4px 20px rgba(201,194,238,0.3); margin-bottom: 28px;
        }
        .card h2 { color: #6b5ca5; font-size: 18px; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: span 2; }
        label { display: block; color: #6b5ca5; font-size: 13px; font-weight: bold; margin-bottom: 6px; }
        input, textarea {
            width: 100%; padding: 12px 16px;
            border: 2px solid #e8e4f8; border-radius: 12px;
            font-size: 14px; outline: none; background: #faf9ff; transition: all 0.3s;
        }
        input:focus, textarea:focus { border-color: #c9c2ee; box-shadow: 0 0 0 4px rgba(201,194,238,0.2); }
        .btn {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: white; border: none; padding: 12px 28px;
            border-radius: 25px; font-size: 15px; font-weight: bold; cursor: pointer; transition: all 0.3s;
        }
        .btn:hover { transform: translateY(-2px); }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: linear-gradient(135deg, #c9c2ee, #b8b0e0);
            color: #2c2c2c; padding: 14px 18px; text-align: left; font-size: 13px;
        }
        td { padding: 14px 18px; font-size: 14px; color: #555; border-bottom: 1px solid #f0eeff; }
        tr:hover td { background: #faf9ff; }
        .acciones { display: flex; gap: 8px; }
        .btn-editar {
            background: #e8e4f8; color: #6b5ca5; border: none;
            padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; cursor: pointer;
        }
        .btn-editar:hover { background: #d4cff7; }
        .btn-eliminar {
            background: #ffe0e0; color: #c0392b; border: none;
            padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; cursor: pointer;
        }
        .alert-success {
            background: #d1f5e0; color: #276749;
            padding: 14px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px;
        }
        .empty { text-align: center; padding: 30px; color: #aaa; }
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.5);
            z-index: 1000; align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: white; border-radius: 20px; padding: 32px;
            width: 100%; max-width: 500px; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal h3 { color: #6b5ca5; font-size: 18px; margin-bottom: 20px; }
        .modal-actions { display: flex; gap: 12px; margin-top: 20px; }
        .btn-cancelar {
            flex: 1; background: #f0eeff; color: #6b5ca5; border: none;
            padding: 12px; border-radius: 25px; font-weight: bold; cursor: pointer;
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

    <div class="header">
        <h1>💅 Gestionar Servicios</h1>
    </div>

    <div class="card">
        <h2>✦ Agregar nuevo servicio</h2>
        <form method="POST" action="{{ route('admin.servicios.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre del servicio</label>
                    <input type="text" name="nombre" placeholder="Ej: Uñas acrílicas" required>
                </div>
                <div class="form-group">
                    <label>Precio</label>
                    <input type="number" name="precio" placeholder="Ej: 50000" required>
                </div>
                <div class="form-group full">
                    <label>Descripción</label>
                    <textarea name="descripcion" placeholder="Descripción del servicio..." rows="3"></textarea>
                </div>
            </div>
            <button type="submit" class="btn">✦ Agregar servicio</button>
        </form>
    </div>

    <div class="card">
        <h2>✦ Servicios registrados</h2>
        @if($servicios->isEmpty())
            <div class="empty">No hay servicios registrados aún</div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($servicios as $servicio)
                <tr>
                    <td>{{ $servicio->nombre }}</td>
                    <td>{{ $servicio->descripcion ?? '—' }}</td>
                    <td>${{ number_format($servicio->precio, 0, ',', '.') }}</td>
                    <td>
                        <div class="acciones">
                            <button class="btn-editar" onclick="abrirModal({{ $servicio->id }}, '{{ addslashes($servicio->nombre) }}', '{{ addslashes($servicio->descripcion) }}', {{ $servicio->precio }})">
                                ✏️ Editar
                            </button>
                            <form method="POST" action="{{ route('admin.servicios.destroy', $servicio->id) }}"
                                  onsubmit="return confirm('¿Segura que quieres eliminar este servicio?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalEditar">
    <div class="modal">
        <h3>✏️ Editar servicio</h3>
        <form method="POST" id="formEditar">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nombre del servicio</label>
                <input type="text" name="nombre" id="editNombre" required>
            </div>
            <div class="form-group">
                <label>Precio</label>
                <input type="number" name="precio" id="editPrecio" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" id="editDescripcion" rows="3"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn" style="flex:1">✦ Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(id, nombre, descripcion, precio) {
    document.getElementById('modalEditar').classList.add('active');
    document.getElementById('formEditar').action = '/admin/servicios/' + id;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editDescripcion').value = descripcion;
    document.getElementById('editPrecio').value = precio;
}
function cerrarModal() {
    document.getElementById('modalEditar').classList.remove('active');
}
</script>
</body>
</html>