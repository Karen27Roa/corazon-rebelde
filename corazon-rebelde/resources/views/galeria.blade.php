<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Georgia', serif; background: #f5f3ff; }

        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            background: rgba(255,255,255,0.95);
            padding: 14px 40px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 20px rgba(201,194,238,0.4);
            backdrop-filter: blur(10px);
        }
        nav img { width: 180px; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a {
            color: #6b5ca5; text-decoration: none;
            font-size: 14px; font-weight: bold; transition: color 0.3s;
        }
        .nav-links a:hover { color: #a89fd4; }
        .btn-nav {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: #2c2c2c; padding: 10px 24px; border-radius: 25px;
            text-decoration: none; font-weight: bold; font-size: 14px;
            transition: all 0.3s;
        }
        .btn-nav:hover { transform: translateY(-2px); color: #2c2c2c; }

        .hero {
            margin-top: 80px;
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            padding: 60px 40px;
            text-align: center;
        }
        .hero h1 {
            color: white; font-size: 42px; font-style: italic;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2); margin-bottom: 10px;
        }
        .hero p { color: rgba(255,255,255,0.9); font-size: 18px; font-style: italic; }

        .container { max-width: 1200px; margin: 0 auto; padding: 60px 40px; }

        .filtros {
            display: flex; gap: 12px; flex-wrap: wrap;
            justify-content: center; margin-bottom: 40px;
        }
        .filtro-btn {
            background: white; color: #6b5ca5; border: 2px solid #e8e4f8;
            padding: 8px 20px; border-radius: 25px; font-size: 13px;
            font-weight: bold; cursor: pointer; transition: all 0.3s;
            font-family: 'Georgia', serif;
        }
        .filtro-btn:hover, .filtro-btn.active {
            background: linear-gradient(135deg, #c9c2ee, #a89fd4);
            color: #2c2c2c; border-color: #c9c2ee;
        }

        .galeria-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
        }
        .galeria-item {
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 20px rgba(201,194,238,0.3);
            background: white; transition: transform 0.3s;
            cursor: pointer;
        }
        .galeria-item:hover { transform: translateY(-5px); }
        .galeria-item img {
            width: 100%; height: 250px; object-fit: cover;
            transition: transform 0.4s;
        }
        .galeria-item:hover img { transform: scale(1.05); }
        .galeria-item-info { padding: 14px 16px; }
        .galeria-item-info h4 { color: #6b5ca5; font-size: 14px; margin-bottom: 4px; }
        .galeria-item-info p { color: #aaa; font-size: 12px; }

        .empty {
            grid-column: span 4; text-align: center;
            padding: 60px; color: #aaa; font-size: 16px;
        }

        /* Modal de imagen */
        .modal-img {
            display: none; position: fixed; top: 0; left: 0;
            width: 100%; height: 100%; background: rgba(0,0,0,0.85);
            z-index: 1000; align-items: center; justify-content: center;
        }
        .modal-img.active { display: flex; }
        .modal-img img {
            max-width: 80%; max-height: 85vh;
            border-radius: 16px; object-fit: contain;
        }
        .modal-img-close {
            position: absolute; top: 20px; right: 30px;
            color: white; font-size: 36px; cursor: pointer;
            background: none; border: none; line-height: 1;
        }
        .modal-img-info {
            position: absolute; bottom: 30px; left: 50%;
            transform: translateX(-50%);
            background: rgba(107,92,165,0.9); color: white;
            padding: 12px 28px; border-radius: 25px;
            font-size: 15px; font-style: italic;
        }

        footer { background: #2c2c2c; color: white; padding: 32px 40px; text-align: center; }
        footer p { color: #666; font-size: 13px; }
        footer a { color: #c9c2ee; text-decoration: none; font-weight: bold; }

        .destello { position: fixed; pointer-events: none; animation: brillar 3s infinite; }
        .destello::before { content: '✦'; color: #f0b429; opacity: 0.5; font-size: 18px; }
        .d1 { top: 15%; left: 8%; animation-delay: 0s; }
        .d2 { top: 30%; right: 6%; animation-delay: 1s; }
        @keyframes brillar {
            0%, 100% { opacity: 0.1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }
    </style>
</head>
<body>

<div class="destello d1"></div>
<div class="destello d2"></div>

<nav>
    <x-logo />
    <div class="nav-links">
        <a href="{{ url('/') }}">← Inicio</a>
        <a href="{{ url('/') }}#servicios">Servicios</a>
        <a href="{{ url('/') }}#contacto">Contacto</a>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn-nav">Panel Admin</a>
            @elseif(auth()->user()->role === 'especialista')
                <a href="{{ route('especialista.dashboard') }}" class="btn-nav">Mi Panel</a>
            @else
                <a href="{{ route('cliente.dashboard') }}" class="btn-nav">Mi Panel</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn-nav">✦ Agendar cita</a>
        @endauth
    </div>
</nav>

<div class="hero">
    <h1>✦ Nuestra Galería ✦</h1>
    <p>Cada trabajo es una obra de arte hecha con amor 💜</p>
</div>

<div class="container">

    {{-- Filtros por servicio --}}
    <div class="filtros">
        <button class="filtro-btn active" onclick="filtrar('todos', this)">✦ Todos</button>
        @foreach($servicios as $servicio)
            <button class="filtro-btn" onclick="filtrar('{{ $servicio->id }}', this)">
                {{ $servicio->nombre }}
            </button>
        @endforeach
    </div>

    <div class="galeria-grid" id="galeriaGrid">
        @forelse($imagenes as $imagen)
        <div class="galeria-item" data-servicio="{{ $imagen->servicio_id }}"
             onclick="abrirModal('{{ asset('storage/' . $imagen->imagen) }}', '{{ $imagen->servicio->nombre }}', '{{ $imagen->descripcion ?? '' }}')">
            <img src="{{ asset('storage/' . $imagen->imagen) }}" alt="{{ $imagen->descripcion }}">
            <div class="galeria-item-info">
                <h4>{{ $imagen->servicio->nombre }}</h4>
                <p>{{ $imagen->descripcion ?? 'Trabajo realizado con amor 💜' }}</p>
            </div>
        </div>
        @empty
        <div class="empty">
            <p>💜 Próximamente fotos de nuestros trabajos</p>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal -->
<div class="modal-img" id="modalImg" onclick="cerrarModal()">
    <button class="modal-img-close" onclick="cerrarModal()">✕</button>
    <img id="modalImgSrc" src="" alt="">
    <div class="modal-img-info" id="modalImgInfo"></div>
</div>

<footer>
    <p>© 2026 Corazón Rebelde Nails Spa 💜 — <a href="{{ url('/') }}">Volver al inicio</a></p>
</footer>

<script>
function filtrar(servicioId, btn) {
    document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const items = document.querySelectorAll('.galeria-item');
    items.forEach(item => {
        if (servicioId === 'todos' || item.dataset.servicio === servicioId) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function abrirModal(src, nombre, descripcion) {
    document.getElementById('modalImg').classList.add('active');
    document.getElementById('modalImgSrc').src = src;
    document.getElementById('modalImgInfo').textContent = nombre + (descripcion ? ' — ' + descripcion : '');
}

function cerrarModal() {
    document.getElementById('modalImg').classList.remove('active');
}
</script>

</body>
</html>