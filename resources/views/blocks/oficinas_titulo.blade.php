{{-- Secção de oficinas com título e botão (versão da Home / Sobre Nós) --}}
<section class="mt-24 xl:mt-[120px]">
    <div class="flex flex-wrap items-center justify-between gap-6">
        @if (filled($data['titulo'] ?? null))
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                {{ $data['titulo'] }}
            </h2>
        @endif
        @if (filled($data['botao_texto'] ?? null))
            <x-pill variant="outline-dark" :href="($data['botao_link'] ?? null) ?: route('marcacoes')">{{ $data['botao_texto'] }}</x-pill>
        @endif
    </div>
    @include('partials.offices-grid')
</section>
