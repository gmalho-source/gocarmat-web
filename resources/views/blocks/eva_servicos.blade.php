{{-- Cards do EVA Powerlab: etiqueta escura, título azul e ícone lima flutuante --}}
<section class="mt-20 grid gap-x-8 gap-y-14 sm:grid-cols-2 xl:mt-24 xl:grid-cols-3 xl:gap-x-10">
    @foreach ($data['itens'] ?? [] as $item)
        <a href="{{ ($item['link'] ?? null) ?: route('eva') }}" class="group relative block bg-white px-10 pb-11 pt-10 transition hover:-translate-y-1">
            <div class="absolute -top-9 right-10 flex size-[80px] items-center justify-center bg-lima text-energia transition-colors duration-300 group-hover:bg-energia group-hover:text-lima">
                <x-ui.icon :name="($item['icone'] ?? null) ?: 'bolt'" class="size-10" />
            </div>
            @if (filled($item['etiqueta'] ?? null))
                <span class="inline-flex rounded-lg bg-carbono px-4 pb-[7px] pt-2 font-mono text-[12px] font-bold uppercase leading-none tracking-[0.36px] text-lima">{{ $item['etiqueta'] }}</span>
            @endif
            <h2 class="mt-5 max-w-[85%] font-mono text-[26px] font-bold uppercase leading-[1.2] tracking-[-0.03em] text-energia">{{ $item['titulo'] }}</h2>
            <p class="mt-3 max-w-[560px] text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $item['texto'] ?? '' }}</p>
        </a>
    @endforeach
</section>
