@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'carbono'); @endphp
@php $escuro = in_array($data['fundo'] ?? 'carbono', ['carbono', 'energia']); @endphp

<section class="mt-16 flex flex-col items-start gap-8 rounded-[32px] px-8 py-12 sm:px-12 xl:mt-24 xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-16 {{ $f['sec'] }}">
    <div>
        <h2 class="font-mono text-2xl font-bold uppercase leading-[1.2] tracking-[-0.03em] sm:text-3xl {{ $f['titulo'] }}">
            {{ $data['titulo'] }}
        </h2>
        @if (filled($data['texto'] ?? null))
            <p class="mt-3 max-w-[640px] text-lg font-bold leading-[1.3] tracking-[-0.3px] {{ $f['texto'] }}">
                {{ $data['texto'] }}
            </p>
        @endif
    </div>

    @if (filled($data['botao_texto'] ?? null))
        <x-pill :variant="$escuro ? 'lima' : 'dark'" :href="($data['botao_link'] ?? null) ?: '#'">{{ $data['botao_texto'] }}</x-pill>
    @endif
</section>
