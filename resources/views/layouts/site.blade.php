<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'GOCARMAT — A sua oficina multimarca · Grande Lisboa')</title>
    <meta name="description" content="@yield('meta_description', 'Rede de oficinas multimarca na Grande Lisboa: revisão oficial, pneus, colisão, climatização e assistência a elétricos com o EVA Powerlab.')">
    @stack('meta')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,600,700&family=jetbrains-mono:500,600,700,800" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
