{{-- Cartão "métodos de pagamento" da landing Repsol Gás — pensado para
     emparelhar com blocks.faq num grid de 2 colunas na própria página.
     A imagem de fundo já vem escurecida, por isso não leva overlay. Cada
     método é um logótipo ('logo'); o 'nome' serve de texto alternativo.
     Entre 1280 e 1739px o cartão é mais estreito (metade do ecrã), por isso
     o texto e os logótipos encolhem para continuarem lado a lado. --}}
<div class="relative flex min-h-[420px] flex-col gap-10 overflow-hidden bg-carbono p-8 sm:p-12 xl:max-[1739px]:p-8">
    @if (filled($data['imagem'] ?? null))
        <img src="{{ \App\Support\Blocos::imagem($data['imagem']) }}" alt="" class="absolute inset-0 size-full object-cover">
    @endif

    <span class="relative z-10 inline-block w-fit border border-white/30 px-4 py-1.5 font-mono text-[11px] font-extrabold uppercase tracking-[0.39px] text-white xl:absolute xl:left-12 xl:top-12 xl:max-[1739px]:left-8 xl:max-[1739px]:top-8">
        {{ $data['etiqueta'] ?? 'Métodos de pagamento' }}
    </span>

    <div class="relative my-auto flex flex-wrap items-center justify-between gap-x-8 gap-y-8 xl:flex-nowrap xl:pr-10 xl:max-[1739px]:gap-x-6 xl:max-[1739px]:pr-0">
        <div class="max-w-[340px] xl:max-[1739px]:max-w-[260px]">
            <h2 class="text-3xl font-bold leading-[1.2] tracking-[-0.03em] text-white sm:text-4xl xl:max-[1739px]:text-3xl">
                {{ $data['titulo'] }}
            </h2>

            @if (filled($data['texto'] ?? null))
                <p class="mt-4 text-sm font-light leading-[1.68] text-gelo">{{ $data['texto'] }}</p>
            @endif

            @if (filled($data['texto_destaque'] ?? null))
                <p class="mt-6 text-sm font-light leading-[1.68] text-gelo">{!! $data['texto_destaque'] !!}</p>
            @endif
        </div>

        @if (filled($data['metodos'] ?? null))
            <div class="flex items-center gap-9 xl:max-[1739px]:gap-7">
                @foreach ($data['metodos'] as $metodo)
                    <img src="{{ \App\Support\Blocos::imagem($metodo['logo']) }}" alt="{{ $metodo['nome'] }}" class="h-[135px] w-auto xl:max-[1739px]:h-[100px]">
                @endforeach
            </div>
        @endif
    </div>
</div>
