@extends('layouts.site')

@section('title', 'GOCARMAT — Um serviço 360º para o seu carro · Oficinas multimarca na Grande Lisboa')

@php
    $services = [
        ['num' => '01.', 'tag' => 'manutenção', 'title' => 'Revisão Oficial', 'img' => 'servico-revisao.jpg', 'text' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.'],
        ['num' => '02.', 'tag' => 'comodidade', 'title' => 'Inspeção', 'img' => 'servico-inspecao.jpg', 'text' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.'],
        ['num' => '03.', 'tag' => 'segurança', 'title' => 'Pneus', 'img' => 'servico-pneus.jpg', 'text' => 'Pneus das melhores marcas, com montagem, alinhamento de direção e equilibragem incluídos.'],
        ['num' => '04.', 'tag' => 'manutenção', 'title' => 'Colisão e Pintura', 'img' => 'servico-colisao.jpg', 'text' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria, em veículos de combustão e elétricos — deixou de haver motivo para andar com mossas.'],
    ];
    $evaPills = ['EVA Lab — diagnóstico de BMS', 'Rescue — desbloqueio EV', 'Tesla Independent Service', 'EVA Collision', 'Battery Warranty · até 5 anos', 'Certificação MV-BER'];
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- HERO --}}
    <section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,728fr)_minmax(0,1064fr)]">
        <div class="bg-energia px-8 py-14 sm:px-12 xl:px-14 xl:pb-14 xl:pt-20 2xl:px-24 2xl:pb-16 2xl:pt-[131px]">
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-gelo">
                A sua oficina multimarca &middot; Grande Lisboa
            </p>
            <h1 class="mt-8 max-w-[568px] text-4xl font-bold leading-[1.1] tracking-[-0.03em] text-white sm:text-5xl 2xl:text-7xl">
                Um serviço 360º para o seu carro.
            </h1>
            <p class="mt-10 max-w-[544px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                Revisão oficial, pneus, colisão, climatização e assistência a elétricos — todas as marcas, nas 4 oficinas GOCARMAT da Grande Lisboa. Do combustão ao elétrico, com a confiança de 16 anos.
            </p>
            <div class="mt-12 flex flex-wrap gap-4">
                <x-pill variant="lima" :href="url('/marcacoes')">Marcar Serviço</x-pill>
                <x-pill variant="outline-light" :href="url('/marcacoes')">Check-up Gratuito</x-pill>
            </div>
        </div>
        <div class="relative min-h-[320px]">
            <img src="{{ asset('images/hero.jpg') }}" alt="Cliente a carregar o seu carro elétrico" class="animate-hero-zoom absolute inset-0 size-full object-cover">
            <div class="absolute bottom-8 right-8 flex w-[min(480px,90%)] items-center gap-6 rounded-[32px] bg-carbono px-9 py-6">
                <p class="shrink-0 text-[64px] font-bold leading-[1.1] tracking-[-0.06em] text-lima xl:text-[88px]">+16<span class="block text-right text-xl tracking-[-0.6px] text-gelo">ANOS</span></p>
                <p class="text-lg font-bold leading-[1.2] tracking-[-0.6px] text-gelo xl:text-xl">
                    a cuidar de carros de todas as marcas, do combustão ao elétrico.
                </p>
            </div>
        </div>
    </section>

    {{-- SERVIÇOS --}}
    <section id="servicos" class="mt-24 xl:mt-[128px]">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                Os nossos serviços
            </h2>
            <x-pill variant="outline-dark" :href="url('/servicos')">Conheça os nossos Serviços</x-pill>
        </div>
        <div class="mt-14 grid gap-6 sm:grid-cols-2 xl:grid-cols-4 xl:gap-8">
            @foreach ($services as $service)
                <a href="{{ url('/servicos') }}" class="group relative flex flex-col overflow-hidden transition hover:-translate-y-1">
                    <div class="relative flex-1 bg-white px-8 pb-6 pt-8 2xl:px-10 2xl:pt-10">
                        <div class="flex items-start justify-between">
                            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">{{ $service['num'] }}</p>
                            <span class="rounded border border-carbono/50 px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-carbono/70">{{ $service['tag'] }}</span>
                        </div>
                        <h3 class="mt-4 font-mono text-[28px] font-bold leading-[1.2] tracking-[-0.03em] text-carbono xl:text-[32px]">{{ $service['title'] }}</h3>
                        <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">{{ $service['text'] }}</p>
                    </div>
                    <div class="relative h-[240px] shrink-0 2xl:h-[300px]">
                        <img src="{{ asset('images/' . $service['img']) }}" alt="{{ $service['title'] }}" class="absolute inset-0 size-full object-cover">
                        <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
                        <div class="absolute bottom-0 right-0 flex size-[65px] items-center justify-center bg-white text-energia transition group-hover:bg-lima group-hover:text-carbono">
                            <x-ui.icon name="arrow-up-right" class="size-8" />
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- EVA POWERLAB --}}
    <section id="eva-powerlab" class="relative mt-24 xl:mt-[135px]">
        <div class="grid xl:grid-cols-[minmax(0,61%)_minmax(0,39%)]">
            <div class="relative overflow-hidden px-8 py-12 sm:px-12 xl:h-[473px] xl:px-[50px] xl:py-[62px]">
                <img src="{{ asset('images/eva-bg.jpg') }}" alt="" class="absolute inset-0 size-full object-cover">
                <div class="absolute inset-0 bg-carbono/75"></div>
                <div class="relative">
                    <span class="inline-flex rounded border border-lima px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-lima">serviços eva powerlab</span>
                    <div class="mt-12 flex max-w-[680px] flex-wrap gap-6">
                        @foreach ($evaPills as $pill)
                            <span class="rounded-full bg-lima px-[30px] py-[15px] text-lg font-semibold leading-[1.68] tracking-[-0.36px] text-tecnico">{{ $pill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="relative rounded-[32px] bg-lima px-8 py-12 sm:px-12 xl:-ml-10 xl:h-[473px] xl:px-16 xl:py-[54px]">
                <div class="absolute right-10 top-10 hidden size-[120px] items-center justify-center rounded-full bg-carbono text-lima xl:flex">
                    <x-ui.icon name="bolt" class="size-14" />
                </div>
                <h2 class="max-w-[480px] text-5xl font-bold leading-[1.2] tracking-[-0.03em] text-carbono xl:text-[62px]">EVA POWERLAB</h2>
                <p class="mt-4 max-w-[416px] font-mono text-2xl font-bold leading-[1.2] tracking-[-0.72px] text-carbono">O laboratório de mobilidade elétrica da GOCARMAT</p>
                <p class="mt-6 max-w-[416px] text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">
                    Diagnóstico, reparação e certificação de baterias de alta tensão — para BEV, HEV e PHEV de todas as marcas, ao abrigo do regulamento europeu (MV-BER 461/2010) e sem perder a garantia.
                </p>
                <div class="mt-8">
                    <x-pill variant="dark" :href="url('/eva-powerlab')">Conhecer o EVA Powerlab</x-pill>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/eva-car.png') }}" alt="" class="pointer-events-none absolute bottom-0 left-[38%] hidden w-[565px] 2xl:block">
    </section>

    {{-- OFICINAS --}}
    <section id="oficinas" class="mt-24 xl:mt-[120px]">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                4 oficinas - o mesmo cuidado
            </h2>
            <x-pill variant="outline-dark" :href="url('/marcacoes')">Marcar na Oficina mais próxima</x-pill>
        </div>
        @include('partials.offices-grid')
    </section>

    {{-- BLOG --}}
    <section id="blog" class="mt-24 xl:mt-[128px]">
        <div class="flex flex-wrap items-center justify-between gap-6">
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                Gocarmat blog
            </h2>
            <x-pill variant="outline-dark" :href="url('/blog')" mono>Ver todos os Artigos</x-pill>
        </div>

        @if ($posts->isNotEmpty())
            <div class="mt-14 grid gap-8 xl:grid-cols-12">
                {{-- Destaque --}}
                @php $featured = $posts->first(); @endphp
                <a href="{{ url('/blog/' . $featured->slug) }}" class="group xl:col-span-5">
                    @if ($featured->featured_image)
                        <div class="h-[384px] overflow-hidden">
                            <img src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                        </div>
                    @endif
                    <div class="bg-white px-10 py-9">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $featured->published_at?->translatedFormat('j F, Y') }}</p>
                        <h3 class="mt-3 text-[28px] font-bold leading-[1.2] tracking-[-0.03em] xl:text-[32px]">{{ $featured->title }}</h3>
                        <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($featured->excerpt, 140, ' ...') }}</p>
                    </div>
                </a>

                {{-- 2 cards horizontais --}}
                <div class="grid gap-8 xl:col-span-4">
                    @foreach ($posts->slice(1, 2) as $post)
                        <a href="{{ url('/blog/' . $post->slug) }}" class="group grid grid-cols-[minmax(0,42%)_minmax(0,58%)] overflow-hidden">
                            @if ($post->featured_image)
                                <div class="overflow-hidden">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="size-full min-h-[220px] object-cover transition duration-300 group-hover:scale-105">
                                </div>
                            @endif
                            <div class="bg-white px-7 py-8">
                                <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $post->published_at?->translatedFormat('j F, Y') }}</p>
                                <h3 class="mt-3 text-2xl font-bold leading-[1.2] tracking-[-0.03em] xl:text-[26px]">{{ $post->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Card vertical --}}
                @php $last = $posts->slice(3, 1)->first(); @endphp
                @if ($last)
                    <a href="{{ url('/blog/' . $last->slug) }}" class="group xl:col-span-3">
                        @if ($last->featured_image)
                            <div class="h-[288px] overflow-hidden">
                                <img src="{{ asset('storage/' . $last->featured_image) }}" alt="{{ $last->title }}" class="size-full object-cover transition duration-300 group-hover:scale-105">
                            </div>
                        @endif
                        <div class="bg-white px-8 py-8">
                            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px]">{{ $last->published_at?->translatedFormat('j F, Y') }}</p>
                            <h3 class="mt-3 text-[28px] font-bold leading-[1.2] tracking-[-0.03em]">{{ $last->title }}</h3>
                            <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ \Illuminate\Support\Str::limit($last->excerpt, 200, ' ...') }}</p>
                        </div>
                    </a>
                @endif
            </div>
        @endif
    </section>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
