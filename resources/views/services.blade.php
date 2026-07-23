@extends('layouts.site')

@section('title', 'Serviços — GOCARMAT · Tudo o que o seu carro precisa, num só lugar')
@section('meta_description', 'Serviço 360 GOCARMAT: revisão oficial, inspeção, pneus, colisão e pintura, climatização, óleo e filtros — com orçamentos grátis e check-up gratuito.')

@php
    // variant: gelo | branco | azul
    $services = [
        ['num' => '01.', 'tag' => 'manutenção', 'variant' => 'gelo', 'title' => 'Revisão Oficial', 'img' => 'servico-revisao.jpg',
         'text' => 'Já é possível fazer as revisões oficiais em oficina multimarca, mantendo a garantia do fabricante — com registo completo da intervenção.',
         'bullets' => ['Plano de revisão do fabricante', 'Peças de qualidade equivalente', 'Check-up gratuito incluído']],
        ['num' => '02.', 'tag' => 'comodidade', 'variant' => 'branco', 'title' => 'Inspeção', 'img' => 'servico-inspecao.jpg',
         'text' => 'A pensar na sua comodidade, acompanhamos o seu carro ao centro de inspeção — deixa o carro connosco e nós tratamos de tudo.',
         'bullets' => ['Acompanhamento ao centro', 'Pré-inspeção incluída', 'Resolução de anomalias no próprio dia']],
        ['num' => '03.', 'tag' => 'segurança', 'variant' => 'azul', 'title' => 'Pneus', 'img' => 'servico-pneus.jpg',
         'text' => 'Pneus das melhores marcas ao melhor preço, com alinhamento de direção e equilibragem incluídos.',
         'bullets' => ['Todas as marcas e medidas', 'Alinhamento e equilibragem', 'Verificação gratuita de desgaste']],
        ['num' => '04.', 'tag' => 'carroçaria', 'variant' => 'branco', 'title' => 'Colisão e Pintura', 'img' => 'servico-colisao.jpg',
         'text' => 'O nosso moderno Centro de Colisão e Pintura repara todo o tipo de danos de carroçaria — deixou de haver justificação para andar com mossas e riscos.',
         'bullets' => ['Orçamento gratuito', 'Gestão do processo com seguradoras', 'Pintura com acabamento de fábrica']],
        ['num' => '05.', 'tag' => 'conforto', 'variant' => 'gelo', 'title' => 'Climatização', 'img' => 'servico-climatizacao.jpg',
         'text' => 'Manutenção completa do sistema de climatização e ar condicionado, para viajar com conforto e ar saudável em todas as estações.',
         'bullets' => ['Carga e verificação de gás', 'Higienização do habitáculo', 'Deteção e reparação de fugas']],
        ['num' => '06.', 'tag' => 'manutenção', 'variant' => 'azul', 'title' => 'Óleo, Filtros e Mecânica', 'img' => 'servico-oleo.jpg',
         'text' => 'Mudança de óleo com todos os filtros (óleo, combustível, ar e habitáculo) e mecânica geral de reparação.',
         'bullets' => ['Óleos homologados pelo fabricante', 'Todos os filtros incluídos', 'Mecânica e diagnóstico geral']],
    ];

    $variants = [
        'gelo' => ['card' => 'bg-gelo', 'text' => 'text-carbono', 'muted' => 'text-carbono', 'num' => 'text-energia', 'tag' => 'border-carbono/50 text-carbono/70', 'check' => 'text-energia'],
        'branco' => ['card' => 'bg-white', 'text' => 'text-carbono', 'muted' => 'text-carbono', 'num' => 'text-energia', 'tag' => 'border-carbono/50 text-carbono/70', 'check' => 'text-energia'],
        'azul' => ['card' => 'bg-energia', 'text' => 'text-white', 'muted' => 'text-gelo', 'num' => 'text-gelo', 'tag' => 'border-gelo/60 text-gelo', 'check' => 'text-lima'],
    ];
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- HERO --}}
    <section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,62%)_minmax(0,38%)]">
        <div class="bg-energia px-8 py-14 sm:px-12 xl:px-24 xl:py-[100px]">
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-gelo">
                Os nossos serviços
            </p>
            <h1 class="mt-8 max-w-[780px] text-4xl font-bold leading-[1.1] tracking-[-0.03em] text-white sm:text-5xl 2xl:text-7xl">
                Tudo o que o seu carro precisa, num só lugar.
            </h1>
            <p class="mt-8 max-w-[640px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                Serviço 360: seja qual for o problema, a GOCARMAT resolve — com orçamentos grátis e check-up gratuito.
            </p>
        </div>
        <div class="relative min-h-[280px]">
            <img src="{{ asset('images/servico-inspecao.jpg') }}" alt="Ferramentas de oficina" class="absolute inset-0 size-full object-cover">
        </div>
    </section>

    {{-- SERVIÇOS DETALHADOS --}}
    <section class="mt-16 grid gap-8 xl:mt-20 xl:grid-cols-2 xl:gap-10">
        @foreach ($services as $service)
            @php $v = $variants[$service['variant']]; @endphp
            <a href="{{ route('marcacoes') }}" class="group flex flex-col overflow-hidden transition hover:-translate-y-1">
                <div class="flex-1 px-9 pb-10 pt-9 {{ $v['card'] }}">
                    <div class="flex items-start justify-between gap-4">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ $v['num'] }}">{{ $service['num'] }}</p>
                        <span class="rounded border px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] {{ $v['tag'] }}">{{ $service['tag'] }}</span>
                    </div>
                    <h2 class="mt-4 font-mono text-[28px] font-bold leading-[1.2] tracking-[-0.03em] xl:text-[32px] {{ $v['text'] }}">{{ $service['title'] }}</h2>
                    <p class="mt-4 max-w-[600px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $v['muted'] }}">{{ $service['text'] }}</p>
                    <ul class="mt-7 space-y-3">
                        @foreach ($service['bullets'] as $bullet)
                            <li class="flex items-center gap-3 text-base font-bold tracking-[-0.16px] {{ $v['text'] }}">
                                <svg class="size-5 shrink-0 {{ $v['check'] }}" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <circle cx="10" cy="10" r="8.2" />
                                    <path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $bullet }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="relative h-[280px] shrink-0 xl:h-[340px]">
                    <img src="{{ asset('images/' . $service['img']) }}" alt="{{ $service['title'] }}" class="absolute inset-0 size-full object-cover" loading="lazy">
                    <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
                    <div class="absolute bottom-0 right-0 flex size-[65px] items-center justify-center bg-white text-energia transition group-hover:bg-lima group-hover:text-carbono">
                        <x-ui.icon name="arrow-up-right" class="size-8" />
                    </div>
                </div>
            </a>
        @endforeach
    </section>

    {{-- BANDA EVA --}}
    <section class="mt-16 flex flex-col items-start gap-8 rounded-[32px] bg-lima px-8 py-12 sm:px-12 xl:mt-24 xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-16">
        <div>
            <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-carbono sm:text-4xl">Tem um elétrico ou híbrido?</h2>
            <p class="mt-3 max-w-[640px] text-lg font-bold leading-[1.3] tracking-[-0.3px] text-carbono">
                Conte com a assistência especializada da GOCARMAT.
            </p>
        </div>
        <x-pill variant="dark" :href="route('eva')">Conhecer o EVA Powerlab</x-pill>
    </section>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
