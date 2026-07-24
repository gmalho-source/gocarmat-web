@extends('layouts.site')

@section('title', 'EVA Powerlab — Assistência a veículos elétricos · GOCARMAT')
@section('meta_description', 'O laboratório de mobilidade elétrica da GOCARMAT: diagnóstico, reparação e certificação de baterias de alta tensão para BEV, HEV e PHEV, ao abrigo do regulamento MV-BER 461/2010.')

@php
    $evaServices = [
        ['tag' => 'lab', 'icon' => 'bolt', 'title' => 'EVA LAB', 'text' => 'Diagnóstico ao sistema de gestão da bateria (BMS), módulo a módulo. Descobrimos o estado de saúde real (SoH) do pack.'],
        ['tag' => 'resgate', 'icon' => 'car-burst', 'title' => 'RESCUE', 'text' => 'Desbloqueio e assistência a elétricos imobilizados. Resposta rápida para voltar a pôr o carro na estrada.'],
        ['tag' => 'segurança', 'icon' => 'car-burst', 'title' => 'EVA COLLISION', 'text' => 'Colisão e pintura para elétricos e híbridos, com os cuidados de alta tensão que estes veículos exigem.'],
        ['tag' => 'tis', 'icon' => 'bolt', 'title' => 'TESLA INDEPENDENT SERVICE', 'text' => 'Manutenção e reparação de Tesla com equipamento dedicado. Ao nível da marca, sem depender da marca.'],
        ['tag' => 'garantia', 'icon' => 'shield', 'title' => 'EVA BATTERY WARRANTY', 'text' => 'Garantia até 5 anos nas baterias recuperadas e certificadas no EVA Powerlab.'],
        ['tag' => 'mv-ber', 'icon' => 'certificate', 'title' => 'CERTIFICAÇÃO MV-BER', 'text' => 'Certificamos a saúde da bateria e registamos a intervenção no Livro de Manutenção Digital. Prova e valor de revenda.'],
    ];

    $faqs = [
        ['q' => 'Posso fazer a manutenção do meu elétrico fora da marca?', 'a' => 'Sim. Ao abrigo do regulamento europeu MV-BER 461/2010, pode fazer a manutenção em oficinas independentes qualificadas sem perder a garantia — desde que sejam seguidos os planos do fabricante, como fazemos na EVA Powerlab.', 'open' => true],
        ['q' => 'Que cuidados têm com baterias de alta tensão?', 'a' => 'Todas as intervenções em alta tensão são executadas por técnicos com formação e certificação próprias, com equipamento dedicado e protocolos de segurança específicos — e ficam registadas no Livro de Manutenção Digital.', 'open' => false],
        ['q' => 'Fazem assistência a Tesla?', 'a' => 'Sim. Com o Tesla Independent Service fazemos manutenção e reparação de Tesla com equipamento dedicado, ao nível da marca — sem depender da marca.', 'open' => false],
        ['q' => 'A garantia da bateria pode ser estendida?', 'a' => 'Sim. As baterias recuperadas e certificadas no EVA Powerlab incluem garantia até 5 anos.', 'open' => false],
    ];
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- HERO --}}
    <section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,58%)_minmax(0,42%)]">
        <div class="bg-carbono px-8 py-14 sm:px-12 xl:px-24 xl:py-[110px]">
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-lima">
                EVA Powerlab &mdash; Assistência a veículos eletrificados
            </p>
            <h1 class="mt-8 max-w-[900px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] text-white sm:text-5xl 2xl:text-7xl">
                O seu elétrico tem oficina <span class="text-lima">fora da marca.</span>
            </h1>
            <p class="mt-8 max-w-[720px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                O EVA Powerlab é o laboratório de mobilidade elétrica da GOCARMAT: diagnóstico, reparação e certificação de baterias de alta tensão (BEV, HEV e PHEV). Ao abrigo do regulamento europeu MV-BER 461/2010, mantém a garantia — sem depender da marca.
            </p>
            <div class="mt-10">
                <x-pill variant="lima" :href="route('marcacoes')">Marcação EVA</x-pill>
            </div>
        </div>
        <div class="relative min-h-[320px]">
            <img src="{{ asset('images/eva-hero.jpg') }}" alt="Carregamento de um veículo elétrico" class="absolute inset-0 size-full object-cover">
        </div>
    </section>

    {{-- SERVIÇOS EVA --}}
    <section class="mt-20 grid gap-x-8 gap-y-14 sm:grid-cols-2 xl:mt-24 xl:gap-x-10">
        @foreach ($evaServices as $service)
            <div class="relative bg-white px-10 pb-11 pt-10">
                <div class="absolute -top-9 right-10 flex size-[80px] items-center justify-center bg-lima text-energia">
                    <x-ui.icon :name="$service['icon']" class="size-10" />
                </div>
                <span class="inline-flex rounded-lg bg-carbono px-4 pb-[7px] pt-2 font-mono text-[12px] font-bold uppercase leading-none tracking-[0.36px] text-lima">{{ $service['tag'] }}</span>
                <h2 class="mt-5 max-w-[85%] font-mono text-[26px] font-bold uppercase leading-[1.2] tracking-[-0.03em] text-energia">{{ $service['title'] }}</h2>
                <p class="mt-3 max-w-[560px] text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $service['text'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- PORQUÊ + FAQS --}}
    <section class="mt-20 grid gap-10 xl:mt-28 xl:grid-cols-[minmax(0,46%)_minmax(0,54%)] xl:gap-14">
        <div class="flex flex-col overflow-hidden rounded-[32px] bg-lima px-8 pt-12 sm:px-12 xl:px-14 xl:pt-16">
            <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-carbono sm:text-4xl">
                Porquê o EVA Powerlab?
            </h2>
            <p class="mt-6 max-w-[560px] text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono sm:text-lg">
                Trabalhar em alta tensão exige formação, equipamento e certificação próprios. Na EVA, cada intervenção fica registada no Livro de Manutenção Digital (LMD).
            </p>
            <img src="{{ asset('images/eva-car-profile.png') }}" alt="Carro elétrico" class="mt-auto w-full max-w-[640px] translate-y-2 self-center pt-8">
        </div>
        <div>
            <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl">
                Perguntas frequentes
            </h2>
            <div class="mt-8 space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-2xl bg-white open:bg-carbono" {{ $faq['open'] ? 'open' : '' }}>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-8 py-6 text-lg font-bold tracking-[-0.3px] text-carbono group-open:text-lima">
                            {{ $faq['q'] }}
                            <span class="shrink-0 text-2xl leading-none text-energia transition group-open:rotate-45 group-open:text-lima" aria-hidden="true">+</span>
                        </summary>
                        <p class="px-8 pb-7 text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                            {{ $faq['a'] }}
                        </p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- BANDA MARCAÇÃO --}}
    <section class="mt-16 flex flex-col items-start gap-8 rounded-t-[32px] bg-carbono px-8 py-12 sm:px-12 xl:mt-24 xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-16">
        <div class="flex items-center gap-8">
            <div class="flex size-[90px] shrink-0 items-center justify-center rounded-full bg-lima text-carbono xl:size-[120px]">
                <x-ui.icon name="bolt" class="size-12" />
            </div>
            <div>
                <h2 class="font-mono text-3xl font-bold uppercase leading-[1.2] tracking-[-0.03em] text-white xl:text-4xl">Marcação EVA</h2>
                <p class="mt-2 max-w-[620px] text-lg font-bold leading-[1.3] tracking-[-0.3px] text-gelo">
                    Diagnóstico, reparação e certificação de baterias — marque já a sua avaliação.
                </p>
            </div>
        </div>
        <x-pill variant="lima" :href="route('marcacoes')">Marcar agora</x-pill>
    </section>
</div>
@endsection
