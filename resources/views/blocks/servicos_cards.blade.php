{{-- Grelha de serviços da Home: cards numerados com etiqueta, foto e seta --}}
<section class="mt-24 xl:mt-[128px]">
    <div class="flex flex-wrap items-center justify-between gap-6">
        @if (filled($data['titulo'] ?? null))
            <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
                {{ $data['titulo'] }}
            </h2>
        @endif
        @if (filled($data['botao_texto'] ?? null))
            <x-pill variant="outline-dark" :href="($data['botao_link'] ?? null) ?: '#'">{{ $data['botao_texto'] }}</x-pill>
        @endif
    </div>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 xl:grid-cols-4 xl:gap-8">
        @foreach ($data['itens'] ?? [] as $item)
            <a href="{{ ($item['link'] ?? null) ?: '#' }}" class="group relative flex flex-col overflow-hidden transition hover:-translate-y-1">
                <div class="relative flex-1 bg-white px-8 pb-6 pt-8 2xl:px-10 2xl:pt-10">
                    <div class="flex items-start justify-between">
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-energia">{{ $item['numero'] ?? '' }}</p>
                        @if (filled($item['etiqueta'] ?? null))
                            <span class="rounded border border-carbono/50 px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-carbono/70">{{ $item['etiqueta'] }}</span>
                        @endif
                    </div>
                    <h3 class="mt-4 font-mono text-[28px] font-bold leading-[1.2] tracking-[-0.03em] text-carbono xl:text-[32px]">{{ $item['titulo'] }}</h3>
                    <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">{{ $item['texto'] ?? '' }}</p>
                </div>
                <div class="relative h-[240px] shrink-0 2xl:h-[300px]">
                    <img src="{{ \App\Support\Blocos::imagem($item['imagem'] ?? null) }}" alt="{{ $item['titulo'] }}" class="absolute inset-0 size-full object-cover" loading="lazy">
                    <div class="absolute inset-0 bg-energia/35 mix-blend-screen"></div>
                    <div class="absolute bottom-0 right-0 flex size-[65px] items-center justify-center bg-white text-energia transition group-hover:bg-lima group-hover:text-carbono">
                        <x-ui.icon name="arrow-up-right" class="size-8" />
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
