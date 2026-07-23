@php
    $menu = [
        ['label' => 'Home', 'href' => url('/')],
        ['label' => 'Sobre nós', 'href' => url('/sobre-nos')],
        ['label' => 'Serviços', 'href' => url('/servicos')],
        ['label' => 'EVA Powerlab', 'href' => url('/eva-powerlab')],
        ['label' => 'Campanhas', 'href' => url('/campanhas')],
        ['label' => 'Blog', 'href' => url('/blog')],
        ['label' => 'Contactos', 'href' => url('/contactos')],
    ];
@endphp
<header class="mx-auto flex w-full max-w-[1920px] items-center justify-between gap-6 px-4 pt-8 sm:px-8 xl:px-16">
    <a href="{{ url('/') }}" class="shrink-0">
        <img src="{{ asset('images/logo.svg') }}" alt="GOCARMAT — A sua oficina multimarca" class="h-11 w-auto 2xl:h-[62px]">
    </a>

    <nav class="hidden items-center gap-1 2xl:gap-4 lg:flex">
        @foreach ($menu as $item)
            <a href="{{ $item['href'] }}"
               class="whitespace-nowrap px-2.5 py-1 font-mono text-[14px] 2xl:text-base font-bold uppercase leading-[1.68] tracking-[-0.16px] transition hover:text-energia {{ request()->url() === $item['href'] ? 'text-signal' : 'text-carbono' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
        <a href="#" class="flex items-center gap-2 whitespace-nowrap px-2.5 py-1 font-mono text-[14px] 2xl:text-base font-bold uppercase leading-[1.68] tracking-[-0.16px] text-carbono transition hover:text-energia">
            Loja online
            <x-ui.icon name="shopping-bag" class="size-5" />
        </a>
        <a href="{{ url('/marcacoes') }}" class="ml-1 inline-flex items-center gap-2 2xl:gap-4 whitespace-nowrap rounded-full border-2 border-energia bg-energia px-5 2xl:px-[30px] py-[9px] 2xl:py-[11px] text-[15px] font-semibold leading-[1.68] tracking-[-0.3px] text-precision transition hover:opacity-85">
            Marcações
            <x-ui.icon name="arrow-right" class="size-5" />
        </a>
    </nav>

    {{-- Menu mobile --}}
    <details class="relative lg:hidden">
        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border-2 border-carbono px-4 py-2 font-mono text-sm font-bold uppercase">
            Menu
        </summary>
        <nav class="absolute right-0 z-50 mt-2 flex w-56 flex-col gap-1 rounded-2xl bg-white p-4 shadow-xl">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}" class="px-2 py-1.5 font-mono text-sm font-bold uppercase text-carbono">{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ url('/marcacoes') }}" class="mt-2 rounded-full bg-energia px-4 py-2 text-center text-sm font-semibold text-precision">Marcações</a>
        </nav>
    </details>
</header>
