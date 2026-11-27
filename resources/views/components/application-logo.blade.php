{{-- Si existe public/img/logo.png muestra tu logo; si no, muestra el nombre de la app. --}}
@if (file_exists(public_path('img/logo.png')))
    <img src="{{ asset('img/logo.png') }}" alt="{{ config('app.name') }}" {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <span {{ $attributes->merge(['class' => 'flex items-center justify-center font-bold text-2xl text-gray-800 whitespace-nowrap w-auto h-auto']) }}>
        {{ config('app.name') }}
    </span>
@endif
