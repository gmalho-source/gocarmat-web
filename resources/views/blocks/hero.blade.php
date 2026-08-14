@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'energia'); @endphp

<section class="mt-7 grid overflow-hidden rounded-[32px] {{ $f['sec'] }} {{ ($data['imagem'] ?? null) ? 'lg:grid-cols-[minmax(0,58%)_minmax(0,42%)]' : '' }}">
    <div class="px-8 py-14 sm:px-12 xl:px-24 xl:py-[110px]">
        @if (filled($data['eyebrow'] ?? null))
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ $f['texto'] }}">
                {{ $data['eyebrow'] }}
            </p>
        @endif

        <h1 class="mt-8 max-w-[900px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] sm:text-5xl 2xl:text-7xl {{ $f['titulo'] }}">
            {{ $data['titulo'] }}
        </h1>

        @if (filled($data['texto'] ?? null))
            <p class="mt-8 max-w-[720px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $f['texto'] }}">
                {{ $data['texto'] }}
            </p>
        @endif

        @if (filled($data['botoes'] ?? null))
            <div class="mt-10 flex flex-wrap gap-4">
                @foreach ($data['botoes'] as $botao)
                    <x-pill :variant="$loop->first ? (($data['fundo'] ?? '') === 'lima' ? 'dark' : 'lima') : (in_array($data['fundo'] ?? '', ['energia','carbono']) ? 'outline-light' : 'outline-dark')"
                            :href="$botao['link']">{{ $botao['texto'] }}</x-pill>
                @endforeach
            </div>
        @endif
    </div>

    @if ($data['imagem'] ?? null)
        <div class="relative min-h-[320px]">
            <img src="{{ asset('storage/'.$data['imagem']) }}" alt="{{ $data['titulo'] }}" class="absolute inset-0 size-full object-cover">
        </div>
    @endif
</section>
