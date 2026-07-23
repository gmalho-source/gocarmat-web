@php
    $colGocarmat = [
        ['label' => 'Home', 'href' => url('/')],
        ['label' => 'Quem Somos', 'href' => url('/sobre-nos')],
        ['label' => 'Campanhas', 'href' => url('/campanhas')],
        ['label' => 'Blog', 'href' => url('/blog')],
        ['label' => 'Oficinas', 'href' => url('/contactos')],
        ['label' => 'Contactos', 'href' => url('/contactos')],
    ];
    $colServicos = ['Revisão Oficial', 'Pneus', 'Centro de Colisão e Pintura', 'Inspeção Automóvel', 'Mudança de Óleos e Filtros', 'Climatização Automóvel'];
    $colEva = ['EVA Lab — diagnóstico de BMS', 'Rescue — desbloqueio EV', 'Tesla Independent Service', 'EVA Collision', 'Battery Warranty · até 5 anos', 'Certificação MV-BER'];
@endphp
<footer class="mx-auto w-full max-w-[1920px] px-4 pb-4 sm:px-8 xl:px-16">
    <div class="relative overflow-hidden rounded-b-2xl bg-energia px-8 pb-10 pt-16 text-white sm:px-12 xl:px-[98px] xl:pt-[91px]">
        <div class="grid gap-12 xl:grid-cols-12">
            <div class="xl:col-span-4">
                <img src="{{ asset('images/logo-white.svg') }}" alt="GOCARMAT — A sua oficina multimarca" class="h-[62px] w-auto">
                <p class="mt-8 max-w-[512px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                    A GOCARMAT é uma rede de oficinas multimarca, com 16 anos de experiência, a operar na Grande Lisboa e a crescer para todo o país. Capitais 100% portugueses e um laboratório próprio de mobilidade elétrica, o EVA Powerlab.
                </p>
            </div>
            <div class="xl:col-span-2">
                <p class="font-mono text-2xl font-bold uppercase leading-[1.2]">GoCarmat</p>
                <ul class="mt-4 space-y-0.5">
                    @foreach ($colGocarmat as $link)
                        <li><a href="{{ $link['href'] }}" class="text-base font-light leading-[1.68] tracking-[-0.16px] transition hover:text-cristal">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="xl:col-span-2">
                <p class="font-mono text-2xl font-bold uppercase leading-[1.2]">Serviços</p>
                <ul class="mt-4 space-y-0.5">
                    @foreach ($colServicos as $label)
                        <li><a href="{{ url('/servicos') }}" class="text-base font-light leading-[1.68] tracking-[-0.16px] transition hover:text-cristal">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="xl:col-span-2">
                <p class="font-mono text-2xl font-bold uppercase leading-[1.2]">EVA Powerlab</p>
                <ul class="mt-4 space-y-0.5">
                    @foreach ($colEva as $label)
                        <li><a href="{{ url('/eva-powerlab') }}" class="text-base font-light leading-[1.68] tracking-[-0.16px] transition hover:text-cristal">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="relative hidden xl:col-span-2 xl:block">
                <img src="{{ asset('images/footer-graphic.png') }}" alt="" class="absolute -top-5 right-0 h-[271px] w-auto max-w-none">
            </div>
        </div>

        <div class="mt-16 flex flex-wrap items-center gap-x-6 gap-y-2 text-base font-light leading-[1.68] tracking-[-0.16px] text-cristal">
            <span>Gocarmat © {{ date('Y') }} - Todos os Direitos Reservados&nbsp;&nbsp;&nbsp;/</span>
            <a href="{{ url('/politica-de-privacidade') }}" class="transition hover:text-white">Política de Privacidade</a>
            <a href="{{ url('/termos-e-condicoes') }}" class="transition hover:text-white">Termos &amp; Condições</a>
            <a href="https://www.livroreclamacoes.pt" target="_blank" rel="noopener" class="transition hover:text-white">Livro de Reclamações</a>
        </div>

        <img src="{{ asset('images/footer-badge.svg') }}" alt="" class="absolute bottom-6 right-16 hidden h-14 w-auto xl:block">
    </div>
</footer>
