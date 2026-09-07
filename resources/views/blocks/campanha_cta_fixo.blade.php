{{-- Barra de conversão fixa no fundo do ecrã, só nas páginas de campanha.
     Escondida até se fazer scroll, e desaparece quando o CTA real (antes do
     footer) entra em vista — o JS trata disto em resources/js/app.js. --}}
@php
    $corIcone = ($data['cor_icone'] ?? 'energia') === 'lima' ? 'bg-lima text-carbono' : 'bg-energia text-white';
@endphp

<div data-cta-fixo class="hidden fixed inset-x-0 bottom-0 z-50 items-center justify-between gap-4 bg-carbono px-6 py-4 shadow-[0_-8px_24px_rgba(0,0,0,0.25)] sm:px-10">
    <div class="flex min-w-0 items-center gap-4">
        @if (filled($data['icone'] ?? null))
            <div class="flex size-11 shrink-0 items-center justify-center rounded-full sm:size-12 {{ $corIcone }}">
                <x-ui.icon :name="$data['icone']" class="size-6" />
            </div>
        @endif
        <div class="min-w-0">
            <p class="truncate font-mono text-sm font-bold uppercase tracking-[-0.16px] text-lima sm:text-base">{{ $data['titulo'] ?? '' }}</p>
            @if (filled($data['texto'] ?? null))
                <p class="mt-0.5 hidden truncate text-sm text-gelo sm:block">{{ $data['texto'] }}</p>
            @endif
        </div>
    </div>
    <x-pill variant="lima" :href="($data['botao_link'] ?? null) ?: route('marcacoes')" class="shrink-0">{{ $data['botao_texto'] ?? 'Marcar Agora' }}</x-pill>
</div>
