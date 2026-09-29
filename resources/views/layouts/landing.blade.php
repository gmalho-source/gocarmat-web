<!DOCTYPE html>
<html lang="pt">
<head>
    {{-- Tem de ser o primeiro script do <head>, antes de qualquer outro
         script de terceiros, para o bloqueio de cookies do Iubenda funcionar. --}}
    @include('partials.cookie-consent')

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'GOCARMAT — A sua oficina multimarca · Grande Lisboa')</title>
    <meta name="description" content="@yield('meta_description', 'Rede de oficinas multimarca na Grande Lisboa: revisão oficial, pneus, colisão, climatização e assistência a elétricos com o EVA Powerlab.')">
    @if (filled(\App\Models\Setting::get('google_site_verification')))
        <meta name="google-site-verification" content="{{ \App\Models\Setting::get('google_site_verification') }}">
    @endif
    @stack('meta')

    <link rel="icon" href="{{ asset('icons/favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="icon" href="{{ asset('icons/favicon-16.png') }}" sizes="16x16" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#0e61fb">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,600,700&family=jetbrains-mono:500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    @php $gtmId = \App\Models\Setting::get('gtm_id'); @endphp
    @if (filled($gtmId))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    {{-- Sem cabeçalho/menu nem rodapé de propósito: página de campanha
         autónoma, pensada para não dar saída do funil de conversão. --}}
    <main>
        @yield('content')
    </main>
</body>
</html>
