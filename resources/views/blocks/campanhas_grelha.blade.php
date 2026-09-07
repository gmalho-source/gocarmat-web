{{-- Listagem de campanhas ativas, grelha simples (sem destaque no topo) --}}
@php $itens = $data['itens'] ?? []; @endphp

<div class="mt-10 flex flex-wrap items-center justify-between gap-6">
    <h1 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
        {{ $data['titulo'] ?? 'Campanhas' }}
    </h1>
</div>

@if (count($itens))
    <div class="mt-12 grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($itens as $item)
            <a href="{{ $item['link'] ?? route('marcacoes') }}" class="group flex flex-col bg-white transition hover:-translate-y-1">
                @if (filled($item['imagem'] ?? null))
                    <div class="h-[240px] shrink-0 overflow-hidden">
                        <img src="{{ \App\Support\Blocos::imagem($item['imagem']) }}" alt="{{ $item['titulo'] }}" class="size-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                    </div>
                @endif
                <div class="flex flex-1 flex-col px-8 py-8">
                    @if (filled($item['preco'] ?? null))
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">{{ $item['preco'] }}</p>
                    @endif
                    <h2 class="mt-3 text-2xl font-bold leading-[1.2] tracking-[-0.03em]">{{ $item['titulo'] }}</h2>
                    @if (filled($item['texto'] ?? null))
                        <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $item['texto'] }}</p>
                    @endif
                    @if (filled($item['condicoes'] ?? null))
                        <p class="mt-3 text-sm font-light leading-[1.5] text-carbono/60">{{ $item['condicoes'] }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="mt-10 rounded-2xl bg-white px-10 py-12">
        <p class="text-2xl font-bold tracking-[-0.03em]">Sem campanhas ativas de momento.</p>
        <p class="mt-2 text-base font-light leading-[1.68]">Volte a visitar esta página em breve.</p>
    </div>
@endif
