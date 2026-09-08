{{-- Hero de página de serviço individual: breadcrumb, 2 botões, imagem com faixa de destaque --}}
@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'energia'); @endphp

<section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,33%)_minmax(0,67%)]">
    <div class="{{ $f['sec'] }} px-8 py-14 sm:px-12 xl:px-12 xl:py-20">
        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ ($data['fundo'] ?? 'energia') === 'carbono' ? 'text-lima' : 'text-gelo' }}">
            <a href="{{ $data['breadcrumb_link'] ?? route('services') }}" class="transition hover:text-white">{{ $data['breadcrumb_pai'] ?? 'Serviços' }}</a>
            / {{ $data['breadcrumb_atual'] ?? $data['titulo'] }}
        </p>

        <h1 class="mt-6 max-w-[520px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] {{ $f['titulo'] }} sm:text-5xl">
            {{ $data['titulo'] }}
        </h1>

        @if (filled($data['texto'] ?? null))
            <p class="mt-6 max-w-[480px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $f['texto'] }}">
                {{ $data['texto'] }}
            </p>
        @endif

        @if (filled($data['botoes'] ?? null))
            <div class="mt-8 flex flex-wrap gap-3">
                @foreach ($data['botoes'] as $botao)
                    <x-pill :variant="$loop->first ? 'lima' : 'outline-light'" :href="$botao['link']">{{ $botao['texto'] }}</x-pill>
                @endforeach
            </div>
        @endif
    </div>

    <div class="relative min-h-[300px]">
        <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null) }}" alt="{{ $data['titulo'] }}" class="absolute inset-0 size-full object-cover">

        @if (filled($data['faixa_numero'] ?? null))
            <div class="absolute inset-x-0 bottom-0 flex flex-wrap items-center gap-4 bg-carbono/90 px-6 py-5 sm:px-10">
                <p class="shrink-0 font-mono text-3xl font-extrabold uppercase leading-none tracking-[-0.03em] text-lima sm:text-4xl">{{ $data['faixa_numero'] }}</p>
                @if (filled($data['faixa_texto'] ?? null))
                    <p class="max-w-[420px] text-sm font-light leading-[1.5] text-gelo">{{ $data['faixa_texto'] }}</p>
                @endif
            </div>
        @endif
    </div>
</section>
