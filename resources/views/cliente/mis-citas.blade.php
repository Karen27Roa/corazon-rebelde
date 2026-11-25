<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas - Corazón Rebelde</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Georgia', serif;
            min-height: 100vh; overflow-x: hidden;
            background:
                radial-gradient(circle at 8% 0%, #e9defc 0%, transparent 42%),
                radial-gradient(circle at 92% 25%, #ffe3f3 0%, transparent 38%),
                linear-gradient(160deg, #f5f3ff 0%, #ece6fb 100%);
            background-attachment: fixed;
        }

        /* ---------- Uñas y corazones flotando ---------- */
        .fondo { position: fixed; inset: 0; pointer-events: none; overflow: hidden; z-index: 0; }
        .flota { position: absolute; bottom: -70px; opacity: 0; animation: subir linear infinite; }
        .flota > span { display: inline-block; animation: vaiven ease-in-out infinite; filter: drop-shadow(0 4px 6px rgba(107,92,165,.25)); }
        .combo { position: relative; display: inline-block; line-height: 1; }
        .combo .una { position: absolute; top: -.55em; right: -.45em; font-size: .62em; transform: rotate(18deg); }
        @keyframes subir { 0% { transform: translateY(0); opacity: 0; } 10% { opacity: .75; } 90% { opacity: .45; } 100% { transform: translateY(-118vh); opacity: 0; } }
        @keyframes vaiven { 0%,100% { transform: translateX(-16px) rotate(-16deg); } 50% { transform: translateX(16px) rotate(16deg); } }

        /* ---------- Menú ---------- */
        nav {
            position: relative; z-index: 5;
            background: linear-gradient(135deg, #c9c2ee, #b4aae0);
            padding: 10px 32px; display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px; box-shadow: 0 6px 24px rgba(107,92,165,.3);
        }
        .nav-links { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: bold; position: relative; transition: transform .2s; }
        .nav-links a::after { content: ''; position: absolute; left: 0; bottom: -4px; width: 0; height: 2px; background: white; transition: width .3s; }
        .nav-links a:hover { transform: translateY(-2px); }
        .nav-links a:hover::after { width: 100%; }
        .btn-logout {
            background: white; color: #6b5ca5; border: none; padding: 10px 22px; border-radius: 25px;
            font-weight: bold; cursor: pointer; font-size: 14px; transition: transform .2s, box-shadow .2s;
        }
        .btn-logout:hover { transform: scale(1.06); box-shadow: 0 6px 16px rgba(107,92,165,.3); }

        .container { position: relative; z-index: 2; max-width: 1120px; margin: 0 auto 60px; padding: 0 20px; }

        @keyframes entrar { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: none; } }

        .alert-success, .alert-error { padding: 14px 20px; border-radius: 14px; margin: 26px 0 0; font-size: 14px; font-weight: bold; animation: entrar .6s ease both; }
        .alert-success { background: #d1f5e0; color: #276749; }
        .alert-error   { background: #ffe0e0; color: #c0392b; }

        /* ---------- Banner con el osito ---------- */
        .banner {
            position: relative; margin-top: 100px; min-height: 190px;
            padding: 40px 310px 40px 42px; color: white; border-radius: 30px;
            background: linear-gradient(135deg, #3f2470 0%, #6a3fb5 55%, #a76bf0 100%);
            background-size: 200% 200%; animation: entrar .8s ease both, mover 12s ease infinite;
            box-shadow: 0 20px 48px rgba(91,58,143,.4);
        }
        @keyframes mover { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        .banner-bg { position: absolute; inset: 0; border-radius: 30px; overflow: hidden; pointer-events: none; }
        .banner-bg::before, .banner-bg::after { content: ''; position: absolute; border-radius: 50%; background: rgba(255,255,255,.09); animation: respira 9s ease-in-out infinite; }
        .banner-bg::before { width: 280px; height: 280px; top: -110px; left: -70px; }
        .banner-bg::after  { width: 200px; height: 200px; bottom: -90px; left: 38%; animation-delay: 3s; }
        @keyframes respira { 0%,100% { transform: scale(1); } 50% { transform: scale(1.14); } }
        .estrella { position: absolute; color: #ffe680; text-shadow: 0 0 10px #ffd21f; animation: titila 2.4s ease-in-out infinite; }
        .e1 { top: 16%; left: 46%; font-size: 20px; } .e2 { bottom: 18%; left: 8%; font-size: 15px; animation-delay: .9s; } .e3 { top: 12%; right: 38%; font-size: 24px; animation-delay: 1.6s; }
        @keyframes titila { 0%,100% { opacity: .2; transform: scale(.6) rotate(0); } 50% { opacity: 1; transform: scale(1.3) rotate(45deg); } }

        .banner h1 { position: relative; font-family: 'Dancing Script', 'Brush Script MT', cursive; font-size: clamp(2.4rem, 4vw, 3.4rem); font-weight: 700; line-height: 1.05; text-shadow: 0 4px 18px rgba(0,0,0,.3); }
        .banner h1 span {
            background: linear-gradient(100deg, #ffd21f, #fff3b0, #ffd21f);
            -webkit-background-clip: text; background-clip: text; color: transparent; -webkit-text-fill-color: transparent;
        }
        .banner p { position: relative; margin-top: 10px; font-size: 15px; line-height: 1.6; opacity: .94; max-width: 430px; }
        .chips { position: relative; display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
        .chip { background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.4); backdrop-filter: blur(4px); padding: 8px 15px; border-radius: 30px; font-size: 13px; font-weight: bold; }

        .oso { position: absolute; right: 36px; bottom: -4px; width: 262px; z-index: 3; pointer-events: none; }
        .oso .glow {
            position: absolute; inset: -12%; border-radius: 50%; z-index: 0;
            background: radial-gradient(circle, rgba(255,255,255,.9) 0%, rgba(255,230,250,.5) 42%, transparent 70%);
            animation: pulso 4s ease-in-out infinite;
        }
        @keyframes pulso { 0%,100% { transform: scale(1); opacity: .9; } 50% { transform: scale(1.08); opacity: 1; } }
        .oso img {
            position: relative; z-index: 1; width: 100%; display: block; transform-origin: 50% 95%;
            filter: drop-shadow(0 18px 18px rgba(30,15,60,.45)); animation: flotarOso 4s ease-in-out infinite;
        }
        @keyframes flotarOso { 0%,100% { transform: translateY(0) rotate(-1.5deg); } 50% { transform: translateY(-12px) rotate(1.8deg); } }
        .oso .lat { position: absolute; z-index: 2; animation: subeLat 3s ease-in-out infinite; }
        .l1 { top: 4%; left: -4%; font-size: 24px; } .l2 { top: -6%; right: 6%; font-size: 20px; animation-delay: 1s; } .l3 { top: 30%; right: -6%; font-size: 18px; animation-delay: 2s; }
        @keyframes subeLat { 0%,100% { transform: translateY(0) scale(1); opacity: .9; } 50% { transform: translateY(-14px) scale(1.2); opacity: 1; } }

        /* ---------- Columnas ---------- */
        .cols { display: grid; grid-template-columns: 1fr 370px; gap: 28px; align-items: start; margin-top: 34px; }
        h2 { color: #6b5ca5; font-size: 24px; font-weight: normal; margin-bottom: 16px; }
        h2 .brillo-h { display: inline-block; animation: girar 4s linear infinite; }
        @keyframes girar { 0%,100% { transform: rotate(0) scale(1); } 50% { transform: rotate(180deg) scale(1.25); } }

        /* ---------- Tarjetas de cita ---------- */
        .cita-card {
            display: flex; align-items: center; gap: 20px;
            background: white; border-radius: 24px; padding: 20px 24px; margin-bottom: 18px;
            border: 2px solid #f1ebfc; box-shadow: 0 8px 28px rgba(201,194,238,.4);
            animation: entrar .7s ease both; animation-delay: calc(var(--i) * .15s + .2s);
            transition: transform .3s, box-shadow .3s, border-color .3s;
        }
        .cita-card:hover { transform: translateY(-5px); box-shadow: 0 16px 38px rgba(168,159,212,.55); border-color: #d8cff5; }

        .fecha {
            flex-shrink: 0; width: 82px; text-align: center; border-radius: 20px; overflow: hidden;
            background: white; box-shadow: 0 8px 20px rgba(139,95,208,.3); border: 2px solid #e6dcf9;
        }
        .fecha .mes { display: block; background: linear-gradient(135deg, #8b5fd0, #c08bf0); color: white; font-size: 12px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; padding: 6px 0; }
        .fecha strong { display: block; font-size: 34px; color: #5b3a8f; line-height: 1.15; padding-top: 4px; }
        .fecha .dow { display: block; color: #9b8ec4; font-size: 12px; text-transform: capitalize; padding-bottom: 7px; }

        .info { flex: 1; min-width: 0; }
        .info h3 { color: #5b3a8f; font-size: 19px; font-weight: normal; }
        .info p { color: #8a8a9a; font-size: 13px; margin-top: 6px; }
        .info .estilista { color: #8b5fd0; font-weight: bold; }
        .faltan { display: inline-block; margin-top: 9px; background: linear-gradient(135deg, #f8f4ff, #efe9fc); color: #6b3fb0; font-size: 12px; font-weight: bold; padding: 5px 12px; border-radius: 20px; animation: latido 2.4s ease-in-out infinite; }
        @keyframes latido { 0%,100% { transform: scale(1); } 50% { transform: scale(1.07); } }

        .acciones { display: flex; flex-direction: column; align-items: stretch; gap: 8px; min-width: 120px; }
        .badge { text-align: center; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: bold; background: #fff3cd; color: #856404; }
        .btn-reagendar, .btn-cancelar {
            display: block; text-align: center; text-decoration: none; border: none; cursor: pointer; width: 100%;
            font-weight: bold; border-radius: 20px; padding: 9px 16px; font-size: 12px; font-family: inherit; transition: transform .2s, box-shadow .2s;
        }
        .btn-reagendar { background: linear-gradient(135deg, #b085ee, #8b5fd0); color: white; }
        .btn-cancelar { background: #ffe9e9; color: #c0392b; }
        .btn-reagendar:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(139,95,208,.45); }
        .btn-cancelar:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(240,128,128,.4); }

        .vacio {
            text-align: center; padding: 50px 30px; background: white; border-radius: 26px; color: #9b8ec4;
            box-shadow: 0 10px 30px rgba(201,194,238,.4); animation: entrar .8s ease both .2s;
        }
        .vacio .grande { font-size: 52px; display: inline-block; animation: flotar 3s ease-in-out infinite; }
        @keyframes flotar { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .btn-agendar {
            position: relative; overflow: hidden; display: inline-block; margin-top: 18px; text-decoration: none;
            background: linear-gradient(135deg, #b085ee, #7a4cc4); color: white; padding: 13px 30px; border-radius: 28px;
            font-weight: bold; font-size: 14px; box-shadow: 0 8px 22px rgba(122,76,196,.4); transition: transform .25s;
        }
        .btn-agendar::before { content: ''; position: absolute; top: 0; left: -80%; width: 50%; height: 100%; background: linear-gradient(120deg, transparent, rgba(255,255,255,.7), transparent); transform: skewX(-20deg); animation: brillo 3s ease-in-out infinite; }
        @keyframes brillo { 0% { left: -80%; } 60%, 100% { left: 140%; } }
        .btn-agendar:hover { transform: translateY(-3px) scale(1.04); }

        /* ---------- Calendario ---------- */
        aside { position: sticky; top: 20px; animation: entrar .8s ease both .3s; }
        .cal { background: white; border-radius: 26px; padding: 22px; box-shadow: 0 14px 40px rgba(168,159,212,.5); border: 2px solid #efe9fc; }
        .cal-top {
            background: linear-gradient(135deg, #8b5fd0, #c08bf0); color: white; border-radius: 18px; padding: 14px 16px; margin-bottom: 14px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .cal-top strong { font-size: 18px; text-transform: capitalize; font-weight: normal; letter-spacing: .5px; }
        .cal-top button { background: rgba(255,255,255,.25); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 18px; font-weight: bold; transition: transform .2s, background .2s; }
        .cal-top button:hover { background: rgba(255,255,255,.45); transform: scale(1.15); }
        .grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; text-align: center; }
        .dow-h { font-size: 11px; color: #9b8ec4; font-weight: bold; padding-bottom: 4px; }
        .dia {
            position: relative; height: 46px; border-radius: 12px; font-size: 13px; color: #555;
            display: flex; align-items: flex-end; justify-content: center; padding-bottom: 5px; background: transparent; border: none; font-family: inherit;
        }
        .dia.off { visibility: hidden; }
        .dia.hoy { background: #f3f0fb; color: #6b5ca5; font-weight: bold; box-shadow: inset 0 0 0 2px #c9c2ee; }
        .dia.con { background: #f8f4ff; color: #6b5ca5; font-weight: bold; cursor: pointer; transition: transform .2s, background .2s; }
        .dia.con:hover { transform: scale(1.14); background: #e8e1fb; }
        .dia.activo { background: #e8e1fb; box-shadow: 0 0 0 2px #a89fd4; }
        .dia .cor { position: absolute; top: 2px; left: 50%; font-size: 17px; line-height: 1; transform: translateX(-50%); animation: cor 1.4s ease-in-out infinite; }
        @keyframes cor { 0%,100% { transform: translateX(-50%) scale(1); } 50% { transform: translateX(-50%) scale(1.3); } }
        .nota { margin-top: 14px; background: linear-gradient(135deg, #f8f4ff, #efe9fc); border-radius: 16px; padding: 14px 16px; color: #6b5ca5; font-size: 13px; line-height: 1.7; min-height: 62px; animation: entrar .5s ease both; }
        .nota.sin { color: #9b8ec4; text-align: center; }

        /* ---------- Tablet y celular ---------- */
        @media (max-width: 960px) {
            .cols { grid-template-columns: 1fr; }
            aside { position: static; }
            .banner { margin-top: 150px; padding: 112px 24px 28px; text-align: center; }
            .banner p { margin-left: auto; margin-right: auto; }
            .chips { justify-content: center; }
            .oso { right: auto; left: 50%; bottom: auto; top: -138px; width: 200px; transform: translateX(-50%); }
        }
        @media (max-width: 640px) {
            .cita-card { flex-wrap: wrap; }
            .acciones { flex-direction: row; flex-wrap: wrap; width: 100%; }
            .acciones > * { flex: 1; }
        }
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; transition: none !important; } }
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

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- ===== Banner con el osito ===== --}}
    <section class="banner">
        <div class="banner-bg"></div>
        <span class="estrella e1" style="position:absolute">✦</span>
        <span class="estrella e2" style="position:absolute">✦</span>
        <span class="estrella e3" style="position:absolute">✦</span>

        <h1>Mis <span>citas</span> 💜</h1>
        <p>Aquí ves tus próximas citas. Puedes reagendar o cancelar cuando lo necesites.</p>

        <div class="chips">
            <span class="chip">📅 {{ $citas->count() }} {{ $citas->count() === 1 ? 'cita próxima' : 'citas próximas' }}</span>
            @if($citas->first())
                <span class="chip">✨ Siguiente: {{ $citas->first()->inicio->translatedFormat('d M') }} · {{ $citas->first()->inicio->format('h:i A') }}</span>
            @endif
        </div>

        <div class="oso">
            <div class="glow"></div>
            <img src="{{ asset('mascota/osito-citas.png') }}" alt="Osito de Corazón Rebelde con el letrero: mira tus citas aquí">
            <span class="lat l1">💜</span><span class="lat l2">✨</span><span class="lat l3">💅</span>
        </div>
    </section>

    <div class="cols">

        {{-- ===== Lista de citas ===== --}}
        <div>
            <h2><span class="brillo-h">✦</span> Próximas citas</h2>

            @forelse($citas as $cita)
                <div class="cita-card" style="--i: {{ $loop->index }}">
                    <div class="fecha">
                        <span class="mes">{{ $cita->inicio->translatedFormat('M') }}</span>
                        <strong>{{ $cita->inicio->format('d') }}</strong>
                        <span class="dow">{{ $cita->inicio->translatedFormat('D') }}</span>
                    </div>

                    <div class="info">
                        <h3>{{ $cita->servicio->nombre }}</h3>
                        <p>🕐 {{ $cita->inicio->format('h:i A') }}</p>
                        <p>💅 <span class="estilista">{{ $cita->especialista->user->name }}</span></p>
                        @if($cita->notas)
                            <p>📝 {{ $cita->notas }}</p>
                        @endif
                        <span class="faltan" data-fecha="{{ $cita->inicio->format('Y-m-d\TH:i:s') }}">⏳ …</span>
                    </div>

                    <div class="acciones">
                        <span class="badge">Pendiente</span>
                        <a href="{{ route('citas.edit', $cita) }}" class="btn-reagendar">Reagendar</a>
                        <form method="POST" action="{{ route('citas.cancelar', $cita) }}"
                              onsubmit="return confirm('¿Seguro que deseas cancelar esta cita?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-cancelar">Cancelar</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="vacio">
                    <div class="grande">💅</div>
                    <p style="margin-top:10px;">Aún no tienes citas pendientes.<br>¡Es momento de consentirte! 💜</p>
                    <a href="{{ route('citas.create') }}" class="btn-agendar">✦ Agendar una cita</a>
                </div>
            @endforelse
        </div>

        {{-- ===== Calendario ===== --}}
        <aside>
            <div class="cal">
                <div class="cal-top">
                    <button type="button" id="prev">‹</button>
                    <strong id="titulo"></strong>
                    <button type="button" id="next">›</button>
                </div>
                <div class="grid" id="grid"></div>
                <div class="nota sin" id="nota">Toca un día con 💜 para ver el recordatorio de tu cita.</div>
            </div>
        </aside>
    </div>
</div>

<script>
    /* ---------- Uñas y corazones flotando ---------- */
    (function () {
        const fondo = document.getElementById('fondo');
        const corazones = ['💜', '💗', '💖', '🤍'];
        for (let i = 0; i < 20; i++) {
            const flota = document.createElement('div');
            flota.className = 'flota';
            const interior = document.createElement('span');
            const t = Math.random();
            const c = corazones[Math.floor(Math.random() * corazones.length)];
            if (t < 0.45)      interior.textContent = '💅';
            else if (t < 0.7)  interior.innerHTML = `<span class="combo">${c}<span class="una">💅</span></span>`;
            else               interior.textContent = c;

            flota.style.left = Math.random() * 100 + '%';
            flota.style.fontSize = (16 + Math.random() * 24) + 'px';
            flota.style.animationDuration = (11 + Math.random() * 14) + 's';
            flota.style.animationDelay = (-Math.random() * 25) + 's';
            interior.style.animationDuration = (3 + Math.random() * 3) + 's';
            interior.style.animationDelay = (-Math.random() * 4) + 's';
            flota.appendChild(interior);
            fondo.appendChild(flota);
        }
    })();

    /* ---------- "Faltan X días" ---------- */
    document.querySelectorAll('.faltan').forEach(el => {
        const destino = new Date(el.dataset.fecha);
        const hoy = new Date(); hoy.setHours(0, 0, 0, 0);
        const dia = new Date(destino); dia.setHours(0, 0, 0, 0);
        const dias = Math.round((dia - hoy) / 86400000);
        el.textContent = dias <= 0 ? '⏳ ¡Es hoy! Te esperamos' : dias === 1 ? '⏳ Es mañana' : `⏳ Faltan ${dias} días`;
    });

    /* ---------- Calendario ---------- */
    const eventos = @json($eventos ?? []);
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
        if (!lista.length) { nota.className = 'nota sin'; nota.innerHTML = 'Toca un día con 💜 para ver el recordatorio de tu cita.'; return; }
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
        dows.forEach(x => { const e = document.createElement('div'); e.className = 'dow-h'; e.textContent = x; grid.appendChild(e); });

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