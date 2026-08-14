{{-- Banda EVA Powerlab da Home: painel escuro com pills + painel lima com CTA --}}
<section class="relative mt-24 xl:mt-[135px]">
    <div class="grid xl:grid-cols-[minmax(0,61%)_minmax(0,39%)]">
        <div class="relative overflow-hidden px-8 py-12 sm:px-12 xl:h-[473px] xl:px-[50px] xl:py-[62px]">
            <img src="{{ \App\Support\Blocos::imagem($data['imagem_fundo'] ?? null, 'images/eva-bg.jpg') }}" alt="" class="absolute inset-0 size-full object-cover">
            <div class="absolute inset-0 bg-carbono/75"></div>
            <div class="relative">
                @if (filled($data['etiqueta'] ?? null))
                    <span class="inline-flex rounded border border-lima px-4 pb-[7px] pt-2 font-mono text-[11px] font-bold uppercase leading-none tracking-[0.33px] text-lima">{{ $data['etiqueta'] }}</span>
                @endif
                <div class="mt-12 flex max-w-[680px] flex-wrap gap-6">
                    @foreach ($data['servicos'] ?? [] as $servico)
                        <span class="rounded-full bg-lima px-[30px] py-[15px] text-lg font-semibold leading-[1.68] tracking-[-0.36px] text-tecnico">{{ is_array($servico) ? ($servico['texto'] ?? '') : $servico }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="relative rounded-[32px] bg-lima px-8 py-12 sm:px-12 xl:-ml-10 xl:h-[473px] xl:px-16 xl:py-[54px]">
            <div class="absolute right-10 top-10 hidden size-[120px] items-center justify-center rounded-full bg-carbono text-lima xl:flex">
                <x-ui.icon name="bolt" class="size-14" />
            </div>
            <h2 class="max-w-[480px] text-5xl font-bold leading-[1.2] tracking-[-0.03em] text-carbono xl:text-[62px]">{{ $data['titulo'] }}</h2>
            @if (filled($data['subtitulo'] ?? null))
                <p class="mt-4 max-w-[416px] font-mono text-2xl font-bold leading-[1.2] tracking-[-0.72px] text-carbono">{{ $data['subtitulo'] }}</p>
            @endif
            @if (filled($data['texto'] ?? null))
                <p class="mt-6 max-w-[416px] text-base font-light leading-[1.68] tracking-[-0.16px] text-carbono">{{ $data['texto'] }}</p>
            @endif
            @if (filled($data['botao_texto'] ?? null))
                <div class="mt-8">
                    <x-pill variant="dark" :href="($data['botao_link'] ?? null) ?: '#'">{{ $data['botao_texto'] }}</x-pill>
                </div>
            @endif
        </div>
    </div>

    <img src="{{ \App\Support\Blocos::imagem($data['imagem_carro'] ?? null, 'images/eva-car.png') }}" alt="" class="pointer-events-none absolute bottom-0 left-[38%] hidden w-[565px] 2xl:block">
</section>
