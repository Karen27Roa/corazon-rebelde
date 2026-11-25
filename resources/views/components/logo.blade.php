@props(['size' => 'md'])
@php
    $fs = ['sm' => 1.6, 'md' => 2.3, 'lg' => 3.2][$size] ?? 2.3;
@endphp

@once
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">
    <style>
        .cr-marca{
            text-decoration:none; position:relative; display:inline-block; line-height:1;
            padding:6px 20px; border-radius:16px; vertical-align:middle;
            background:linear-gradient(135deg,#4b2d7a,#7a4cc4);
            box-shadow:0 6px 18px rgba(46,29,77,.35);
        }
        .cr-marca .cr-nombre{
            display:block; font-family:'Dancing Script',cursive; font-weight:700;
            font-size:calc(var(--fs) * 1rem);
            background:linear-gradient(100deg,#c98a00 0%,#ffe066 25%,#fff3b0 40%,#ffd21f 55%,#c98a00 100%);
            background-size:250% 100%;
            -webkit-background-clip:text; background-clip:text;
            color:transparent; -webkit-text-fill-color:transparent;
            animation:cr-oro 4s linear infinite;
            filter:drop-shadow(0 2px 4px rgba(0,0,0,.35));
            white-space:nowrap;
        }
        .cr-marca .cr-sub{
            display:block; font-family:'Dancing Script',cursive; font-weight:600;
            font-size:calc(var(--fs) * .465rem); text-align:center; color:#ffe680;
            letter-spacing:2px; margin-top:-4px; text-shadow:0 0 10px rgba(255,214,31,.7);
        }
        .cr-marca .cr-b{
            position:absolute; color:#ffe066; text-shadow:0 0 8px #ffd21f;
            animation:cr-titila 2s ease-in-out infinite; pointer-events:none;
        }
        .cr-b1{top:0;left:0;font-size:16px}
        .cr-b2{top:2px;right:2px;font-size:20px;animation-delay:.6s!important}
        .cr-b3{bottom:8px;left:8px;font-size:12px;animation-delay:1.1s!important}
        .cr-b4{bottom:2px;right:14px;font-size:15px;animation-delay:1.5s!important}
        .cr-b5{top:40%;left:46%;font-size:11px;animation-delay:.3s!important}

        @keyframes cr-oro{to{background-position:-250% 0}}
        @keyframes cr-titila{
            0%,100%{opacity:.15;transform:scale(.6) rotate(0)}
            50%{opacity:1;transform:scale(1.25) rotate(45deg)}
        }
        @media(prefers-reduced-motion:reduce){
            .cr-marca .cr-nombre,.cr-marca .cr-b{animation:none!important}
        }
    </style>
@endonce

<a href="{{ url('/') }}" class="cr-marca" style="--fs: {{ $fs }}">
    <span class="cr-b cr-b1">✦</span><span class="cr-b cr-b2">✦</span><span class="cr-b cr-b3">✦</span><span class="cr-b cr-b4">✦</span><span class="cr-b cr-b5">✦</span>
    <span class="cr-nombre">Corazón Rebelde</span>
    <span class="cr-sub">Nails Spa</span>
</a>