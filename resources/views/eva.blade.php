@extends('layouts.site')

@section('title', 'EVA Powerlab — Assistência a veículos elétricos · GOCARMAT')
@section('meta_description', 'O laboratório de mobilidade elétrica da GOCARMAT: diagnóstico, reparação e certificação de baterias de alta tensão para BEV, HEV e PHEV, ao abrigo do regulamento MV-BER 461/2010.')

@php
    $evaServices = [
        ['tag' => 'lab', 'icon' => 'bolt', 'title' => 'EVA LAB', 'text' => 'Diagnóstico ao sistema de gestão da bateria (BMS), módulo a módulo. Descobrimos o estado de saúde real (SoH) do pack.'],
        ['tag' => 'segurança', 'icon' => 'car-burst', 'title' => 'EVA COLLISION', 'text' => 'Colisão e pintura para elétricos e híbridos, com os cuidados de alta tensão que estes veículos exigem.'],
        ['tag' => 'assistência', 'icon' => 'unlock', 'title' => 'RESCUE — DESBLOQUEIO EV', 'text' => 'Assistência a veículos elétricos imobilizados ou bloqueados, com intervenção segura em alta tensão.'],
        ['tag' => 'manutenção', 'icon' => 'wrench', 'title' => 'TESLA INDEPENDENT SERVICE', 'text' => 'Manutenção e reparação independente para Tesla, com diagnóstico especializado e registo completo da intervenção.'],
        ['tag' => 'garantia', 'icon' => 'shield', 'title' => 'BATTERY WARRANTY · ATÉ 5 ANOS', 'text' => 'As intervenções à bateria de alta tensão incluem garantia até 5 anos, para ficar tranquilo depois da reparação.'],
        ['tag' => 'certificação', 'icon' => 'certificate', 'title' => 'CERTIFICAÇÃO MV-BER', 'text' => 'Intervenções ao abrigo do regulamento europeu MV-BER 461/2010 — o seu carro mantém a garantia do fabricante.'],
    ];
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- HERO --}}
    <section class="mt-7 overflow-hidden rounded-[32px] bg-carbono px-8 py-14 sm:px-12 xl:px-24 xl:py-[110px]">
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
    </section>

    {{-- SERVIÇOS EVA --}}
    <section class="mt-16 grid gap-6 sm:grid-cols-2 xl:mt-20 xl:grid-cols-3 xl:gap-8">
        @foreach ($evaServices as $service)
            <div class="relative bg-white px-9 pb-12 pt-10">
                <div class="absolute right-0 top-0 flex size-[75px] items-center justify-center bg-lima text-energia">
                    <x-ui.icon :name="$service['icon']" class="size-9" />
                </div>
                <span class="inline-flex rounded bg-carbono px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-lima">{{ $service['tag'] }}</span>
                <h2 class="mt-6 max-w-[80%] font-mono text-2xl font-bold uppercase leading-[1.2] tracking-[-0.03em] text-energia">{{ $service['title'] }}</h2>
                <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $service['text'] }}</p>
            </div>
        @endforeach
    </section>

    {{-- PORQUÊ --}}
    <section class="relative mt-16 overflow-hidden rounded-[32px] bg-lima px-8 pb-0 pt-14 sm:px-12 xl:mt-24 xl:px-24 xl:pt-[100px]">
        <h2 class="max-w-[900px] font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-carbono sm:text-[52px]">
            Porquê o EVA Powerlab
        </h2>
        <p class="mt-8 max-w-[820px] text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono sm:text-lg">
            Trabalhar em alta tensão exige formação, equipamento e certificação próprios. No EVA Powerlab, cada intervenção segue protocolos de segurança e fica registada no Livro de Manutenção do veículo — com selo de qualidade MV-BER.
        </p>
        <img src="{{ asset('images/eva-car.png') }}" alt="Carro elétrico em carregamento" class="mx-auto mt-10 w-[720px] max-w-full">
    </section>

    {{-- BANDA MARCAÇÃO --}}
    <section class="mt-16 flex flex-col items-start gap-8 rounded-[32px] bg-carbono px-8 py-12 sm:px-12 xl:mt-24 xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-16">
        <div class="flex items-center gap-8">
            <div class="flex size-[90px] shrink-0 items-center justify-center rounded-full bg-lima text-carbono xl:size-[120px]">
                <x-ui.icon name="bolt" class="size-12" />
            </div>
            <div>
                <h2 class="font-mono text-3xl font-bold uppercase leading-[1.2] tracking-[-0.03em] text-white xl:text-4xl">Marcação EVA</h2>
                <p class="mt-2 max-w-[560px] text-lg font-bold leading-[1.3] tracking-[-0.3px] text-gelo">
                    Diagnóstico, reparação e certificação de baterias de alta tensão — marque já a sua visita.
                </p>
            </div>
        </div>
        <x-pill variant="lima" :href="route('marcacoes')">Marcar agora</x-pill>
    </section>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
