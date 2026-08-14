{{-- Hero de página interior: painel de cor + imagem, com cartão opcional sobreposto --}}
@php
    $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'carbono');
    $escuro = \App\Support\Blocos::escuro($data['fundo'] ?? 'carbono');
    $proporcao = $data['proporcao'] ?? '58';
@endphp

<section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,{{ $proporcao }}%)_minmax(0,{{ 100 - (int) $proporcao }}%)]">
    <div class="{{ $f['sec'] }} px-8 py-14 sm:px-12 xl:px-24 xl:py-[110px]">
        @if (filled($data['eyebrow'] ?? null))
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ $escuro ? 'text-lima' : 'text-energia' }}">
                {{ $data['eyebrow'] }}
            </p>
        @endif

        <h1 class="mt-8 max-w-[900px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] sm:text-5xl 2xl:text-7xl {{ $f['titulo'] }}">
            @if (filled($data['titulo_destaque'] ?? null))
                {{ $data['titulo'] }} <span class="text-lima">{{ $data['titulo_destaque'] }}</span>
            @else
                {{ $data['titulo'] }}
            @endif
        </h1>

        @if (filled($data['texto'] ?? null))
            <p class="mt-8 max-w-[720px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $f['texto'] }}">
                {{ $data['texto'] }}
            </p>
        @endif

        @if (filled($data['botoes'] ?? null))
            <div class="mt-10 flex flex-wrap gap-4">
                @foreach ($data['botoes'] as $botao)
                    <x-pill :variant="$escuro ? ($loop->first ? 'lima' : 'outline-light') : 'outline-dark'" :href="$botao['link']">{{ $botao['texto'] }}</x-pill>
                @endforeach
            </div>
        @endif
    </div>

    <div class="relative min-h-[300px]">
        <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null) }}" alt="{{ $data['titulo'] }}" class="absolute inset-0 size-full object-cover">
        @if (($data['overlay_azul'] ?? false))
            <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
        @endif

        @if (filled($data['cartao_titulo'] ?? null))
            <div class="absolute bottom-10 left-8 w-[min(420px,85%)] rounded-[24px] bg-lima px-10 py-8 xl:left-[-40px]">
                <p class="font-mono text-4xl font-extrabold uppercase leading-[1.1] tracking-[-0.03em] text-carbono sm:text-[44px]">{{ $data['cartao_titulo'] }}</p>
                @if (filled($data['cartao_texto'] ?? null))
                    <p class="mt-2 text-lg font-bold leading-[1.3] tracking-[-0.3px] text-carbono">{{ $data['cartao_texto'] }}</p>
                @endif
            </div>
        @endif
    </div>
</section>
