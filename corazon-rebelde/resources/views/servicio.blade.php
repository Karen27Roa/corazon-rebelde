@php
$servicios = [
 'manicure' => ['t'=>'Manicure','ico'=>'💅','intro'=>'Manos suaves, uñas perfectas y un acabado impecable. Elige el tratamiento que mejor se adapte a ti.',
  'puntos'=>['Productos de alta calidad y herramientas esterilizadas','Atención personalizada y relajante','Tu cita incluye hidratación final de manos'],
  'items'=>[
   ['Manicure tradicional','$25.000','45 min','Limado, cuidado de cutícula, hidratación y esmaltado tradicional del color que elijas.'],
   ['Spa de manos','$45.000','1 hora','Exfoliación, mascarilla hidratante, masaje relajante y esmaltado. Ideal para consentirte.'],
   ['Manicure ruso','$60.000','1 h 15 min','Técnica detallada de cutícula con acabado limpio y de larga duración.']]],
 'pedicure-spa' => ['t'=>'Pedicure Spa','ico'=>'🦶','intro'=>'Relajación total para tus pies, con exfoliación, hidratación y un esmaltado precioso.',
  'puntos'=>['Baño de pies relajante','Exfoliación y retiro de durezas','Hidratación profunda con masaje'],
  'items'=>[
   ['Pedicure tradicional','$30.000','50 min','Limado, cuidado de cutículas, hidratación y esmaltado tradicional.'],
   ['Pedicure spa','$50.000','1 h 10 min','Incluye exfoliación, mascarilla, masaje y esmaltado. Pies suaves y renovados.'],
   ['Pedicure semipermanente','$55.000','1 h 10 min','Pedicure completo con esmaltado semipermanente que dura semanas.']]],
 'unas-acrilicas' => ['t'=>'Uñas Acrílicas','ico'=>'✨','intro'=>'Largo, forma y diseño a tu gusto. Uñas resistentes, elegantes y con acabado profesional.',
  'puntos'=>['Duración aproximada de 3 a 4 semanas','Recomendamos relleno cada 15 a 21 días','La duración depende del cuidado de cada persona'],
  'items'=>[
   ['Acrílico en color','$90.000','2 horas','Construcción de uñas en la forma y largo que prefieras, con color liso.'],
   ['Acrílico con diseño','$110.000','2 h 30 min','Uñas acrílicas con decoración sencilla incluida: francesa, degradé o brillos.'],
   ['Relleno','$70.000','1 h 30 min','Mantenimiento de tus uñas acrílicas para que luzcan como nuevas.'],
   ['Retiro de acrílico','$25.000','40 min','Retiro cuidadoso para proteger tu uña natural.']]],
 'nail-art' => ['t'=>'Nail Art','ico'=>'🎨','intro'=>'Decoración personalizada con brillos, piedras y detalles únicos. Cuéntanos tu idea y la hacemos realidad.',
  'puntos'=>['Puedes traer una foto de referencia','El valor final depende de la complejidad del diseño','Todos los diseños se aplican con cuidado y detalle'],
  'items'=>[
   ['Diseño sencillo','$3.000 por uña','Según diseño','Líneas, puntos, flores o detalles pequeños.'],
   ['Efecto espejo o cromo','$15.000','20 min','Acabado brillante tipo espejo en el color que quieras.'],
   ['Pedrería y cristales','$20.000','30 min','Aplicación de piedras y cristales para uñas de ensueño.'],
   ['Encapsulado','$35.000','45 min','Decoración protegida dentro de la capa de gel o acrílico.']]],
 'maquillaje' => ['t'=>'Maquillaje','ico'=>'💄','fondo'=>'makeup',
  'intro'=>'Looks para cada ocasión: desde un acabado natural hasta el maquillaje perfecto para tu matrimonio o tu grado.',
  'puntos'=>['Productos de alta calidad y larga duración','Pruebas de maquillaje con cita previa','Te asesoramos según tu tipo de piel y tu vestuario'],
  'aviso'=>'*Los valores pueden variar según el tipo de evento, el look y el número de personas. Escríbenos y te confirmamos tu cotización.',
  'items'=>[
   ['Natural look','$60.000','45 min','Acabado fresco y luminoso que realza tu belleza sin cargar el rostro. Ideal para el día a día.'],
   ['Maquillaje de noche','$80.000','1 hora','Look glamuroso con más color, definición y brillo para eventos y fiestas.'],
   ['Matrimonios y novias','$150.000','1 h 30 min','Maquillaje de larga duración para el día más especial, con prueba previa opcional.'],
   ['Grados','$90.000','1 hora','Look elegante y duradero para que luzcas increíble en las fotos de tu graduación.'],
   ['Quinceañeras','$100.000','1 h 15 min','Maquillaje juvenil y delicado para tu fiesta de 15 años.'],
   ['Maquillaje social','$70.000','50 min','Para cumpleaños, cenas, reuniones y cualquier ocasión especial.']]],
 'esmaltado-semipermanente' => ['t'=>'Esmaltado Semipermanente','ico'=>'💜','intro'=>'Color y brillo que dura semanas, sin estropearse. Perfecto para lucir lindas todos los días.',
  'puntos'=>['Secado inmediato con lámpara','Dura hasta 3 semanas','Gran variedad de colores'],
  'items'=>[
   ['Semipermanente en manos','$40.000','1 hora','Preparación de la uña y esmaltado semipermanente.'],
   ['Semipermanente en pies','$45.000','1 hora','Preparación y esmaltado con acabado de larga duración.'],
   ['Baby boomer','$55.000','1 h 15 min','Degradé suave entre rosado y blanco, elegante y natural.'],
   ['Retiro de semipermanente','$15.000','30 min','Retiro seguro que cuida tu uña natural.']]],
 'tratamientos-spa' => ['t'=>'Tratamientos Spa','ico'=>'🌸','intro'=>'Hidratación y mimos para ti. Un momento de pausa para manos y pies.',
  'puntos'=>['Ambiente tranquilo y cómodo','Productos hidratantes y relajantes','Se pueden combinar con tu manicure o pedicure'],
  'items'=>[
   ['Parafina','$35.000','30 min','Baño de parafina tibia que suaviza e hidrata profundamente.'],
   ['Exfoliación e hidratación','$40.000','40 min','Retira células muertas y deja la piel suave y luminosa.'],
   ['Masaje relajante','$30.000','25 min','Masaje en manos o pies para soltar la tensión.']]],
];
abort_unless(isset($servicios[$slug]), 404);
$s = $servicios[$slug];
$fondo = trim(str_repeat(($s['fondo'] ?? 'nails').' ', 10));
$aviso = $s['aviso'] ?? '*Los valores pueden variar según el diseño, el largo y el estado de tus uñas. Escríbenos y te confirmamos tu cotización.';

