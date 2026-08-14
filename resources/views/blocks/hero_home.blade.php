{{-- Hero da Home: painel azul + imagem com efeito Ken Burns + cartão de estatística --}}
<section class="mt-7 grid overflow-hidden rounded-[32px] lg:grid-cols-[minmax(0,728fr)_minmax(0,1064fr)]">
    <div class="bg-energia px-8 py-14 sm:px-12 xl:px-14 xl:pb-14 xl:pt-20 2xl:px-24 2xl:pb-16 2xl:pt-[131px]">
        @if (filled($data['eyebrow'] ?? null))
            <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-gelo">
                {{ $data['eyebrow'] }}
            </p>
        @endif

        <h1 class="mt-8 max-w-[568px] text-4xl font-bold leading-[1.1] tracking-[-0.03em] text-white sm:text-5xl 2xl:text-7xl">
            {{ $data['titulo'] }}
        </h1>

        @if (filled($data['texto'] ?? null))
            <p class="mt-10 max-w-[544px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                {{ $data['texto'] }}
            </p>
        @endif

        @if (filled($data['botoes'] ?? null))
            <div class="mt-12 flex flex-wrap gap-4">
                @foreach ($data['botoes'] as $botao)
                    <x-pill :variant="$loop->first ? 'lima' : 'outline-light'" :href="$botao['link']">{{ $botao['texto'] }}</x-pill>
                @endforeach
            </div>
        @endif
    </div>

    <div class="relative min-h-[320px]">
        <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null, 'images/hero.jpg') }}"
             alt="{{ $data['imagem_alt'] ?? '' }}"
             class="animate-hero-zoom absolute inset-0 size-full object-cover">

        @if (filled($data['destaque_numero'] ?? null))
            <div class="absolute bottom-8 right-8 flex w-[min(480px,90%)] items-center gap-6 rounded-[32px] bg-carbono px-9 py-6">
                <p class="shrink-0 text-[64px] font-bold leading-[1.1] tracking-[-0.06em] text-lima xl:text-[88px]">
                    {{ $data['destaque_numero'] }}
                    <span class="block text-right text-xl tracking-[-0.6px] text-gelo">{{ $data['destaque_unidade'] ?? '' }}</span>
                </p>
                <p class="text-lg font-bold leading-[1.2] tracking-[-0.6px] text-gelo xl:text-xl">
                    {{ $data['destaque_texto'] ?? '' }}
                </p>
            </div>
        @endif
    </div>
</section>
