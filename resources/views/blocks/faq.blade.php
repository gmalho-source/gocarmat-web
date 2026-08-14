<section class="mt-16 xl:mt-24">
    <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl">
        {{ $data['titulo'] ?? 'Perguntas frequentes' }}
    </h2>

    <div class="mt-8 max-w-[900px] space-y-4">
        @foreach ($data['itens'] ?? [] as $item)
            <details class="group rounded-2xl bg-white open:bg-carbono" {{ $loop->first ? 'open' : '' }}>
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-8 py-6 text-lg font-bold tracking-[-0.3px] text-carbono group-open:text-lima">
                    {{ $item['pergunta'] }}
                    <span class="shrink-0 text-2xl leading-none text-energia transition group-open:rotate-45 group-open:text-lima" aria-hidden="true">+</span>
                </summary>
                <p class="px-8 pb-7 text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                    {{ $item['resposta'] }}
                </p>
            </details>
        @endforeach
    </div>
</section>
