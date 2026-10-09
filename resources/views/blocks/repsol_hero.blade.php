{{-- Hero da landing Repsol Gás: botões de bilha (escolhem a opção do dropdown
     do formulário) + formulário de encomenda embutido, à semelhança de
     campanha_hero_marcacao.blade.php mas para pedidos de gás (GasOrder), não
     marcações de oficina. --}}
@php
    $bilhas = \App\Http\Controllers\GasOrderController::BILHAS;
    $janelas = \App\Http\Controllers\GasOrderController::JANELAS_ENTREGA;
    $bilhaPredefinida = old('bilha', $data['bilha_predefinida'] ?? 'Repsol K11');
    // Ordem dos botões no hero (diferente da ordem do dropdown do formulário).
    $bilhasBotoes = ['Repsol K11', 'Repsol K6', 'Repsol K13', 'Repsol K45'];
@endphp

<section class="relative">
    <form method="POST" action="{{ url()->current() }}">
        @csrf
        <div class="hidden" aria-hidden="true">
            <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>
        <input type="hidden" name="redirect_to" value="{{ $data['redirect_to'] ?? url()->current() }}">

        <div class="relative grid overflow-hidden rounded-br-[32px] rounded-tl-[32px] lg:grid-cols-[minmax(0,42%)_minmax(0,58%)]">
            <div class="lg:col-start-1 lg:row-start-1">
                <div class="h-full bg-energia px-8 py-10 sm:px-12 xl:px-12 xl:py-14">
                    @if (filled($data['eyebrow'] ?? null))
                        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-gelo">
                            {{ $data['eyebrow'] }}
                            @if (filled($data['eyebrow_destaque'] ?? null))
                                <span class="text-lima">{{ $data['eyebrow_destaque'] }}</span>
                            @endif
                        </p>
                    @endif

                    <h1 class="mt-6 max-w-[480px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] text-white sm:text-5xl">
                        {{ $data['titulo'] }}
                    </h1>

                    @if (filled($data['texto'] ?? null))
                        <p class="mt-6 max-w-[440px] text-base font-light leading-[1.68] tracking-[-0.16px] text-gelo">
                            {{ $data['texto'] }}
                        </p>
                    @endif

                    @if (filled($data['bilha_titulo'] ?? null))
                        <h2 class="mt-8 text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-lima lg:max-w-[60%]">
                            {{ $data['bilha_titulo'] }}
                        </h2>
                    @endif

                    @if (filled($data['bilha_texto'] ?? null))
                        <p class="mt-3 max-w-[440px] text-base font-light lg:max-w-[55%] leading-[1.68] tracking-[-0.16px] text-gelo">
                            {{ $data['bilha_texto'] }}
                        </p>
                    @endif

                    <div class="mt-5 flex max-w-[260px] flex-wrap gap-3">
                        @foreach ($bilhasBotoes as $bilha)
                            <button type="button" data-bilha-botao="{{ $bilha }}" class="inline-flex items-center justify-center rounded-full bg-carbono px-4 py-1.5 text-center text-sm font-semibold text-white transition hover:opacity-90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                                {{ $bilha }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="relative min-h-[300px] lg:col-start-2 lg:row-start-1">
                <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null) }}" alt="" class="absolute inset-0 size-full object-cover">
                {{-- No mobile (empilhado) as garrafas ficam por cima da fotografia, a assentar na faixa lima. --}}
                @if (filled($data['imagem_garrafas'] ?? null))
                    <img src="{{ \App\Support\Blocos::imagem($data['imagem_garrafas']) }}" alt="" class="pointer-events-none absolute bottom-0 left-1/2 h-auto w-[78%] max-w-[360px] -translate-x-1/2 lg:hidden">
                @endif
            </div>

            {{-- Garrafas: ocupam a linha de cima e ficam centradas na fronteira
                 entre a área azul e a fotografia, a "assentar" na faixa lima. --}}
            @if (filled($data['imagem_garrafas'] ?? null))
                <div class="pointer-events-none relative z-10 hidden lg:col-span-2 lg:col-start-1 lg:row-start-1 lg:block">
                    <img src="{{ \App\Support\Blocos::imagem($data['imagem_garrafas']) }}" alt="" class="absolute bottom-0 left-[42%] h-auto w-[29%] max-w-[460px] -translate-x-[55%] 2xl:w-[460px]">
                </div>
            @endif

            @if (filled($data['entrega_titulo'] ?? null))
                <div class="flex flex-wrap items-center gap-x-8 gap-y-4 bg-lima px-8 py-8 sm:px-12 xl:px-12 lg:col-span-2 lg:col-start-1 lg:row-start-2">
                    <h3 class="max-w-[320px] text-2xl font-bold leading-[1.25] tracking-[-0.03em] text-carbono xl:text-[28px]">
                        {{ $data['entrega_titulo'] }}
                    </h3>

                    @if (filled($data['entrega_itens'] ?? null))
                        <ul class="space-y-1.5 text-sm font-medium leading-[1.5] text-carbono">
                            @foreach ($data['entrega_itens'] as $item)
                                <li>• {{ $item }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif
        </div>

        {{-- Cartão do formulário: em fluxo normal (empilhado por baixo) até lg,
             só flutua sobre a imagem em ecrãs largos, onde há espaço de sobra.
             O id é o alvo do botão da barra de CTA fixa (campanha_cta_fixo). --}}
        <div id="form-marcacao" class="relative z-10 mt-6 scroll-mt-24 px-4 sm:px-8 lg:absolute lg:z-20 lg:mt-0 lg:right-0 lg:bottom-0 lg:w-[520px] lg:px-0 xl:w-[600px]">
            <div class="relative rounded-[24px] bg-carbono p-7 shadow-2xl lg:rounded-br-[32px] xl:p-8">
                {{-- Depois de enviar, o formulário continua no layout (invisível) para o cartão
                     manter exatamente o mesmo tamanho, e a mensagem de sucesso aparece por cima. --}}
                <div @class(['invisible' => session('success')]) @if (session('success')) aria-hidden="true" inert @endif>
                <h2 class="text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-white">{{ $data['formulario_titulo'] ?? 'Encomende a sua bilha de gás' }}</h2>

                    @if (filled($data['formulario_texto'] ?? null))
                        <p class="mt-3 text-sm font-light leading-[1.5] text-gelo">{{ $data['formulario_texto'] }}</p>
                    @endif

                    @if ($errors->any())
                        <div class="mt-4 rounded-2xl bg-white px-5 py-4">
                            <p class="text-sm font-bold text-red-600">Verifique os campos assinalados:</p>
                            <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php $inputClass = 'w-full rounded-lg border-2 border-white/80 bg-white px-4 py-2.5 text-sm text-carbono placeholder:text-carbono/40 focus:border-lima focus:outline-none'; @endphp

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="gas_name" class="mb-1 block text-sm font-semibold text-white">Nome</label>
                            <input id="gas_name" name="name" type="text" required maxlength="120" value="{{ old('name') }}" placeholder="ex: Maria Silva" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="gas_company" class="mb-1 block text-sm font-semibold text-white">Empresa</label>
                            <input id="gas_company" name="company" type="text" maxlength="120" value="{{ old('company') }}" placeholder="ex: Nome da empresa" class="{{ $inputClass }}">
                        </div>
                    </div>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="gas_email" class="mb-1 block text-sm font-semibold text-white">E-mail</label>
                            <input id="gas_email" name="email" type="email" required maxlength="190" value="{{ old('email') }}" placeholder="ex: maria@email.com" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="gas_phone" class="mb-1 block text-sm font-semibold text-white">Telefone</label>
                            <input id="gas_phone" name="phone" type="tel" inputmode="tel" required maxlength="20" pattern="\+?[0-9]+" title="Apenas números e o símbolo + para indicativos" value="{{ old('phone') }}" placeholder="ex: +351912345678" class="{{ $inputClass }}" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="gas_bilha" class="mb-1 block text-sm font-semibold text-white">Opção pretendida:</label>
                        <select id="gas_bilha" name="bilha" required class="{{ $inputClass }}" data-bilha-select>
                            @foreach ($bilhas as $bilha)
                                <option value="{{ $bilha }}" {{ $bilhaPredefinida === $bilha ? 'selected' : '' }}>{{ $bilha }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <label for="gas_janela" class="mb-1 block text-sm font-semibold text-white">Escolha da janela horária para entrega</label>
                        <select id="gas_janela" name="janela_entrega" required class="{{ $inputClass }}">
                            <option value="" disabled {{ old('janela_entrega') ? '' : 'selected' }}>Escolha um horário</option>
                            @foreach ($janelas as $janela)
                                <option value="{{ $janela }}" {{ old('janela_entrega') === $janela ? 'selected' : '' }}>{{ $janela }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="mt-4 flex items-start gap-2.5 text-xs font-light leading-snug text-gelo">
                        <input type="checkbox" name="privacy" value="1" required class="mt-0.5 size-4 shrink-0 accent-lima">
                        <span>Ao enviar concordo com a nossa <a href="https://www.gocarmat.pt/politica-de-privacidade" class="underline hover:text-white">Política de Privacidade</a></span>
                    </label>

                    <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-3 rounded-full border-2 border-lima bg-lima px-6 py-3.5 text-sm font-semibold leading-[1.68] tracking-[-0.3px] text-carbono transition hover:opacity-85">
                        Encomendar Agora
                        <x-ui.icon name="arrow-right" class="size-4" />
                    </button>
                </div>

                @if (session('success'))
                    <div class="absolute inset-0 flex items-center p-7 xl:p-8">
                        <div class="w-full rounded-2xl bg-lima px-6 py-6">
                            <p class="text-xl font-bold text-carbono">Obrigado pela sua encomenda.</p>
                            <p class="mt-3 text-base text-carbono">Recebemos o seu pedido com sucesso. Brevemente entraremos em contacto para confirmar a hora de entrega.</p>
                            <p class="mt-3 text-base text-carbono">Até breve!</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</section>
