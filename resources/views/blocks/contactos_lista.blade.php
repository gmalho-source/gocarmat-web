{{-- Cartões de contactos por email (Apoio ao Cliente, Recrutamento, etc.) --}}
<section class="mt-20 xl:mt-[110px]">
    @if (filled($data['titulo'] ?? null))
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">{{ $data['titulo'] }}</h2>
    @endif

    <div class="mt-12 grid gap-8 sm:grid-cols-3">
        @foreach ($data['itens'] ?? [] as $item)
            <div class="bg-white px-10 py-9">
                <p class="text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $item['label'] }}:</p>
                <div class="mt-2 flex items-center gap-3">
                    <x-ui.icon name="envelope" class="size-5 shrink-0 text-energia" />
                    <a href="mailto:{{ $item['email'] }}" class="break-all text-lg font-bold leading-[1.2] tracking-[-0.54px] hover:text-energia">{{ $item['email'] }}</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
