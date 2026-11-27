{{-- Si guardas tu logo como public/img/logo.png se usa esa imagen; si no, se dibuja la insignia. --}}
<a href="{{ url('/') }}">
    @if (file_exists(public_path('img/logo.png')))
        <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" class="cr-logo-img">
    @else
        <span class="cr-marca">
            <span class="cr-nombre">Corazón Rebelde</span>
            <span class="cr-sub">Nails Spa</span>
        </span>
    @endif
</a>
