@php
    $menu = [
        ['label' => 'Sobre nós', 'href' => url('/sobre-nos')],
        ['label' => 'Serviços', 'href' => url('/servicos')],
        ['label' => 'EVA Powerlab', 'href' => url('/eva-powerlab')],
        ['label' => 'Campanhas', 'href' => url('/campanhas')],
        ['label' => 'Blog', 'href' => url('/blog')],
        ['label' => 'Contactos', 'href' => url('/contactos')],
    ];
    $submenus = [
        'Serviços' => [
            ['label' => 'Revisão Oficial', 'href' => url('/servicos/revisao-oficial')],
            ['label' => 'Pneus', 'href' => url('/servicos/pneus')],
            ['label' => 'Centro de Colisão e Pintura', 'href' => url('/servicos/colisao-e-pintura')],
            ['label' => 'Inspeção Automóvel', 'href' => url('/servicos/inspecao')],
            ['label' => 'Mudança de Óleos e Filtros', 'href' => url('/servicos/oleo-filtros-e-mecanica')],
            ['label' => 'Climatização Automóvel', 'href' => url('/servicos/climatizacao')],
        ],
        'EVA Powerlab' => [
            ['label' => 'EVA Lab', 'href' => url('/eva-powerlab/eva-lab')],
            ['label' => 'Rescue', 'href' => url('/eva-powerlab/rescue')],
            ['label' => 'Tesla Independent Service', 'href' => url('/eva-powerlab/tesla-independent-service')],
            ['label' => 'EVA Collision', 'href' => url('/eva-powerlab/eva-collision')],
            ['label' => 'EVA Battery Warranty', 'href' => url('/eva-powerlab/battery-warranty')],
            ['label' => 'Certificação MV-BER', 'href' => url('/eva-powerlab/certificacao-mv-ber')],
        ],
    ];
@endphp
<header class="mx-auto flex w-full max-w-[1920px] items-center justify-between gap-6 px-4 pt-8 sm:px-8 xl:px-16">
    <a href="{{ url('/') }}" class="shrink-0 transition hover:opacity-80">
        <img src="{{ asset('images/logo.svg') }}" alt="GOCARMAT — A sua oficina multimarca" class="h-11 w-auto 2xl:h-[62px]">
    </a>

    <nav class="hidden items-center gap-1 2xl:gap-4 lg:flex">
        @foreach ($menu as $item)
            @if (isset($submenus[$item['label']]))
                <div class="group relative">
                    <a href="{{ $item['href'] }}"
                       class="whitespace-nowrap px-2.5 py-1 font-mono text-[14px] 2xl:text-base font-bold uppercase leading-[1.68] tracking-[-0.16px] transition hover:text-energia {{ request()->url() === $item['href'] ? 'text-signal' : 'text-carbono' }}">
                        {{ $item['label'] }}
                    </a>
                    <div class="invisible absolute left-0 top-full z-50 w-64 rounded-2xl bg-white p-3 opacity-0 shadow-xl transition duration-150 group-hover:visible group-hover:opacity-100">
                        @foreach ($submenus[$item['label']] as $subitem)
                            <a href="{{ $subitem['href'] }}" class="block rounded-lg px-3 py-2 font-mono text-[13px] font-bold uppercase leading-[1.4] tracking-[-0.16px] text-carbono transition hover:bg-cloud hover:text-energia">
                                {{ $subitem['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ $item['href'] }}"
                   class="whitespace-nowrap px-2.5 py-1 font-mono text-[14px] 2xl:text-base font-bold uppercase leading-[1.68] tracking-[-0.16px] transition hover:text-energia {{ request()->url() === $item['href'] ? 'text-signal' : 'text-carbono' }}">
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
        <a href="{{ url('/loja-online') }}" class="flex items-center gap-2 whitespace-nowrap px-2.5 py-1 font-mono text-[14px] 2xl:text-base font-bold uppercase leading-[1.68] tracking-[-0.16px] text-carbono transition hover:text-energia">
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
        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-full border-2 border-carbono px-4 py-2 font-mono text-sm font-bold uppercase transition hover:border-energia hover:text-energia">
            Menu
        </summary>
        <nav class="absolute right-0 z-50 mt-2 flex w-56 flex-col gap-1 rounded-2xl bg-white p-4 shadow-xl">
            @foreach ($menu as $item)
                @if (isset($submenus[$item['label']]))
                    <details>
                        <summary class="flex cursor-pointer list-none items-center justify-between px-2 py-1.5 font-mono text-sm font-bold uppercase text-carbono transition hover:text-energia">
                            {{ $item['label'] }}
                            <x-ui.icon name="chevron-down" class="size-4" />
                        </summary>
                        <div class="flex flex-col gap-0.5 py-1 pl-4">
                            @foreach ($submenus[$item['label']] as $subitem)
                                <a href="{{ $subitem['href'] }}" class="rounded-lg px-2 py-1.5 font-mono text-[13px] font-bold uppercase text-carbono/80 transition hover:text-energia">{{ $subitem['label'] }}</a>
                            @endforeach
                        </div>
                    </details>
                @else
                    <a href="{{ $item['href'] }}" class="px-2 py-1.5 font-mono text-sm font-bold uppercase text-carbono transition hover:text-energia">{{ $item['label'] }}</a>
                @endif
            @endforeach
            <a href="{{ url('/loja-online') }}" class="flex items-center gap-2 px-2 py-1.5 font-mono text-sm font-bold uppercase text-carbono transition hover:text-energia">
                Loja online
            </a>
            <a href="{{ url('/marcacoes') }}" class="mt-2 rounded-full bg-energia px-4 py-2 text-center text-sm font-semibold text-precision transition hover:opacity-85">Marcações</a>
        </nav>
    </details>
</header>