$dir = public_path('images/servicios/'.$slug);
$propias = is_dir($dir) ? (glob($dir.'/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: []) : [];
$fotos = [];
foreach ($propias as $a) { $fotos[] = asset('images/servicios/'.$slug.'/'.basename($a)); }
if (!$fotos) {
  foreach ((glob(public_path('images/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE) ?: []) as $a) {
    if (stripos(basename($a), 'logo') === false) { $fotos[] = asset('images/'.basename($a)); }
  }
}
$foto = fn($i) => $fotos ? $fotos[$i % count($fotos)] : null;
$wa = 'https://wa.me/573144685775?text='.rawurlencode('Hola Corazón Rebelde 💜, quiero información sobre: '.$s['t']);
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $s['t'] }} - Corazón Rebelde Nails Spa</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Quicksand:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--l50:#faf6ff;--l100:#f1e8ff;--l200:#e0cdfc;--l300:#c9a8f7;--l400:#b085ee;--l500:#9560d9;--l700:#5b3a8f;--l900:#2e1d4d;--oro:#d4a017;
--script:'Dancing Script',cursive;--body:'Quicksand',sans-serif}
html{scroll-behavior:smooth}
body{font-family:var(--body);color:var(--l900);background:linear-gradient(180deg,var(--l50),#fff 40%,var(--l100));overflow-x:hidden}
h1,h2,h3{font-family:var(--script);color:var(--l700);line-height:1.15}
nav{position:sticky;top:0;z-index:100;background:linear-gradient(135deg,#4b2d7a,#7a4cc4);padding:10px 40px;display:flex;align-items:center;justify-content:space-between;box-shadow:0 6px 24px rgba(46,29,77,.35)}
.marca{text-decoration:none;display:inline-block;line-height:1;text-align:center}
.marca .nombre{display:block;font-family:var(--script);font-weight:700;font-size:2.6rem;background:linear-gradient(100deg,#c98a00 0%,#ffe066 25%,#fff3b0 40%,#ffd21f 55%,#c98a00 100%);background-size:250% 100%;-webkit-background-clip:text;background-clip:text;color:transparent;-webkit-text-fill-color:transparent;animation:oro 4s linear infinite}
.marca .sub{display:block;font-family:var(--script);font-size:1.2rem;color:#ffe680;letter-spacing:2px;text-shadow:0 0 10px rgba(255,214,31,.7)}
.volver{color:#fff;text-decoration:none;font-weight:700}
.btn{position:relative;overflow:hidden;display:inline-block;text-decoration:none;background:linear-gradient(135deg,var(--l400),var(--l500));color:#fff!important;font-weight:700;border:0;cursor:pointer;font-family:var(--body);font-size:1rem;border-radius:999px;padding:12px 28px;box-shadow:0 8px 22px rgba(149,96,217,.35);transition:transform .25s}
.btn::before{content:"";position:absolute;top:0;left:-80%;width:50%;height:100%;background:linear-gradient(120deg,transparent,rgba(255,255,255,.75),transparent);transform:skewX(-20deg);animation:brillo 3.2s ease-in-out infinite}
.btn:hover{transform:translateY(-3px) scale(1.04)}

.banner{max-width:1100px;margin:30px auto 0;padding:50px 20px;border-radius:30px;background:linear-gradient(135deg,var(--l300),var(--l400));position:relative;overflow:hidden;display:flex;justify-content:center;gap:36px;flex-wrap:wrap}
.banner::before{content:var(--fondo);position:absolute;inset:-10px;font-family:var(--script);font-weight:700;font-size:6rem;line-height:1.1;color:rgba(255,255,255,.22);word-break:break-all;pointer-events:none}
.circ{position:relative;width:240px;height:240px;border-radius:50%;border:6px solid #fff;background:var(--l100) center/cover;box-shadow:0 10px 30px rgba(46,29,77,.3);display:flex;align-items:center;justify-content:center;font-size:4rem;transition:transform .4s}
.circ:hover{transform:scale(1.06) rotate(-2deg)}
.tit{text-align:center;margin:40px 16px 10px}
.tit h1{font-size:clamp(2.4rem,6vw,4rem)}
.tit p{max-width:640px;margin:10px auto 0;color:#6e5d8c;line-height:1.6;font-size:1.1rem}

main{max-width:880px;margin:30px auto 60px;padding:0 20px}
.puntos{list-style:none;margin:10px 0 30px;padding:20px 24px;background:#fff;border:1px solid var(--l200);border-radius:20px;box-shadow:0 8px 24px rgba(149,96,217,.12)}
.puntos li{padding:5px 0;color:#5f4e80}
.puntos li::before{content:"♡ ";color:var(--l500);font-weight:700}
details{background:#fff;border-bottom:2px solid var(--l300);margin-bottom:14px;border-radius:16px 16px 0 0;box-shadow:0 6px 18px rgba(149,96,217,.1)}
summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:12px;padding:18px 22px;font-weight:700;color:var(--l700);font-size:1.12rem}
summary::-webkit-details-marker{display:none}
summary .pr{color:var(--l500);white-space:nowrap}
summary::after{content:"▾";margin-left:8px;color:var(--l500);transition:transform .3s}
details[open] summary::after{transform:rotate(180deg)}
.cuerpo{padding:0 22px 22px;line-height:1.65;color:#5f4e80}
.meta{display:flex;gap:10px;flex-wrap:wrap;margin:12px 0 16px}
.meta span{background:var(--l100);color:var(--l700);padding:6px 14px;border-radius:999px;font-weight:700;font-size:.92rem}
.tecnicas{display:flex;gap:22px;flex-wrap:wrap;justify-content:center;margin:34px 0}
.tecnicas figure{width:190px;text-align:center}
.tecnicas img,.tecnicas .ph{width:190px;height:230px;object-fit:cover;border:4px solid var(--oro);border-radius:6px;box-shadow:0 8px 22px rgba(0,0,0,.18);display:flex;align-items:center;justify-content:center;font-size:3rem;background:var(--l100)}
.tecnicas figcaption{margin-top:8px;font-weight:700;font-size:.85rem;text-transform:uppercase;color:var(--l700)}
.aviso{font-size:.88rem;font-style:italic;color:#7b6a99;margin:20px 0;text-align:center}
.acciones{text-align:center;display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
footer{background:var(--l900);color:var(--l200);text-align:center;padding:26px;font-size:14px}
footer a{color:#ffe680}

.promo{position:fixed;left:20px;bottom:20px;z-index:300;width:290px;background:#fff;border-radius:22px;padding:22px 20px;text-align:center;box-shadow:0 14px 40px rgba(46,29,77,.4);border:2px solid var(--l300);transform:translateY(160%);transition:transform .7s cubic-bezier(.2,1.2,.4,1)}
.promo.ver{transform:none}
.promo .x{position:absolute;top:8px;right:12px;border:0;background:none;font-size:22px;cursor:pointer;color:var(--l700)}
.promo .corona{font-size:2rem;animation:flota 2.5s ease-in-out infinite}
.promo .off{font-family:var(--script);font-weight:700;font-size:3rem;color:var(--l500);line-height:1}
.promo p{margin:6px 0 14px;font-weight:700;color:var(--l700)}
.promo small{display:block;margin-top:10px;color:#8a7aa8;font-size:.75rem}
.promo::after{content:"✦";position:absolute;top:-10px;left:-6px;font-size:22px;color:#ffd21f;animation:titila 2s infinite}

@keyframes brillo{0%{left:-80%}60%,100%{left:140%}}
@keyframes oro{to{background-position:-250% 0}}
@keyframes flota{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
@keyframes titila{0%,100%{opacity:.2;transform:scale(.6)}50%{opacity:1;transform:scale(1.2) rotate(45deg)}}
@media(max-width:700px){nav{padding:8px 14px}.marca .nombre{font-size:1.8rem}.circ{width:170px;height:170px}.promo{left:10px;right:10px;width:auto;bottom:10px}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<nav>
  <a href="{{ url('/') }}" class="marca"><span class="nombre">Corazón Rebelde</span><span class="sub">Nails Spa</span></a>
  <a href="{{ url('/') }}#servicios" class="volver">← Ver todos los servicios</a>
</nav>

<div class="banner" style="--fondo:'{{ $fondo }}'">
  @for($i=0;$i<3;$i++)
    <div class="circ" @if($foto($i)) style="background-image:url('{{ $foto($i) }}')" @endif>@unless($foto($i)){{ $s['ico'] }}@endunless</div>
  @endfor
</div>

<div class="tit">
  <h1>{{ $s['ico'] }} {{ $s['t'] }}</h1>
  <p>{{ $s['intro'] }}</p>
</div>

<main>
  <ul class="puntos">
    @foreach($s['puntos'] as $p)<li>{{ $p }}</li>@endforeach
  </ul>

  @foreach($s['items'] as $k => $it)
  <details @if($k==0) open @endif>
    <summary><span>{{ $it[0] }}</span><span class="pr">{{ $it[1] }}</span></summary>
    <div class="cuerpo">
      <p>{{ $it[3] }}</p>
      <div class="meta"><span>💜 Valor: {{ $it[1] }}</span><span>⏱ Duración: {{ $it[2] }}</span></div>
      <a href="{{ url('/login') }}" class="btn">✦ Agendar</a>
    </div>
  </details>
  @endforeach

  <div class="tecnicas">
    @foreach(array_slice($s['items'],0,4) as $k => $it)
    <figure>
      @if($foto($k+3)) <img src="{{ $foto($k+3) }}" alt="{{ $it[0] }}"> @else <div class="ph">{{ $s['ico'] }}</div> @endif
      <figcaption>{{ $it[0] }}</figcaption>
    </figure>
    @endforeach
  </div>

  <p class="aviso">{{ $aviso }}</p>

  <div class="acciones">
    <a href="{{ url('/login') }}" class="btn">✦ Agendar mi cita online</a>
    <a href="{{ $wa }}" target="_blank" rel="noopener" class="btn" style="background:#25d366">Preguntar por WhatsApp</a>
  </div>
</main>

<footer>© {{ date('Y') }} Corazón Rebelde Nails Spa ♥ Lunes a viernes 10 a.m. a 7 p.m. · Sábados y domingos 10 a.m. a 6 p.m. · <a href="{{ $wa }}" target="_blank">314 468 5775</a></footer>

<div class="promo" id="promo">
  <button class="x" aria-label="Cerrar" onclick="cerrarPromo()">×</button>
  <div class="corona">👑</div>
  <div class="off">10% OFF</div>
  <p>En tu primer agendamiento online</p>
  <a href="{{ url('/login') }}" class="btn">¡Quiero mi descuento!</a>
  <small>Válido para tu primera cita agendada desde la página.</small>
</div>

<script>
const promo = document.getElementById('promo');
function cerrarPromo(){ promo.classList.remove('ver'); try{ sessionStorage.setItem('promoCerrada','1'); }catch(e){} }
let cerrada = false; try{ cerrada = sessionStorage.getItem('promoCerrada'); }catch(e){}
if(!cerrada){ setTimeout(()=>promo.classList.add('ver'), 1500); }
</script>
</body>
</html>