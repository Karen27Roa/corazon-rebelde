{{-- Panel izquierdo morado (igual al del login) --}}
<aside class="cr-panel" aria-hidden="true">
    <span class="cr-circulo c1"></span>
    <span class="cr-circulo c2"></span>

    <span class="cr-flota f1">💜</span>
    <span class="cr-flota f2">💅</span>
    <span class="cr-flota f3">✨</span>
    <span class="cr-flota f4">💜</span>
    <span class="cr-flota f5">⭐</span>
    <span class="cr-flota f6">💅</span>
    <span class="cr-flota f7">✨</span>

    <div class="cr-escena">
        <div class="cr-aura"></div>
        <div class="cr-candado">{{ $icono ?? '🔐' }}</div>
        <span class="cr-chip k1">{{ $chip1 ?? '💌 Enlace a tu correo' }}</span>
        <span class="cr-chip k2">{{ $chip2 ?? '🔒 100% seguro' }}</span>
        <span class="cr-chip k3">{{ $chip3 ?? '💜 Te esperamos' }}</span>
    </div>

    <h2>{!! $titulo ?? 'Recupera tu <span>brillo</span><br>en un momento' !!}</h2>
    <p>{{ $texto ?? 'Te ayudamos a volver a tu cuenta para que sigas agendando tus citas favoritas.' }}</p>
</aside>
