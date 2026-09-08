{{-- Carrossel de serviços da Home: cards numerados com etiqueta, foto e seta --}}
<section class="mt-24 xl:mt-[128px]">
    <div class="flex flex-wrap items-center justify-between gap-6">
        @if (filled($data['titulo'] ?? null))
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                {{ $data['titulo'] }}
            </h2>
        @endif
        <div class="flex items-center gap-4">
            @if (filled($data['botao_texto'] ?? null))
                <x-pill variant="outline-dark" :href="($data['botao_link'] ?? null) ?: '#'">{{ $data['botao_texto'] }}</x-pill>
            @endif
            <div class="hidden shrink-0 items-center gap-2 sm:flex">
                <button type="button" data-carousel-prev class="flex size-11 items-center justify-center rounded-full border-2 border-carbono text-carbono transition hover:bg-carbono hover:text-white disabled:opacity-30 disabled:pointer-events-none">
                    <x-ui.icon name="arrow-right" class="size-5 rotate-180" />
                </button>
                <button type="button" data-carousel-next class="flex size-11 items-center justify-center rounded-full border-2 border-carbono text-carbono transition hover:bg-carbono hover:text-white disabled:opacity-30 disabled:pointer-events-none">
                    <x-ui.icon name="arrow-right" class="size-5" />
                </button>
            </div>
        </div>
    </div>

    <div data-carousel-track class="mt-14 flex snap-x snap-mandatory select-none gap-6 overflow-x-auto scroll-smooth pb-2 xl:gap-8 [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
        @foreach ($data['itens'] ?? [] as $item)
            <a href="{{ ($item['link'] ?? null) ?: '#' }}" class="group relative flex w-[280px] shrink-0 snap-start flex-col overflow-hidden transition hover:-translate-y-1 sm:w-[320px] xl:w-[28%]">
                <div class="relative flex-1 px-8 pb-6 pt-8 transition-colors duration-300 bg-white group-hover:bg-energia 2xl:px-10 2xl:pt-10">
                    <div class="flex items-start justify-between">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia transition-colors duration-300 group-hover:text-lima">{{ $item['numero'] ?? '' }}</p>
                        @if (filled($item['etiqueta'] ?? null))
                            <span class="rounded border border-carbono/50 px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-carbono/70 transition-colors duration-300 group-hover:border-white/50 group-hover:text-white">{{ $item['etiqueta'] }}</span>
                        @endif
                    </div>
                    <h3 class="mt-4 font-mono text-[28px] font-bold leading-[1.2] tracking-[-0.03em] text-carbono transition-colors duration-300 group-hover:text-white xl:text-[32px]">{{ $item['titulo'] }}</h3>
                    <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono transition-colors duration-300 group-hover:text-gelo">{{ $item['texto'] ?? '' }}</p>
                </div>
                <div class="relative h-[240px] shrink-0 2xl:h-[300px]">
                    <img src="{{ \App\Support\Blocos::imagem($item['imagem'] ?? null) }}" alt="{{ $item['titulo'] }}" class="pointer-events-none absolute inset-0 size-full object-cover" loading="lazy" draggable="false">
                    <div class="absolute bottom-0 right-0 flex size-[65px] items-center justify-center bg-white text-energia transition group-hover:bg-lima group-hover:text-carbono">
                        <x-ui.icon name="arrow-up-right" class="size-8" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
