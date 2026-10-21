<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - Corazón Rebelde</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            background: linear-gradient(160deg, #f5f3ff 0%, #ece6fb 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ---------- Corazones y uñas flotando de fondo ---------- */
        .fondo { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }
        .fondo .flota {
            position: absolute; bottom: -60px; opacity: 0;
            animation: subir linear infinite;
        }
        .fondo .flota > span {
            display: inline-block;
            animation: vaiven ease-in-out infinite;
        }
        .fondo .combo { position: relative; display: inline-block; line-height: 1; }
        .fondo .combo .una {
            position: absolute; top: -.55em; right: -.45em; font-size: .62em;
            transform: rotate(18deg);
        }
        @keyframes subir {
            0%   { transform: translateY(0); opacity: 0; }
            10%  { opacity: .75; }
            90%  { opacity: .5; }
            100% { transform: translateY(-115vh); opacity: 0; }
        }
        @keyframes vaiven {
            0%, 100% { transform: translateX(-14px) rotate(-14deg); }
            50%      { transform: translateX(14px) rotate(14deg); }
        }

        /* ---------- Menú ---------- */
        nav {
            position: relative; z-index: 2;
            background: linear-gradient(135deg, #c9c2ee, #b4aae0);
            padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 4px 20px rgba(107,92,165,0.25);
        }
        nav img { width: 130px; border-radius: 8px; }
        .nav-links { display: flex; align-items: center; gap: 22px; }
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
            padding: 10px 22px; border-radius: 25px; font-weight: bold; cursor: pointer;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-logout:hover { transform: scale(1.06); box-shadow: 0 6px 16px rgba(107,92,165,.3); }

        /* ---------- Distribución ---------- */
        .container {
            position: relative; z-index: 1;
            max-width: 1100px; margin: 36px auto 60px; padding: 0 20px;
            display: grid; grid-template-columns: 1fr 360px; gap: 30px; align-items: start;
        }
        @media (max-width: 880px) { .container { grid-template-columns: 1fr; } }

        .alert-success, .alert-error {
            padding: 14px 20px; border-radius: 14px; margin-bottom: 20px;
            font-size: 14px; font-weight: bold; animation: entrar .6s ease both;
        }
        .alert-success { background: #d1f5e0; color: #276749; }
        .alert-error   { background: #ffe0e0; color: #c0392b; }

        h2 {
            color: #6b5ca5; font-size: 26px; font-weight: normal; margin-bottom: 22px;
            animation: entrar .6s ease both;
        }
        h2 .brillo { display: inline-block; animation: girar 4s linear infinite; }
        @keyframes girar { 0%,100% { transform: rotate(0) scale(1); } 50% { transform: rotate(180deg) scale(1.25); } }

        @keyframes entrar {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---------- Tarjetas de citas ---------- */
        .cita-card {
            background: white; border-radius: 22px; padding: 24px 28px; margin-bottom: 18px;
            display: flex; align-items: center; justify-content: space-between; gap: 16px;
            box-shadow: 0 6px 24px rgba(201,194,238,0.35);
            border-left: 6px solid #c9c2ee;
            animation: entrar .7s ease both;
            animation-delay: calc(var(--i) * .15s);
            transition: transform .3s, box-shadow .3s;
        }
        .cita-card:hover { transform: translateY(-5px) scale(1.01); box-shadow: 0 14px 34px rgba(168,159,212,0.5); }
        .cita-info h3 { color: #6b5ca5; font-size: 18px; font-weight: normal; }
        .cita-info p { color: #8a8a9a; font-size: 13px; margin-top: 6px; }
        .cita-info .estilista { color: #8b7bc4; font-weight: bold; }

        .lado-derecho { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
        .badge { padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; white-space: nowrap; }
        .pendiente { background: #fff3cd; color: #856404; animation: latido 2.2s ease-in-out infinite; }
        .completada { background: #d1f5e0; color: #276749; }
        .cancelada { background: #ffe0e0; color: #c0392b; }
        @keyframes latido { 0%,100% { transform: scale(1); } 50% { transform: scale(1.07); } }

        .btn-agendar, .btn-reagendar, .btn-cancelar {
            display: inline-block; text-decoration: none; border: none; cursor: pointer;
            font-weight: bold; border-radius: 20px; padding: 10px 18px; font-size: 12px;
            font-family: inherit; transition: transform .2s, box-shadow .2s;
        }
        .btn-agendar, .btn-reagendar { background: linear-gradient(135deg, #c9c2ee, #a89fd4); color: white; }
        .btn-cancelar { background: #ffe0e0; color: #c0392b; }
        .btn-agendar:hover, .btn-reagendar:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(168,159,212,.6); }
        .btn-cancelar:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(240,128,128,.4); }
        .btn-agendar { margin-top: 16px; padding: 13px 30px; border-radius: 25px; font-size: 14px; }

        .empty {
            text-align: center; padding: 50px 30px; color: #9b8ec4;
            background: white; border-radius: 22px; box-shadow: 0 6px 24px rgba(201,194,238,0.35);
            animation: entrar .7s ease both;
        }
        .empty .grande { font-size: 44px; display: inline-block; animation: flotar 3s ease-in-out infinite; }
        @keyframes flotar { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        /* ---------- Columna derecha ---------- */
        aside { position: sticky; top: 20px; animation: entrar .8s ease both; animation-delay: .2s; }

        /* Muñeca */
        .guia { display: flex; align-items: flex-end; gap: 4px; margin-bottom: -6px; position: relative; z-index: 2; }
        .muneca { width: 130px; flex-shrink: 0; animation: flotar 3.2s ease-in-out infinite; filter: drop-shadow(0 8px 10px rgba(107,92,165,.25)); }
        .muneca .brazo { transform-origin: 80px 100px; animation: senalar 1.6s ease-in-out infinite; }
        .muneca .parpado { transform-origin: center; animation: parpadeo 4s infinite; }
        .muneca .mano-izq { transform-origin: 40px 100px; animation: saludo 2.4s ease-in-out infinite; }
        @keyframes senalar { 0%,100% { transform: rotate(-10deg); } 50% { transform: rotate(14deg); } }
        @keyframes saludo { 0%,100% { transform: rotate(0deg); } 50% { transform: rotate(-8deg); } }
        @keyframes parpadeo { 0%,92%,100% { transform: scaleY(1); } 96% { transform: scaleY(0.1); } }

        .burbuja {
            background: white; color: #6b5ca5; border-radius: 18px 18px 18px 4px;
            padding: 12px 16px; font-size: 13px; line-height: 1.5; margin-bottom: 40px;
            box-shadow: 0 6px 18px rgba(201,194,238,.5); position: relative;
            animation: rebote 3s ease-in-out infinite;
        }
        @keyframes rebote { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }

        /* Calendario */
        .cal {
            background: white; border-radius: 24px; padding: 22px;
            box-shadow: 0 10px 34px rgba(201,194,238,0.5);
            border: 2px solid #efe9fc;
        }
        .cal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .cal-head strong { color: #6b5ca5; font-size: 17px; text-transform: capitalize; }
        .cal-head button {
            background: #f3f0fb; color: #6b5ca5; border: none; width: 34px; height: 34px;
            border-radius: 50%; cursor: pointer; font-size: 18px; font-weight: bold;
            transition: transform .2s, background .2s;
        }
        .cal-head button:hover { background: #e2d9f8; transform: scale(1.15); }
        .cal-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
        .cal-dow { font-size: 11px; color: #9b8ec4; font-weight: bold; padding-bottom: 4px; }
        .dia {
            position: relative; height: 46px; border-radius: 12px; font-size: 13px; color: #555;
            display: flex; align-items: flex-end; justify-content: center; padding-bottom: 5px;
            background: transparent; border: none; font-family: inherit;
        }
        .dia.vacio-dia { visibility: hidden; }
        .dia.hoy { background: #f3f0fb; color: #6b5ca5; font-weight: bold; }
        .dia.con-cita { background: #f8f4ff; cursor: pointer; color: #6b5ca5; font-weight: bold; transition: transform .2s, background .2s; }
        .dia.con-cita:hover { transform: scale(1.12); background: #e8e1fb; }
        .dia.activo { background: #e8e1fb; box-shadow: 0 0 0 2px #c9c2ee; }
        .dia .corazon {
            position: absolute; top: 2px; left: 50%; font-size: 16px; line-height: 1;
            transform: translateX(-50%); animation: corazon 1.4s ease-in-out infinite;
        }
        @keyframes corazon { 0%,100% { transform: translateX(-50%) scale(1); } 50% { transform: translateX(-50%) scale(1.3); } }

        .nota {
            margin-top: 16px; background: linear-gradient(135deg, #f8f4ff, #efe9fc);
            border-radius: 16px; padding: 14px 16px; color: #6b5ca5; font-size: 13px; line-height: 1.7; min-height: 64px;
            animation: entrar .5s ease both;
        }
        .nota.sin { color: #9b8ec4; text-align: center; }

        @media (max-width: 700px) {
            .cita-card { flex-direction: column; align-items: flex-start; }
            .lado-derecho { width: 100%; justify-content: flex-start; }
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
        <a href="{{ route('cliente.dashboard') }}">✦ Inicio</a>
        <a href="{{ route('citas.create') }}">✦ Agendar cita</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container">

    {{-- ===== Columna izquierda: citas ===== --}}
    <div>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <h2><span class="brillo">✦</span> Mis citas</h2>

        @if($citas->isEmpty())
            <div class="empty">
                <div class="grande">💜</div>
                <p style="margin-top:10px;">No tienes citas pendientes</p>
                <a href="{{ route('citas.create') }}" class="btn-agendar">✦ Agendar una cita</a>
            </div>
        @else
            @foreach($citas as $cita)
                <div class="cita-card" style="--i: {{ $loop->index }}">
                    <div class="cita-info">
                        <h3>{{ $cita->servicio->nombre }}</h3>
                        <p>📅 {{ $cita->inicio->format('d/m/Y') }} &nbsp;—&nbsp; 🕐 {{ $cita->inicio->format('h:i A') }}</p>
                        <p>💅 <span class="estilista">{{ $cita->especialista->user->name }}</span></p>
                        @if($cita->notas)
                            <p>📝 {{ $cita->notas }}</p>
                        @endif
                    </div>

                    <div class="lado-derecho">
                        <span class="badge pendiente">Pendiente</span>

                        <a href="{{ route('citas.edit', $cita) }}" class="btn-reagendar">Reagendar</a>

                        <form method="POST" action="{{ route('citas.cancelar', $cita) }}"
                              onsubmit="return confirm('¿Seguro que deseas cancelar esta cita?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-cancelar">Cancelar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- ===== Columna derecha: muñeca + calendario ===== --}}
    <aside>
        <div class="guia">
            {{-- Muñeca animada (SVG) --}}
            <svg class="muneca" viewBox="0 0 130 170" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <!-- pelo atrás -->
                <ellipse cx="65" cy="58" rx="38" ry="42" fill="#6b5ca5"/>
                <!-- vestido -->
                <path d="M32 170 Q38 100 65 94 Q92 100 98 170 Z" fill="#c9c2ee"/>
                <path d="M52 100 Q65 112 78 100" stroke="#a89fd4" stroke-width="3" fill="none" stroke-linecap="round"/>
                <text x="56" y="140" font-size="18">💜</text>
                <!-- brazo izquierdo saludando -->
                <g class="mano-izq">
                    <path d="M42 104 Q24 112 20 90" stroke="#ffe0cf" stroke-width="9" fill="none" stroke-linecap="round"/>
                    <circle cx="20" cy="86" r="7" fill="#ffe0cf"/>
                </g>
                <!-- brazo derecho señalando -->
                <g class="brazo">
                    <path d="M82 104 L116 138" stroke="#ffe0cf" stroke-width="9" stroke-linecap="round"/>
                    <circle cx="118" cy="141" r="7" fill="#ffe0cf"/>
                    <circle cx="118" cy="141" r="11" fill="none" stroke="#f9a8d4" stroke-width="2" opacity=".7"/>
                </g>
                <!-- cabeza -->
                <circle cx="65" cy="62" r="28" fill="#ffe3d4"/>
                <!-- flequillo -->
                <path d="M37 58 Q42 30 65 32 Q88 30 93 58 Q80 44 65 46 Q50 44 37 58 Z" fill="#6b5ca5"/>
                <!-- moño de corazón -->
                <text x="76" y="36" font-size="16">💗</text>
                <!-- ojos -->
                <g class="parpado">
                    <ellipse cx="54" cy="64" rx="3.5" ry="5" fill="#3b2f63"/>
                    <ellipse cx="76" cy="64" rx="3.5" ry="5" fill="#3b2f63"/>
                    <circle cx="55.2" cy="62" r="1.2" fill="white"/>
                    <circle cx="77.2" cy="62" r="1.2" fill="white"/>
                </g>
                <!-- mejillas y sonrisa -->
                <circle cx="47" cy="75" r="5" fill="#ffb3c6" opacity=".7"/>
                <circle cx="83" cy="75" r="5" fill="#ffb3c6" opacity=".7"/>
                <path d="M58 78 Q65 86 72 78" stroke="#c0568a" stroke-width="2.5" fill="none" stroke-linecap="round"/>
            </svg>

            <div class="burbuja">
                @if($citas->isEmpty())
                    ¡Hola! 💜 Aún no tienes citas. ¡Agenda la primera y aparecerá aquí en tu calendario!
                @else
                    ¡Mira aquí abajo! 👇 Toca los 💜 de tu calendario para ver el recordatorio de tu cita.
                @endif
            </div>
        </div>

        <div class="cal">
            <div class="cal-head">
                <button type="button" id="prev">‹</button>
                <strong id="titulo"></strong>
                <button type="button" id="next">›</button>
            </div>
            <div class="cal-grid" id="grid"></div>
            <div class="nota sin" id="nota">Toca un día con 💜 para ver el recordatorio de tu cita.</div>
        </div>
    </aside>
</div>

<script>
    /* ---------- Corazones y uñas flotando ---------- */
    (function () {
        const fondo = document.getElementById('fondo');
        const corazones = ['💜', '💗', '💖', '🤍'];
        const total = 24; // cantidad de elementos flotando

        for (let i = 0; i < total; i++) {
            const flota = document.createElement('div');
            flota.className = 'flota';

            const interior = document.createElement('span');
            const tipo = Math.random();
            const corazon = corazones[Math.floor(Math.random() * corazones.length)];

            if (tipo < 0.4) {
                // Corazón con uña encima
                interior.innerHTML = `<span class="combo">${corazon}<span class="una">💅</span></span>`;
            } else if (tipo < 0.7) {
                // Solo uñas
                interior.textContent = '💅';
            } else {
                // Solo corazón
                interior.textContent = corazon;
            }

            flota.style.left = Math.random() * 100 + '%';
            flota.style.fontSize = (16 + Math.random() * 26) + 'px';
            flota.style.animationDuration = (11 + Math.random() * 14) + 's';
            flota.style.animationDelay = (-Math.random() * 25) + 's';

            interior.style.animationDuration = (3 + Math.random() * 3) + 's';
            interior.style.animationDelay = (-Math.random() * 4) + 's';

            flota.appendChild(interior);
            fondo.appendChild(flota);
        }
    })();

    /* ---------- Calendario ---------- */
    const eventos = @json($eventos ?? []);
    const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
    const dows  = ['L','M','M','J','V','S','D'];

    const grid = document.getElementById('grid');
    const titulo = document.getElementById('titulo');
    const nota = document.getElementById('nota');

    const pad = n => String(n).padStart(2, '0');
    const key = (y, m, d) => `${y}-${pad(m + 1)}-${pad(d)}`;

    const base = eventos.length ? new Date(eventos[0].fecha + 'T00:00:00') : new Date();
    let anio = base.getFullYear();
    let mes  = base.getMonth();
    let seleccionado = eventos.length ? eventos[0].fecha : null;

    function textoNota(fecha) {
        const lista = eventos.filter(e => e.fecha === fecha);
        if (!lista.length) return null;
        const d = new Date(fecha + 'T00:00:00');
        const dia = `${d.getDate()} de ${meses[d.getMonth()]}`;
        return lista.map(e =>
            `💜 Recuerda tu cita para el día <strong>${dia}</strong> a las <strong>${e.hora}</strong> con <strong>${e.estilista}</strong>. ¡No faltes, te esperamos! 💜`
        ).join('<br><br>');
    }

    function mostrarNota() {
        const t = seleccionado ? textoNota(seleccionado) : null;
        nota.style.animation = 'none'; nota.offsetHeight; nota.style.animation = '';
        if (t) { nota.className = 'nota'; nota.innerHTML = t; }
        else   { nota.className = 'nota sin'; nota.innerHTML = 'Toca un día con 💜 para ver el recordatorio de tu cita.'; }
    }

    function dibujar() {
        titulo.textContent = `${meses[mes]} ${anio}`;
        grid.innerHTML = '';

        dows.forEach(d => {
            const el = document.createElement('div');
            el.className = 'cal-dow';
            el.textContent = d;
            grid.appendChild(el);
        });

        const primerDia = (new Date(anio, mes, 1).getDay() + 6) % 7;
        const diasMes = new Date(anio, mes + 1, 0).getDate();
        const hoy = new Date();
        const hoyKey = key(hoy.getFullYear(), hoy.getMonth(), hoy.getDate());

        for (let i = 0; i < primerDia; i++) {
            const v = document.createElement('div');
            v.className = 'dia vacio-dia';
            grid.appendChild(v);
        }

        for (let d = 1; d <= diasMes; d++) {
            const k = key(anio, mes, d);
            const tiene = eventos.some(e => e.fecha === k);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'dia' + (k === hoyKey ? ' hoy' : '') + (tiene ? ' con-cita' : '') + (k === seleccionado ? ' activo' : '');
            btn.innerHTML = (tiene ? '<span class="corazon">💜</span>' : '') + d;
            if (tiene) {
                btn.addEventListener('click', () => { seleccionado = k; dibujar(); mostrarNota(); });
            }
            grid.appendChild(btn);
        }
    }

    document.getElementById('prev').addEventListener('click', () => { mes--; if (mes < 0) { mes = 11; anio--; } dibujar(); });
    document.getElementById('next').addEventListener('click', () => { mes++; if (mes > 11) { mes = 0; anio++; } dibujar(); });

    dibujar();
    mostrarNota();
</script>
</body>
</html>