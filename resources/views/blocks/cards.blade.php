@php
    $colunas = (int) ($data['colunas'] ?? 3);
    $grelha = [2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-2 xl:grid-cols-3', 4 => 'sm:grid-cols-2 xl:grid-cols-4'][$colunas] ?? 'sm:grid-cols-2 xl:grid-cols-3';
@endphp

<section class="mt-16 xl:mt-24">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[44px]">
            {{ $data['titulo'] }}
        </h2>
    @endif

    <div class="mt-10 grid gap-6 {{ $grelha }} xl:gap-8">
        @foreach ($data['itens'] ?? [] as $item)
            @php $tag = 'div'; $atributos = ''; @endphp
            @if (filled($item['link'] ?? null))
                <a href="{{ $item['link'] }}" class="group flex flex-col overflow-hidden bg-white transition hover:-translate-y-1">
            @else
                <div class="flex flex-col overflow-hidden bg-white">
            @endif

                @if ($item['imagem'] ?? null)
                    <div class="h-[240px] shrink-0 overflow-hidden">
                        <img src="{{ asset('storage/'.$item['imagem']) }}" alt="{{ $item['titulo'] }}" class="size-full object-cover transition duration-300 group-hover:scale-105" loading="lazy">
                    </div>
                @endif

                <div class="flex flex-1 flex-col px-8 py-8">
                    @if (filled($item['etiqueta'] ?? null))
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">{{ $item['etiqueta'] }}</p>
                    @endif
                    <h3 class="mt-3 font-mono text-2xl font-bold leading-[1.2] tracking-[-0.03em]">{{ $item['titulo'] }}</h3>
                    @if (filled($item['texto'] ?? null))
                        <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $item['texto'] }}</p>
                    @endif
                </div>

            @if (filled($item['link'] ?? null))
                </a>
            @else
                </div>
            @endif
        @endforeach
    </div>
</section>
