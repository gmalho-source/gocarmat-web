{{-- Painel lima com imagem + acordeão de perguntas frequentes ao lado --}}
<section class="mt-20 grid gap-10 xl:mt-28 xl:grid-cols-[minmax(0,46%)_minmax(0,54%)] xl:gap-14">
    <div class="flex flex-col overflow-hidden rounded-[32px] bg-lima px-8 pt-12 sm:px-12 xl:px-14 xl:pt-16">
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] text-carbono sm:text-4xl">
            {{ $data['titulo'] }}
        </h2>
        @if (filled($data['texto'] ?? null))
            <p class="mt-6 max-w-[560px] text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono sm:text-lg">{{ $data['texto'] }}</p>
        @endif
        @if (filled($data['imagem'] ?? null) || filled($data['imagem_omissao'] ?? null))
            <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null, $data['imagem_omissao'] ?? null) }}" alt="" class="mt-auto w-full max-w-[640px] translate-y-2 self-center pt-8">
        @endif
    </div>

    <div>
        <h2 class="font-mono text-3xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-4xl">
            {{ $data['faq_titulo'] ?? 'Perguntas frequentes' }}
        </h2>
        <div class="mt-8 space-y-4">
            @foreach ($data['faqs'] ?? [] as $faq)
                <details class="group rounded-2xl bg-white open:bg-carbono" {{ $loop->first ? 'open' : '' }}>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-8 py-6 text-lg font-bold tracking-[-0.3px] text-carbono group-open:text-lima">
                        {{ $faq['pergunta'] }}
                        <span class="shrink-0 text-2xl leading-none text-energia transition group-open:rotate-45 group-open:text-lima" aria-hidden="true">+</span>
                    </summary>
                    <p class="px-8 pb-7 text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">{{ $faq['resposta'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
