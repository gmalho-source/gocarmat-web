{{-- Faixa de CTA com ícone circular (recolha/entrega, marcação EVA) --}}
@php
    $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'carbono');
    $corIcone = ($data['cor_icone'] ?? 'energia') === 'lima' ? 'bg-lima text-carbono' : 'bg-energia text-white';
    $colado = (bool) ($data['colar_ao_rodape'] ?? false);
@endphp

<section class="mt-16 flex flex-col items-start gap-8 px-8 py-12 sm:px-12 xl:mt-24 xl:flex-row xl:items-center xl:justify-between xl:px-24 xl:py-14 {{ $f['sec'] }} {{ $colado ? 'rounded-t-[32px]' : 'rounded-[32px]' }}">
    <div class="flex items-center gap-8">
        @if (filled($data['icone'] ?? null))
            <div class="flex size-[90px] shrink-0 items-center justify-center rounded-full xl:size-[110px] {{ $corIcone }}">
                <x-ui.icon :name="$data['icone']" class="size-12" />
            </div>
        @endif
        <div>
            <h2 class="font-mono text-2xl font-bold uppercase leading-[1.2] tracking-[-0.03em] sm:text-3xl {{ ($data['cor_titulo'] ?? '') === 'energia' ? 'text-energia' : $f['titulo'] }}">
                {{ $data['titulo'] }}
            </h2>
            @if (filled($data['texto'] ?? null))
                <p class="mt-2 max-w-[640px] text-base font-light leading-[1.68] {{ $f['texto'] }}">{{ $data['texto'] }}</p>
            @endif
        </div>
    </div>

    @if (filled($data['botao_texto'] ?? null))
        <x-pill :variant="\App\Support\Blocos::escuro($data['fundo'] ?? 'carbono') ? 'lima' : 'dark'" :href="($data['botao_link'] ?? null) ?: route('marcacoes')">{{ $data['botao_texto'] }}</x-pill>
    @endif
</section>
