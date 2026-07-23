@extends('layouts.site')

@section('title', 'Sobre Nós — GOCARMAT · Uma rede de oficinas 100% portuguesa')
@section('meta_description', 'A GOCARMAT é uma rede de oficinas multimarca com 16 anos de experiência, capitais 100% portugueses e um laboratório próprio de mobilidade elétrica, o EVA Powerlab.')

@php
    $values = [
        ['num' => '01', 'title' => 'Transparência', 'text' => 'Sabe antecipadamente o valor a pagar, pode assistir a todas as reparações e é aconselhado pelos nossos técnicos.'],
        ['num' => '02', 'title' => 'Inovação e confiança', 'text' => 'Toda a informação é prestada de forma simples, para que a compreenda da melhor maneira possível.'],
        ['num' => '03', 'title' => 'Qualidade certificada', 'text' => 'Escolhemos peças dos melhores fabricantes e registamos cada intervenção. Qualidade que se comprova.'],
        ['num' => '04', 'title' => 'Rapidez e eficiência', 'text' => 'Cada viatura tem uma ficha de diagnóstico que melhora continuamente os tempos de reparação.'],
        ['num' => '05', 'title' => 'Bem-estar dos clientes', 'text' => 'Podemos ir buscar e entregar a viatura em casa ou no local de trabalho. Contacte-nos para saber mais.'],
    ];
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- HERO --}}
    <section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,55%)_minmax(0,45%)]">
        <div class="bg-carbono px-8 py-14 sm:px-12 xl:px-24 xl:py-[110px]">
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">
                Quem somos
            </p>
            <h1 class="mt-8 max-w-[700px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] text-white sm:text-5xl 2xl:text-7xl">
                Uma rede de oficinas 100% portuguesa.
            </h1>
            <p class="mt-8 max-w-[620px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                A GOCARMAT é uma rede de oficinas multimarca com 16 anos de experiência, a operar na Grande Lisboa e a crescer para todo o país. Capitais 100% portugueses, uma equipa técnica de referência — e um laboratório próprio de mobilidade elétrica, o EVA Powerlab.
            </p>
            <div class="mt-10 flex flex-wrap gap-4">
                <x-pill variant="outline-light" :href="route('marcacoes')">Marcar Serviço</x-pill>
                <x-pill variant="outline-light" :href="url('/servicos')">Conheça os nossos Serviços</x-pill>
            </div>
        </div>
        <div class="relative min-h-[320px]">
            <img src="{{ asset('images/servico-revisao.jpg') }}" alt="Oficina GOCARMAT" class="absolute inset-0 size-full object-cover">
            <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
            <div class="absolute bottom-10 left-8 w-[min(420px,85%)] rounded-[24px] bg-lima px-10 py-8 xl:left-[-40px]">
                <p class="font-mono text-4xl font-extrabold uppercase leading-[1.1] tracking-[-0.03em] text-carbono sm:text-[44px]">4 oficinas</p>
                <p class="mt-2 text-lg font-bold leading-[1.3] tracking-[-0.3px] text-carbono">na Grande Lisboa — e a crescer para todo o país.</p>
            </div>
        </div>
    </section>

    {{-- MISSÃO / VISÃO --}}
    <section class="mt-6 grid overflow-hidden rounded-[32px] lg:grid-cols-2">
        <div class="bg-energia px-8 py-14 sm:px-12 xl:px-24 xl:py-20">
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-white sm:text-[52px]">Missão</h2>
            <p class="mt-6 max-w-[560px] text-lg font-light leading-[1.68] tracking-[-0.18px] text-gelo">
                Contribuir para a segurança rodoviária e o aumento da vida útil dos veículos, através de serviços de excelência na manutenção automóvel.
            </p>
        </div>
        <div class="bg-tecnico px-8 py-14 sm:px-12 xl:px-24 xl:py-20">
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-white sm:text-[52px]">Visão</h2>
            <p class="mt-6 max-w-[560px] text-lg font-light leading-[1.68] tracking-[-0.18px] text-gelo">
                Ser o parceiro de referência de particulares e empresas na manutenção do automóvel — de todas as marcas, do combustão ao elétrico.
            </p>
        </div>
    </section>

    {{-- VALORES --}}
    <section class="mt-24 xl:mt-[110px]">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
            Os nossos valores
        </h2>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3 xl:gap-8">
            @foreach ($values as $value)
                <div class="border border-energia bg-cloud px-9 py-10">
                    <span class="inline-flex rounded bg-energia px-3.5 py-1.5 font-mono text-[13px] font-bold text-white">{{ $value['num'] }}</span>
                    <h3 class="mt-5 font-mono text-2xl font-bold leading-[1.2] tracking-[-0.03em]">{{ $value['title'] }}</h3>
                    <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $value['text'] }}</p>
                </div>
            @endforeach
            <div class="flex flex-col justify-center bg-lima px-9 py-10">
                <h3 class="font-mono text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-carbono">Horário alargado</h3>
                <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">Segunda a sexta das 9h às 19h e sábados das 9h às 13h.</p>
                <a href="{{ route('marcacoes') }}" class="mt-6 inline-flex w-fit items-center gap-3 rounded-full bg-carbono px-7 py-3 text-[15px] font-semibold text-lima transition hover:opacity-85">
                    Marcar Serviço
                    <x-ui.icon name="arrow-right" class="size-5" />
                </a>
            </div>
        </div>
    </section>

    {{-- OFICINAS --}}
    <section class="mt-24 xl:mt-[110px]">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
            4 oficinas - o mesmo cuidado
        </h2>
        @include('partials.offices-grid')
    </section>

    {{-- RECOLHA E ENTREGA --}}
    <section class="mt-20 flex flex-col items-start gap-8 rounded-[32px] bg-carbono px-8 py-12 sm:px-12 xl:mt-[110px] xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-14">
        <div class="flex items-center gap-8">
            <div class="flex size-[90px] shrink-0 items-center justify-center rounded-full bg-energia text-white xl:size-[110px]">
                <x-ui.icon name="car-burst" class="size-12" />
            </div>
            <div>
                <h2 class="font-mono text-2xl font-bold uppercase leading-[1.2] tracking-[-0.03em] text-energia sm:text-3xl">Vamos buscar e entregar o seu carro</h2>
                <p class="mt-2 max-w-[640px] text-base font-light leading-[1.68] text-gelo">
                    Recolhemos e entregamos a sua viatura em casa ou no local de trabalho — contacte-nos para saber mais.
                </p>
            </div>
        </div>
        <x-pill variant="lima" :href="route('marcacoes')">Marcar Serviço</x-pill>
    </section>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
