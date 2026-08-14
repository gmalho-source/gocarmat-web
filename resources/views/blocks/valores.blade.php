{{-- Cartões de valores numerados + cartão lima com CTA --}}
<section class="mt-24 xl:mt-[110px]">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">{{ $data['titulo'] }}</h2>
    @endif

    <div class="mt-12 grid gap-6 sm:grid-cols-2 xl:grid-cols-3 xl:gap-8">
        @foreach ($data['itens'] ?? [] as $item)
            <div class="border border-energia bg-cloud px-9 py-10">
                @if (filled($item['numero'] ?? null))
                    <span class="inline-flex rounded bg-energia px-3.5 py-1.5 font-mono text-[13px] font-bold text-white">{{ $item['numero'] }}</span>
                @endif
                <h3 class="mt-5 font-mono text-2xl font-bold leading-[1.2] tracking-[-0.03em]">{{ $item['titulo'] }}</h3>
                <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $item['texto'] ?? '' }}</p>
            </div>
        @endforeach

        @if (filled($data['destaque_titulo'] ?? null))
            <div class="flex flex-col justify-center bg-lima px-9 py-10">
                <h3 class="font-mono text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-carbono">{{ $data['destaque_titulo'] }}</h3>
                <p class="mt-3 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">{{ $data['destaque_texto'] ?? '' }}</p>
                @if (filled($data['destaque_botao'] ?? null))
                    <a href="{{ ($data['destaque_link'] ?? null) ?: route('marcacoes') }}" class="mt-6 inline-flex w-fit items-center gap-3 rounded-full bg-carbono px-7 py-3 text-[15px] font-semibold text-lima transition hover:opacity-85">
                        {{ $data['destaque_botao'] }}
                        <x-ui.icon name="arrow-right" class="size-5" />
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
