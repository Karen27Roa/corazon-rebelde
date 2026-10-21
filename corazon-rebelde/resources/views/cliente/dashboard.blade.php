<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Panel - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 10% 0%, #e9defc 0%, transparent 45%),
                radial-gradient(circle at 90% 20%, #ffe3f3 0%, transparent 40%),
                linear-gradient(160deg, #f5f3ff 0%, #ece6fb 100%);
            background-attachment: fixed;
        }

        /* ---------- Uñas y corazones flotando ---------- */
        .fondo { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }
        .flota { position: absolute; bottom: -70px; opacity: 0; animation: subir linear infinite; }
        .flota > span { display: inline-block; animation: vaiven ease-in-out infinite; filter: drop-shadow(0 4px 6px rgba(107,92,165,.25)); }
        .combo { position: relative; display: inline-block; line-height: 1; }
        .combo .una { position: absolute; top: -.55em; right: -.45em; font-size: .62em; transform: rotate(18deg); }
        @keyframes subir {
            0%   { transform: translateY(0); opacity: 0; }
            10%  { opacity: .85; }
            90%  { opacity: .55; }
            100% { transform: translateY(-118vh); opacity: 0; }
        }
        @keyframes vaiven {
            0%, 100% { transform: translateX(-16px) rotate(-16deg); }
            50%      { transform: translateX(16px) rotate(16deg); }
        }

        /* ---------- Menú ---------- */
        nav {
            position: relative; z-index: 5;
            background: linear-gradient(135deg, #c9c2ee, #b4aae0);
            padding: 10px 32px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
            box-shadow: 0 6px 24px rgba(107,92,165,0.3);
        }
        .nav-links { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
        .nav-links a {
            color: white; text-decoration: none; font-size: 14px; font-weight: bold;
            position: relative; transition: transform .2s;
        }
        .nav-links a::after {
            content: ''; position: absolute; left: 0; bottom: -4px; width: 0; height: 2px;
            background: white; transition: width .3s;
        }
        .nav-links a:hover { transform: translateY(-2px); }
        .nav-links a:hover::after { width: 100%; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none;
            padding: 9px 20px; border-radius: 25px; font-weight: bold; cursor: pointer; font-size: 13px;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-logout:hover { transform: scale(1.06); box-shadow: 0 6px 16px rgba(107,92,165,.3); }

        /* ---------- Estructura ---------- */
        .container { position: relative; z-index: 2; max-width: 1120px; margin: 34px auto 60px; padding: 0 20px; }
        .alert-success {
            background: #d1f5e0; color: #276749; padding: 14px 20px; border-radius: 14px;
            margin-bottom: 20px; font-size: 14px; font-weight: bold; animation: entrar .6s ease both;
        }
        @keyframes entrar { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: translateY(0); } }

        /* ---------- Bienvenida ---------- */
        .hero {
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, #5b3a8f 0%, #8b5fd0 55%, #c08bf0 100%);
            border-radius: 28px; padding: 38px 40px; color: white;
            display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
            box-shadow: 0 18px 44px rgba(107,92,165,.4);
            animation: entrar .7s ease both;
        }
        .hero::before, .hero::after {
            content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,.1);
        }
        .hero::before { width: 260px; height: 260px; top: -90px; right: -60px; }
        .hero::after  { width: 160px; height: 160px; bottom: -70px; left: 30%; }
        .hero > * { position: relative; z-index: 1; }
        .hero h1 { font-size: 30px; font-weight: normal; }
        .hero h1 .mano { display: inline-block; transform-origin: 70% 70%; animation: saludo 2.4s ease-in-out infinite; }
        @keyframes saludo { 0%,60%,100% { transform: rotate(0); } 10%,30% { transform: rotate(16deg); } 20%,40% { transform: rotate(-10deg); } 50% { transform: rotate(8deg); } }
        .hero p { margin-top: 8px; font-size: 14px; opacity: .92; }
        .btn-agendar {
            position: relative; overflow: hidden; display: inline-block; text-decoration: none;
            background: white; color: #6b3fb0; padding: 14px 30px; border-radius: 30px;
            font-weight: bold; font-size: 15px; box-shadow: 0 8px 22px rgba(0,0,0,.2);
            transition: transform .25s, box-shadow .25s;
        }
        .btn-agendar::before {
            content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%;
            background: linear-gradient(120deg, transparent, rgba(150,100,230,.35), transparent);
            transform: skewX(-20deg); animation: brillo 3s ease-in-out infinite;
        }
        @keyframes brillo { 0% { left: -80%; } 60%, 100% { left: 140%; } }
        .btn-agendar:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 14px 30px rgba(0,0,0,.28); }

        /* ---------- Estadísticas ---------- */
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin: 24px 0 8px; }
        .stat {
            background: rgba(255,255,255,.85); backdrop-filter: blur(6px);
            border-radius: 22px; padding: 22px; text-align: center;
            border: 1px solid #efe9fc; box-shadow: 0 8px 26px rgba(201,194,238,.4);
            animation: entrar .7s ease both; transition: transform .3s;
        }
        .stat:nth-child(1) { animation-delay: .1s; } .stat:nth-child(2) { animation-delay: .2s; } .stat:nth-child(3) { animation-delay: .3s; }
        .stat:hover { transform: translateY(-6px); }
        .stat .ico { font-size: 30px; display: inline-block; animation: flotar 3s ease-in-out infinite; }
        .stat .num { font-size: 34px; color: #6b5ca5; font-weight: bold; margin-top: 4px; }
        .stat .lab { color: #9b8ec4; font-size: 13px; margin-top: 2px; }
        @keyframes flotar { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

        /* ---------- Columnas ---------- */
        .cols { display: grid; grid-template-columns: 1fr 370px; gap: 28px; align-items: start; margin-top: 22px; }
        h2 { color: #6b5ca5; font-size: 24px; font-weight: normal; margin-bottom: 16px; }
        h2 .brillo-h { display: inline-block; animation: girar 4s linear infinite; }
        @keyframes girar { 0%,100% { transform: rotate(0) scale(1); } 50% { transform: rotate(180deg) scale(1.25); } }

        /* Próxima cita destacada */
        .proxima {
            position: relative; overflow: hidden;
            background: white; border-radius: 26px; padding: 28px 30px; margin-bottom: 20px;
            box-shadow: 0 12px 34px rgba(168,159,212,.45);
            border: 2px solid #efe9fc; animation: entrar .8s ease both; animation-delay: .2s;
        }
        .proxima::before {
            content: '💜'; position: absolute; right: 18px; top: 10px; font-size: 64px; opacity: .12;
            animation: latido 2s ease-in-out infinite;
        }
        @keyframes latido { 0%,100% { transform: scale(1); } 50% { transform: scale(1.18); } }
        .etiqueta {
            display: inline-block; background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white;
            padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 12px;
        }
        .proxima h3 { color: #5b3a8f; font-size: 24px; font-weight: normal; }
        .proxima .det { color: #8a8a9a; font-size: 14px; margin-top: 8px; line-height: 1.8; }
        .proxima .det b { color: #6b5ca5; }
        .cuenta { display: flex; gap: 10px; margin-top: 18px; }
        .cuenta div {
            background: linear-gradient(160deg, #f8f4ff, #efe9fc); border-radius: 16px;
            padding: 10px 14px; text-align: center; min-width: 66px;
        }
        .cuenta strong { display: block; font-size: 24px; color: #6b5ca5; }
        .cuenta small { color: #9b8ec4; font-size: 11px; }

        /* Lista */
        .cita-card {
            background: white; border-radius: 20px; padding: 18px 24px; margin-bottom: 14px;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            border-left: 6px solid #c9c2ee; box-shadow: 0 6px 20px rgba(201,194,238,.35);
            animation: entrar .7s ease both; animation-delay: calc(var(--i) * .12s + .3s);
            transition: transform .3s, box-shadow .3s;
        }
        .cita-card:hover { transform: translateX(6px); box-shadow: 0 12px 28px rgba(168,159,212,.5); }
        .cita-card h4 { color: #6b5ca5; font-size: 16px; font-weight: normal; }
        .cita-card p { color: #8a8a9a; font-size: 13px; margin-top: 4px; }
        .badge { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #fff3cd; color: #856404; white-space: nowrap; }
        .enlace { display: inline-block; margin-top: 6px; color: #6b5ca5; font-size: 14px; font-weight: bold; text-decoration: none; }
        .enlace:hover { text-decoration: underline; }

        .vacio {
            text-align: center; padding: 46px 30px; background: white; border-radius: 26px; color: #9b8ec4;
            box-shadow: 0 10px 30px rgba(201,194,238,.4); animation: entrar .8s ease both;
        }
        .vacio .grande { font-size: 50px; display: inline-block; animation: flotar 3s ease-in-out infinite; }
        .vacio .btn-agendar { margin-top: 18px; background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white; }

        /* ---------- Calendario ---------- */
        aside { position: sticky; top: 20px; animation: entrar .8s ease both; animation-delay: .25s; }
        .cal {
            background: white; border-radius: 26px; padding: 22px;
            box-shadow: 0 14px 40px rgba(168,159,212,.5); border: 2px solid #efe9fc;
        }
        .cal-top {
            background: linear-gradient(135deg, #8b5fd0, #c08bf0); color: white;
            border-radius: 18px; padding: 14px 16px; margin-bottom: 14px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .cal-top strong { font-size: 18px; text-transform: capitalize; font-weight: normal; letter-spacing: .5px; }
        .cal-top button {
            background: rgba(255,255,255,.25); color: white; border: none; width: 32px; height: 32px;
            border-radius: 50%; cursor: pointer; font-size: 18px; font-weight: bold; transition: transform .2s, background .2s;
        }
        .cal-top button:hover { background: rgba(255,255,255,.45); transform: scale(1.15); }
        .grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
        .dow { font-size: 11px; color: #9b8ec4; font-weight: bold; padding-bottom: 4px; }
        .dia {
            position: relative; height: 46px; border-radius: 12px; font-size: 13px; color: #555;
            display: flex; align-items: flex-end; justify-content: center; padding-bottom: 5px;
            background: transparent; border: none; font-family: inherit;
        }
        .dia.off { visibility: hidden; }
        .dia.hoy { background: #f3f0fb; color: #6b5ca5; font-weight: bold; box-shadow: inset 0 0 0 2px #c9c2ee; }
        .dia.con { background: #f8f4ff; color: #6b5ca5; font-weight: bold; cursor: pointer; transition: transform .2s, background .2s; }
        .dia.con:hover { transform: scale(1.14); background: #e8e1fb; }
        .dia.activo { background: #e8e1fb; box-shadow: 0 0 0 2px #a89fd4; }
        .dia .cor { position: absolute; top: 2px; left: 50%; font-size: 17px; line-height: 1; transform: translateX(-50%); animation: cor 1.4s ease-in-out infinite; }
        @keyframes cor { 0%,100% { transform: translateX(-50%) scale(1); } 50% { transform: translateX(-50%) scale(1.3); } }
        .nota {
            margin-top: 14px; background: linear-gradient(135deg, #f8f4ff, #efe9fc); border-radius: 16px;
            padding: 14px 16px; color: #6b5ca5; font-size: 13px; line-height: 1.7; min-height: 62px; animation: entrar .5s ease both;
        }
        .nota.sin { color: #9b8ec4; text-align: center; }

        @media (max-width: 900px) {
            .cols { grid-template-columns: 1fr; }
            aside { position: static; }
            .stats { grid-template-columns: 1fr; }
            .hero { padding: 28px 24px; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

<div class="fondo" id="fondo"></div>

<nav>
    <x-logo />
    <div class="nav-links">
        <a href="{{ route('citas.create') }}">✦ Agendar cita</a>
        <a href="{{ route('citas.mias') }}">✦ Mis citas</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>

@php
    // Solo citas pendientes que aún no pasaron
    $proximas = $citas
        ->where('estado', 'pendiente')
        ->filter(fn ($c) => $c->inicio->copy()->addHour()->isFuture())
        ->sortBy(fn ($c) => $c->inicio)
        ->values();

    $siguiente   = $proximas->first();
    $completadas = $citas->where('estado', 'completada')->count();

    $eventos = $proximas->map(fn ($c) => [
        'fecha'     => $c->inicio->format('Y-m-d'),
        'hora'      => $c->inicio->format('h:i A'),
        'estilista' => $c->especialista->user->name,
    ])->values();
@endphp

<div class="container">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bienvenida --}}
    <div class="hero">
        <div>
            <h1>¡Hola, {{ auth()->user()->name }}! <span class="mano">👋</span> 💜</h1>
            <p>Bienvenida a tu espacio en Corazón Rebelde Nails Spa ✦ Aquí brillas tú</p>
        </div>
        <a href="{{ route('citas.create') }}" class="btn-agendar">✦ Agendar nueva cita</a>
    </div>

    {{-- Estadísticas --}}
    <div class="stats">
        <div class="stat"><div class="ico">📅</div><div class="num">{{ $proximas->count() }}</div><div class="lab">Citas próximas</div></div>
        <div class="stat"><div class="ico">💅</div><div class="num">{{ $completadas }}</div><div class="lab">Citas disfrutadas</div></div>
        <div class="stat"><div class="ico">💜</div><div class="num">{{ $citas->count() }}</div><div class="lab">Total de visitas</div></div>
    </div>

    <div class="cols">
        {{-- Columna izquierda --}}
        <div>
            <h2><span class="brillo-h">✦</span> Tu próxima cita</h2>

            @if($siguiente)
                <div class="proxima">
                    <span class="etiqueta">✨ Te esperamos</span>
                    <h3>{{ $siguiente->servicio->nombre }}</h3>
                    <div class="det">
                        📅 <b>{{ $siguiente->inicio->translatedFormat('l d \d\e F') }}</b> a las <b>{{ $siguiente->inicio->format('h:i A') }}</b><br>
                        💅 Con <b>{{ $siguiente->especialista->user->name }}</b><br>
                        📍 Corazón Rebelde Nails Spa
                    </div>
                    <div class="cuenta" data-fecha="{{ $siguiente->inicio->format('Y-m-d\TH:i:s') }}">
                        <div><strong id="c-d">0</strong><small>días</small></div>
                        <div><strong id="c-h">0</strong><small>horas</small></div>
                        <div><strong id="c-m">0</strong><small>min</small></div>
                    </div>
                </div>

                @if($proximas->count() > 1)
                    <h2 style="margin-top:26px;"><span class="brillo-h">✦</span> Después</h2>
                    @foreach($proximas->skip(1) as $cita)
                        <div class="cita-card" style="--i: {{ $loop->index }}">
                            <div>
                                <h4>{{ $cita->servicio->nombre }}</h4>
                                <p>📅 {{ $cita->inicio->format('d/m/Y') }} — 🕐 {{ $cita->inicio->format('h:i A') }}</p>
                                <p>💅 {{ $cita->especialista->user->name }}</p>
                            </div>
                            <span class="badge">Pendiente</span>
                        </div>
                    @endforeach
                @endif

                <a href="{{ route('citas.mias') }}" class="enlace">✦ Reagendar o cancelar mis citas →</a>
            @else
                <div class="vacio">
                    <div class="grande">💅</div>
                    <p style="margin-top:10px;">Aún no tienes citas pendientes.<br>¡Es momento de consentirte! 💜</p>
                    <a href="{{ route('citas.create') }}" class="btn-agendar">✦ Agendar una cita</a>
                </div>
            @endif
        </div>

        {{-- Calendario --}}
        <aside>
            <div class="cal">
                <div class="cal-top">
                    <button type="button" id="prev">‹</button>
                    <strong id="titulo"></strong>
                    <button type="button" id="next">›</button>
                </div>
                <div class="grid" id="grid"></div>
                <div class="nota sin" id="nota">Toca un día con 💜 para ver tu recordatorio.</div>
            </div>
        </aside>
    </div>
</div>

<script>
    /* ---------- Uñas y corazones flotando ---------- */
    (function () {
        const fondo = document.getElementById('fondo');
        const corazones = ['💜', '💗', '💖', '🤍'];
        for (let i = 0; i < 28; i++) {
            const flota = document.createElement('div');
            flota.className = 'flota';
            const interior = document.createElement('span');
            const t = Math.random();
            const c = corazones[Math.floor(Math.random() * corazones.length)];

            if (t < 0.5)       interior.textContent = '💅';
            else if (t < 0.75) interior.innerHTML = `<span class="combo">${c}<span class="una">💅</span></span>`;
            else               interior.textContent = c;

            flota.style.left = Math.random() * 100 + '%';
            flota.style.fontSize = (18 + Math.random() * 28) + 'px';
            flota.style.animationDuration = (11 + Math.random() * 14) + 's';
            flota.style.animationDelay = (-Math.random() * 25) + 's';
            interior.style.animationDuration = (3 + Math.random() * 3) + 's';
            interior.style.animationDelay = (-Math.random() * 4) + 's';
            flota.appendChild(interior);
            fondo.appendChild(flota);
        }
    })();

    /* ---------- Cuenta regresiva ---------- */
    (function () {
        const box = document.querySelector('.cuenta');
        if (!box) return;
        const destino = new Date(box.dataset.fecha);
        const d = document.getElementById('c-d'), h = document.getElementById('c-h'), m = document.getElementById('c-m');
        function tick() {
            let diff = Math.max(0, destino - new Date());
            d.textContent = Math.floor(diff / 86400000);
            h.textContent = Math.floor(diff / 3600000) % 24;
            m.textContent = Math.floor(diff / 60000) % 60;
        }
        tick(); setInterval(tick, 30000);
    })();

    /* ---------- Calendario ---------- */
    const eventos = @json($eventos);
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const dows = ['L','M','M','J','V','S','D'];
    const grid = document.getElementById('grid');
    const titulo = document.getElementById('titulo');
    const nota = document.getElementById('nota');
    const pad = n => String(n).padStart(2, '0');
    const key = (y, m, d) => `${y}-${pad(m + 1)}-${pad(d)}`;

    const base = eventos.length ? new Date(eventos[0].fecha + 'T00:00:00') : new Date();
    let anio = base.getFullYear(), mes = base.getMonth();
    let sel = eventos.length ? eventos[0].fecha : null;

    function mostrarNota() {
        const lista = sel ? eventos.filter(e => e.fecha === sel) : [];
        nota.style.animation = 'none'; nota.offsetHeight; nota.style.animation = '';
        if (!lista.length) { nota.className = 'nota sin'; nota.innerHTML = 'Toca un día con 💜 para ver tu recordatorio.'; return; }
        const d = new Date(sel + 'T00:00:00');
        const dia = `${d.getDate()} de ${meses[d.getMonth()]}`;
        nota.className = 'nota';
        nota.innerHTML = lista.map(e =>
            `💜 Recuerda tu cita para el día <strong>${dia}</strong> a las <strong>${e.hora}</strong> con <strong>${e.estilista}</strong>. ¡No faltes, te esperamos! 💜`
        ).join('<br><br>');
    }

    function dibujar() {
        titulo.textContent = `${meses[mes]} ${anio}`;
        grid.innerHTML = '';
        dows.forEach(x => { const e = document.createElement('div'); e.className = 'dow'; e.textContent = x; grid.appendChild(e); });

        const primero = (new Date(anio, mes, 1).getDay() + 6) % 7;
        const total = new Date(anio, mes + 1, 0).getDate();
        const hoy = new Date();
        const hoyK = key(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());

        for (let i = 0; i < primero; i++) { const v = document.createElement('div'); v.className = 'dia off'; grid.appendChild(v); }

        for (let d = 1; d <= total; d++) {
            const k = key(anio, mes, d);
            const tiene = eventos.some(e => e.fecha === k);
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'dia' + (k === hoyK ? ' hoy' : '') + (tiene ? ' con' : '') + (k === sel ? ' activo' : '');
            b.innerHTML = (tiene ? '<span class="cor">💜</span>' : '') + d;
            if (tiene) b.addEventListener('click', () => { sel = k; dibujar(); mostrarNota(); });
            grid.appendChild(b);
        }
    }

    document.getElementById('prev').addEventListener('click', () => { mes--; if (mes < 0) { mes = 11; anio--; } dibujar(); });
    document.getElementById('next').addEventListener('click', () => { mes++; if (mes > 11) { mes = 0; anio++; } dibujar(); });
    dibujar(); mostrarNota();
</script>
</body>
</html>