{{-- Lista do check-up incluído nas campanhas, com imagem opcional ao lado --}}
<section class="mt-16 grid gap-10 rounded-[32px] bg-gelo px-8 py-14 xl:mt-24 xl:grid-cols-2 xl:gap-16 xl:px-16 xl:py-20">
    @if (filled($data['imagem'] ?? null))
        <div class="relative min-h-[300px] overflow-hidden rounded-[24px] xl:order-1">
            <img src="{{ \App\Support\Blocos::imagem($data['imagem']) }}" alt="" class="absolute inset-0 size-full object-cover">
        </div>
    @endif

    <div class="xl:order-2">
        <h2 class="font-mono text-2xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-energia sm:text-3xl">
            {{ $data['titulo'] }}
        </h2>
        @if (filled($data['texto'] ?? null))
            <p class="mt-4 text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">{{ $data['texto'] }}</p>
        @endif

        <ul class="mt-8 space-y-6">
            @foreach ($data['itens'] ?? [] as $item)
                <li class="flex items-start gap-4">
                    <svg class="mt-0.5 size-6 shrink-0 text-energia" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <circle cx="10" cy="10" r="8.2" />
                        <path d="m6.6 10.2 2.3 2.3 4.5-4.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-base leading-[1.5] tracking-[-0.16px] text-carbono">{{ is_array($item) ? ($item['texto'] ?? '') : $item }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
