{{-- Cards de serviço detalhados (página Serviços): texto com bullets + foto --}}
@php
    $variantes = [
        'gelo' => ['card' => 'bg-gelo', 'text' => 'text-carbono', 'num' => 'text-energia', 'tag' => 'border-carbono/50 text-carbono/70', 'check' => 'text-energia'],
        'branco' => ['card' => 'bg-white', 'text' => 'text-carbono', 'num' => 'text-energia', 'tag' => 'border-carbono/50 text-carbono/70', 'check' => 'text-energia'],
        'azul' => ['card' => 'bg-energia', 'text' => 'text-white', 'num' => 'text-gelo', 'tag' => 'border-gelo/60 text-gelo', 'check' => 'text-lima'],
    ];
@endphp

<section class="mt-16 grid gap-8 xl:mt-20 xl:grid-cols-2 xl:gap-10">
    @foreach ($data['itens'] ?? [] as $item)
        @php $v = $variantes[$item['variante'] ?? 'branco'] ?? $variantes['branco']; @endphp
        <a href="{{ ($item['link'] ?? null) ?: route('marcacoes') }}" class="group flex flex-col overflow-hidden transition hover:-translate-y-1">
            <div class="flex-1 px-9 pb-10 pt-9 {{ $v['card'] }}">
                <div class="flex items-start justify-between gap-4">
                    <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ $v['num'] }}">{{ $item['numero'] ?? '' }}</p>
                    @if (filled($item['etiqueta'] ?? null))
                        <span class="rounded border px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] {{ $v['tag'] }}">{{ $item['etiqueta'] }}</span>
                    @endif
                </div>
                <h2 class="mt-4 font-mono text-[28px] font-bold leading-[1.2] tracking-[-0.03em] xl:text-[32px] {{ $v['text'] }}">{{ $item['titulo'] }}</h2>
                <p class="mt-4 max-w-[600px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $v['text'] }}">{{ $item['texto'] ?? '' }}</p>

                @if (filled($item['bullets'] ?? null))
                    <ul class="mt-7 space-y-3">
                        @foreach ($item['bullets'] as $bullet)
                            <li class="flex items-center gap-3 text-base font-bold tracking-[-0.16px] {{ $v['text'] }}">
                                <svg class="size-5 shrink-0 {{ $v['check'] }}" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                    <circle cx="10" cy="10" r="8.2" />
                                    <path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ is_array($bullet) ? ($bullet['texto'] ?? '') : $bullet }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="relative h-[280px] shrink-0 xl:h-[340px]">
                <img src="{{ \App\Support\Blocos::imagem($item['imagem'] ?? null) }}" alt="{{ $item['titulo'] }}" class="absolute inset-0 size-full object-cover" loading="lazy">
                <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
                <div class="absolute bottom-0 right-0 flex size-[65px] items-center justify-center bg-white text-energia transition group-hover:bg-lima group-hover:text-carbono">
                    <x-ui.icon name="arrow-up-right" class="size-8" />
                </div>
            </div>
        </a>
    @endforeach
</section>
