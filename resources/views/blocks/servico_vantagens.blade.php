{{-- Grelha de vantagens numeradas (páginas de serviço individual) --}}
<section class="mt-16 rounded-[32px] bg-gelo px-8 py-14 sm:px-12 xl:mt-24 xl:px-16 xl:py-20">
    <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-carbono sm:text-[52px]">
        {{ $data['titulo'] }}
        @if (filled($data['titulo_destaque'] ?? null))
            <span class="text-energia">{{ $data['titulo_destaque'] }}</span>
        @endif
    </h2>

    <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($data['itens'] ?? [] as $item)
            <div class="rounded-2xl border border-carbono/10 bg-white px-7 py-6">
                <div class="flex items-start justify-between gap-4">
                    <h3 class="font-mono text-base font-bold uppercase leading-[1.3] tracking-[-0.16px] text-carbono">{{ $item['titulo'] }}</h3>
                    <span class="shrink-0 font-mono text-sm font-extrabold text-energia">{{ $item['numero'] }}</span>
                </div>
                <p class="mt-3 text-sm font-light leading-[1.68] text-carbono">{{ $item['texto'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</section>
