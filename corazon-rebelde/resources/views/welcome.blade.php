<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Corazón Rebelde - Nails Spa</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--l50:#faf6ff;--l100:#f1e8ff;--l200:#e0cdfc;--l300:#c9a8f7;--l400:#b085ee;--l500:#9560d9;--l700:#5b3a8f;--l900:#2e1d4d;--rosa:#ffd6f2;
--script:'Dancing Script',cursive;--body:'Quicksand',sans-serif}
html{scroll-behavior:smooth}
body{font-family:var(--body);color:var(--l900);background:linear-gradient(180deg,var(--l50),#fff 40%,var(--l100));overflow-x:hidden}
h1,h2,h3{font-family:var(--script);color:var(--l700);line-height:1.15}

.cinta{background:linear-gradient(90deg,var(--l400),var(--l500),var(--l400));color:#fff;overflow:hidden;white-space:nowrap;padding:8px 0;font-weight:600;font-size:14px;position:relative;z-index:101}
.cinta span{display:inline-block;padding-left:100%;animation:cinta 24s linear infinite}

nav{position:sticky;top:0;z-index:100;background:linear-gradient(135deg,#4b2d7a,#7a4cc4);padding:10px 40px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 6px 24px rgba(46,29,77,.35)}
.marca{text-decoration:none;position:relative;display:inline-block;padding:4px 18px;line-height:1}
.marca .nombre{display:block;font-family:var(--script);font-weight:700;font-size:2.9rem;
  background:linear-gradient(100deg,#c98a00 0%,#ffe066 25%,#fff3b0 40%,#ffd21f 55%,#c98a00 100%);background-size:250% 100%;
  -webkit-background-clip:text;background-clip:text;color:transparent;-webkit-text-fill-color:transparent;
  animation:oro 4s linear infinite;filter:drop-shadow(0 2px 4px rgba(0,0,0,.35))}
.marca .sub{display:block;font-family:var(--script);font-weight:600;font-size:1.35rem;text-align:center;color:#ffe680;letter-spacing:2px;margin-top:-4px;text-shadow:0 0 10px rgba(255,214,31,.7)}
.marca .b{position:absolute;color:#ffe066;text-shadow:0 0 8px #ffd21f;animation:titila 2s ease-in-out infinite;pointer-events:none}
.b1{top:0;left:0;font-size:16px}.b2{top:2px;right:2px;font-size:20px;animation-delay:.6s!important}
.b3{bottom:8px;left:8px;font-size:12px;animation-delay:1.1s!important}.b4{bottom:2px;right:14px;font-size:15px;animation-delay:1.5s!important}
.b5{top:40%;left:46%;font-size:11px;animation-delay:.3s!important}

.nav-links{display:flex;align-items:center;gap:28px}
.nav-links a{color:#fff;text-decoration:none;font-weight:700;position:relative}
.nav-links a:not(.btn)::after{content:"";position:absolute;left:0;bottom:-5px;width:0;height:2px;background:linear-gradient(90deg,#ffe066,var(--rosa));transition:width .3s}
.nav-links a:not(.btn):hover::after{width:100%}

.btn{position:relative;overflow:hidden;display:inline-block;text-decoration:none;background:linear-gradient(135deg,var(--l400),var(--l500));color:#fff!important;font-weight:700;border-radius:999px;padding:12px 28px;box-shadow:0 8px 22px rgba(149,96,217,.35);transition:transform .25s,box-shadow .25s}
.btn::before{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(120deg,transparent,rgba(255,255,255,.75),transparent);transform:skewX(-20deg);animation:brillo 3.2s ease-in-out infinite}
.btn:hover{transform:translateY(-3px) scale(1.04);box-shadow:0 14px 30px rgba(149,96,217,.5)}
.btn-blanco{background:#fff;color:var(--l700)!important}

.carrusel{position:relative;height:620px;overflow:hidden;background:linear-gradient(135deg,var(--l300),var(--l500))}
.slide{position:absolute;inset:0;background-size:cover;background-position:center;opacity:0;transition:opacity 1.2s}
.slide.activo{opacity:1}
.carrusel::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(91,58,143,.35),rgba(91,58,143,.6))}
.hero{position:absolute;inset:0;z-index:3;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;color:#fff;padding:20px}
.hero h1{color:#fff;font-size:clamp(3rem,8vw,6rem);text-shadow:0 4px 25px rgba(46,29,77,.6);animation:aparece 1.2s ease both}
.hero p{font-size:1.3rem;margin:10px 0 26px;text-shadow:0 2px 12px rgba(46,29,77,.6);animation:aparece 1.2s .3s ease both}
.hero .btn{animation:aparece 1.2s .6s ease both}
.flecha{position:absolute;top:50%;z-index:4;transform:translateY(-50%);width:46px;height:46px;border-radius:50%;border:0;background:rgba(255,255,255,.7);color:var(--l700);font-size:20px;cursor:pointer;transition:.3s}
.flecha:hover{background:#fff;transform:translateY(-50%) scale(1.12)}
.f-izq{left:20px}.f-der{right:20px}

section{padding:80px 24px;position:relative;z-index:1}
.titulo{text-align:center;margin-bottom:46px}
.titulo h2{font-size:clamp(2.2rem,5vw,3.4rem)}
.titulo h2::after{content:"♥";display:block;font-size:1rem;color:var(--l400);margin-top:4px;animation:latido 1.8s ease-in-out infinite}
.titulo p{color:#7b6a99;font-size:1.1rem;margin-top:6px}
.grid{max-width:1100px;margin:auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:26px}
.card{background:#fff;border:1px solid var(--l200);border-radius:28px;padding:34px 26px;text-align:center;box-shadow:0 10px 30px rgba(149,96,217,.14);transition:transform .35s,box-shadow .35s}
.card:hover{transform:translateY(-10px);box-shadow:0 20px 44px rgba(149,96,217,.3)}
.card .ico{font-size:2.6rem;display:inline-block;animation:flota 3s ease-in-out infinite}
.card h3{font-size:2rem;margin:10px 0 6px}
.card p{color:#6e5d8c;line-height:1.5}
.card .precio{display:inline-block;margin-top:14px;font-weight:700;color:var(--l500)}

/* Reseñas */
.resenas-grid{max-width:1100px;margin:auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:26px}
.resena{background:#fff;border:1px solid var(--l200);border-radius:28px;padding:30px 26px;box-shadow:0 10px 30px rgba(149,96,217,.14);position:relative;transition:transform .35s,box-shadow .35s}
.resena:hover{transform:translateY(-8px);box-shadow:0 20px 44px rgba(149,96,217,.3)}
.resena::before{content:"“";position:absolute;top:6px;right:22px;font-family:var(--script);font-size:5rem;color:var(--l200);line-height:1}
.resena .estrellas{color:#f5b301;font-size:1.3rem;letter-spacing:3px;text-shadow:0 0 10px rgba(255,214,31,.6)}
.resena p{margin:12px 0 18px;line-height:1.65;color:#5f4e80;font-size:1.05rem}
.resena .quien{display:flex;align-items:center;gap:12px}
.resena .avatar{width:46px;height:46px;border-radius:50%;background:linear-gradient(135deg,var(--l400),var(--l500));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem}
.resena .quien strong{display:block;color:var(--l700)}
.resena .quien small{color:#8a7aa8}
.google-badge{text-align:center;margin-bottom:34px;font-weight:700;color:var(--l700)}
.google-badge span{color:#f5b301;font-size:1.4rem;letter-spacing:3px}
.res-botones{text-align:center;margin-top:36px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

.contacto{background:linear-gradient(135deg,var(--l500),var(--l700));color:#fff}
.contacto > h2{color:#fff;text-align:center;font-size:clamp(2.2rem,5vw,3.4rem);margin-bottom:34px}
.contacto-grid{max-width:1100px;margin:auto;display:grid;grid-template-columns:1fr 1.5fr;gap:28px;align-items:stretch}
.info{background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.4);backdrop-filter:blur(6px);border-radius:28px;padding:34px 30px;text-align:left}
.info h3{color:#ffe680;font-size:2.3rem;margin-bottom:14px;text-shadow:0 0 12px rgba(255,214,31,.45)}
.info .fila{margin-bottom:18px;line-height:1.55;font-size:1.08rem}
.info .fila strong{display:block;font-size:1.15rem}
.info .btn{display:inline-flex;align-items:center;gap:10px;margin-top:6px;background:#25d366;box-shadow:0 8px 22px rgba(0,0,0,.25)}
.info .btn svg{width:22px;height:22px;fill:#fff}
.mapa{border-radius:28px;overflow:hidden;border:4px solid rgba(255,255,255,.7);box-shadow:0 16px 40px rgba(46,29,77,.45);min-height:380px}
.mapa iframe{display:block;width:100%;height:100%;min-height:380px;border:0}
.como{text-align:center;margin-top:30px}

footer{background:var(--l900);color:var(--l200);text-align:center;padding:26px;font-size:14px;position:relative;z-index:1}

.wa-flotante{position:fixed;right:22px;bottom:22px;z-index:200;width:60px;height:60px;border-radius:50%;background:#25d366;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,0,0,.3);animation:latido 2.4s ease-in-out infinite}
.wa-flotante svg{width:32px;height:32px;fill:#fff}

.capa{position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0}
.capa i{position:absolute;bottom:-30px;font-style:normal;color:var(--l300);opacity:0;animation:subir linear infinite}

.card-link{text-decoration:none;color:inherit;display:block}
.card-link .card{height:100%}
.card .ver{display:inline-block;margin-top:14px;padding:8px 18px;border-radius:999px;background:linear-gradient(135deg,#b085ee,#9560d9);color:#fff;font-weight:700;font-size:14px}

@keyframes brillo{0%{left:-80%}60%,100%{left:140%}}
@keyframes latido{0%,100%{transform:scale(1)}50%{transform:scale(1.12)}}
@keyframes cinta{to{transform:translateX(-100%)}}
@keyframes flota{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes oro{to{background-position:-250% 0}}
@keyframes titila{0%,100%{opacity:.15;transform:scale(.6) rotate(0)}50%{opacity:1;transform:scale(1.25) rotate(45deg)}}
@keyframes subir{0%{transform:translateY(0) rotate(0);opacity:0}10%{opacity:.8}100%{transform:translateY(-110vh) rotate(360deg);opacity:0}}
@keyframes aparece{from{opacity:0;transform:translateY(26px)}to{opacity:1;transform:none}}

@media(max-width:850px){.contacto-grid{grid-template-columns:1fr}.mapa,.mapa iframe{min-height:320px}}
@media(max-width:700px){nav{padding:8px 14px}.marca .nombre{font-size:2rem}.marca .sub{font-size:1rem}.nav-links{gap:12px}.nav-links a:not(.btn){display:none}.carrusel{height:480px}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
</style>
</head>
<body>

@php
    $archivos = glob(public_path('images/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE) ?: [];
    $fotos = [];
    foreach ($archivos as $a) {
        $n = basename($a);
        if (stripos($n, 'logo') === false) { $fotos[] = asset('images/'.$n); }
    }
    $wa = 'https://wa.me/573144685775?text=' . rawurlencode('Hola Corazón Rebelde 💜, quiero agendar una cita');

    // Enlaces de Google: ver reseñas y escribir una reseña
    $mapsLink = 'https://maps.google.com/?cid=12329472049827692075';
    $resenaLink = 'https://search.google.com/local/writereview?placeid=ChIJRddOVR6ZP44RKz4WRoEVG6s';

    // RESEÑAS reales de tus clientas
    $resenas = [
      ['nombre'=>'Marcela Moreno','fecha'=>'hace 1 mes','estrellas'=>5,'texto'=>'Me encantó la atención 🩷 cuidan las uñas, te asesoran, la atención es increíble, te dedican tiempo y explican sobre todo lo que te haces, volveré mes a mes sin duda.'],
      ['nombre'=>'Karen Roa','fecha'=>'hace 8 meses','estrellas'=>5,'texto'=>'Quedé encantada con este lugar 💅✨. Desde que llegas, el ambiente es súper agradable, limpio y acogedor. El personal es muy profesional, amable y se nota que cuidan cada detalle para que tengas una excelente experiencia.'],
      ['nombre'=>'Luisa Hincapie','fecha'=>'hace 3 meses','estrellas'=>5,'texto'=>'Son las mejores manicuristas!! No solo porque son muy profesionales y te hacen el diseño que les pidas (el que sea) sino que además la calidad humana es insuperable, de verdad atienden con gusto y son muy lindas en el trato. 100% recomendadas'],
    ];
@endphp

<div class="capa" id="capa"></div>

<div class="cinta"><span>✦ Corazón Rebelde Nails Spa ♥ Belleza que inspira ✦ Agenda tu cita hoy ♥ ✦ Uñas, spa y mucho más ♥</span></div>

<nav>
    <a href="#inicio" class="marca">
        <span class="b b1">✦</span><span class="b b2">✦</span><span class="b b3">✦</span><span class="b b4">✦</span><span class="b b5">✦</span>
        <span class="nombre">Corazón Rebelde</span>
        <span class="sub">Nails Spa</span>
    </a>
    <div class="nav-links">
        <a href="#inicio">Inicio</a>
        <a href="#servicios">Servicios</a>
        <a href="#resenas">Reseñas</a>
        <a href="#contacto">Contacto</a>
        <a href="{{ url('/login') }}" class="btn btn-blanco">✦ Agendar cita</a>
    </div>
</nav>

<div class="carrusel" id="inicio">
    @forelse(array_slice($fotos, 0, 4) as $i => $f)
        <div class="slide {{ $i == 0 ? 'activo' : '' }}" style="background-image:url('{{ $f }}')"></div>
    @empty
        <div class="slide activo"></div>
    @endforelse
    <div class="hero">
        <h1>Corazón Rebelde 💜</h1>
        <p>Nails Spa — Belleza que inspira</p>
        <a href="{{ url('/login') }}" class="btn btn-blanco">✦ Agenda tu cita ahora</a>
    </div>
    <button class="flecha f-izq" onclick="mover(-1)">←</button>
    <button class="flecha f-der" onclick="mover(1)">→</button>
</div>

<section id="servicios">
    <div class="titulo">
        <h2>✨ Carta de Servicios ✨</h2>
        <p>Tratamientos exclusivos para realzar tu belleza natural</p>
    </div>
    <div class="grid">
        <div class="card"><span class="ico">💅</span><h3>Manicure</h3><p>Cuidado completo de tus manos con acabado impecable.</p><span class="precio">Consulta el valor</span></div>
        <div class="card"><span class="ico">🦶</span><h3>Pedicure Spa</h3><p>Relajación y suavidad para tus pies.</p><span class="precio">Consulta el valor</span></div>
        <div class="card"><span class="ico">✨</span><h3>Uñas Acrílicas</h3><p>Diseños duraderos, elegantes y a tu estilo.</p><span class="precio">Consulta el valor</span></div>
        <div class="card"><span class="ico">💜</span><h3>Esmaltado Semipermanente</h3><p>Color y brillo que dura semanas.</p><span class="precio">Consulta el valor</span></div>
        <div class="card"><span class="ico">💄</span><h3>Maquillaje</h3><p>Desde un look natural para el día a día hasta un estilo sofisticado para la noche.</p><span class="precio">Consulta el valor</span></div>
    </div>
</section>

<section id="resenas">
    <div class="titulo">
        <h2>Lo que dicen nuestras clientas</h2>
        <p>Reseñas reales de Google Maps</p>
    </div>
    <div class="google-badge"><span>★★★★★</span><br>Calificación en Google Maps</div>
    <div class="resenas-grid">
        @foreach($resenas as $r)
        <div class="resena">
            <div class="estrellas">{{ str_repeat('★', $r['estrellas']) }}{{ str_repeat('☆', 5 - $r['estrellas']) }}</div>
            <p>{{ $r['texto'] }}</p>
            <div class="quien">
                <div class="avatar">{{ mb_strtoupper(mb_substr($r['nombre'], 0, 1)) }}</div>
                <div><strong>{{ $r['nombre'] }}</strong><small>{{ $r['fecha'] }} · Google</small></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="res-botones">
        <a href="{{ $mapsLink }}" target="_blank" rel="noopener" class="btn">⭐ Ver todas las reseñas en Google</a>
        <a href="{{ $resenaLink }}" target="_blank" rel="noopener" class="btn btn-blanco">✍️ Déjanos tu reseña</a>
    </div>
</section>

<section class="contacto" id="contacto">
    <h2>¿Lista para brillar? 💜</h2>
    <div class="contacto-grid">
        <div class="info">
            <h3>Horario de atención</h3>
            <div class="fila"><strong>Lunes a viernes</strong>10:00 a.m. a 7:00 p.m.</div>
            <div class="fila"><strong>Sábados y domingos</strong>10:00 a.m. a 6:00 p.m.</div>
            <div class="fila"><strong>Escríbenos al WhatsApp</strong>+57 314 468 5775</div>
            <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn">
                <svg viewBox="0 0 24 24"><path d="M17.5 14.4c-.3-.1-1.7-.8-2-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.4.1-.6l.4-.5c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2z"/></svg>
                Escríbenos por WhatsApp
            </a>
        </div>
        <div class="mapa">
            <iframe
                src="https://www.google.com/maps?q=4.5854582,-74.1035625&z=17&output=embed"
                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                title="Ubicación Corazón Rebelde Nails Spa"></iframe>
        </div>
    </div>
    <div class="como">
        <a href="{{ url('/login') }}" class="btn btn-blanco">✦ Agendar mi cita</a>
        <a href="https://maps.app.goo.gl/yoVQcyLytgkebnxy8" target="_blank" rel="noopener" class="btn btn-blanco">📍 Cómo llegar</a>
    </div>
</section>

<footer>© {{ date('Y') }} Corazón Rebelde Nails Spa ♥ Todos los derechos reservados</footer>

<a href="{{ $wa }}" target="_blank" rel="noopener" class="wa-flotante" aria-label="WhatsApp">
    <svg viewBox="0 0 24 24"><path d="M17.5 14.4c-.3-.1-1.7-.8-2-.9-.3-.1-.5-.1-.7.1-.2.3-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.4.1-.6l.4-.5c.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.6-.9-2.2-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4-.3.3-1 1-1 2.5s1.1 2.9 1.2 3.1c.1.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.4.2 1.9.1.6-.1 1.7-.7 2-1.4.2-.7.2-1.3.2-1.4-.1-.2-.3-.3-.6-.4zM12 2a10 10 0 0 0-8.5 15.2L2 22l4.9-1.4A10 10 0 1 0 12 2z"/></svg>
</a>

<script>
let actual = 0;
const slides = document.querySelectorAll('.slide');
function mover(d){
    if(slides.length < 2) return;
    slides[actual].classList.remove('activo');
    actual = (actual + d + slides.length) % slides.length;
    slides[actual].classList.add('activo');
}
setInterval(() => mover(1), 5000);

if (!matchMedia('(prefers-reduced-motion: reduce)').matches) {
    const capa = document.getElementById('capa');
    const sim = ['✦','♥','✧','✨','♡'];
    for (let i = 0; i < 18; i++) {
        const s = document.createElement('i');
        s.textContent = sim[i % sim.length];
        s.style.left = Math.random()*100 + 'vw';
        s.style.fontSize = (10 + Math.random()*20) + 'px';
        s.style.animationDuration = (9 + Math.random()*10) + 's';
        s.style.animationDelay = (Math.random()*12) + 's';
        capa.appendChild(s);
    }
    addEventListener('mousemove', e => {
        if (Math.random() > .12) return;
        const d = document.createElement('span');
        d.textContent = '✦';
        Object.assign(d.style,{position:'fixed',left:e.clientX+'px',top:e.clientY+'px',color:'#ffd21f',pointerEvents:'none',zIndex:9999,transition:'all .8s ease-out',fontSize:'14px'});
        document.body.appendChild(d);
        requestAnimationFrame(() => { d.style.transform='translateY(-30px) scale(.3)'; d.style.opacity='0'; });
        setTimeout(() => d.remove(), 800);
    });
}

// Cada tarjeta de servicio abre su página de detalle
document.querySelectorAll('#servicios .card').forEach(c => {
  const t = c.querySelector('h3').textContent.trim();
  const slug = t.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
  const a = document.createElement('a');
  a.href = '{{ url("/servicio") }}/' + slug;
  a.target = '_blank'; a.rel = 'noopener'; a.className = 'card-link';
  c.parentNode.insertBefore(a, c); a.appendChild(c);
  const v = document.createElement('span'); v.className = 'ver'; v.textContent = 'Ver precios y detalles ✦';
  c.appendChild(v);
});
</script>
</body>
</html>