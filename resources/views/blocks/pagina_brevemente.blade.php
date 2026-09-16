{{-- Página "brevemente disponível" — mesmo padrão visual da listagem de campanhas vazia --}}
<div class="mt-10 flex flex-wrap items-center justify-between gap-6">
    <h1 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
        {{ $data['titulo'] ?? '' }}
    </h1>
</div>

<div class="mt-10 rounded-2xl bg-white px-10 py-12">
    <p class="text-2xl font-bold tracking-[-0.03em]">{{ $data['mensagem_titulo'] ?? 'Brevemente online.' }}</p>
    @if (filled($data['mensagem_texto'] ?? null))
        <p class="mt-2 text-base font-light leading-[1.68]">{{ $data['mensagem_texto'] }}</p>
    @endif
</div>
