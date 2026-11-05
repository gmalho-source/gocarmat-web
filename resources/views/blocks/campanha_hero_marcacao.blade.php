{{-- Hero de landing page de campanha com o formulário de marcação embutido
     (em vez de apenas um botão a apontar para /marcacoes) — pensado para
     páginas de parceiros/campanhas pagas fora do site principal, onde o
     objetivo é converter sem sair da página. --}}
@php $f = \App\Support\Blocos::fundo($data['fundo'] ?? 'energia'); @endphp

<section class="relative lg:pb-40">
    <div class="grid overflow-hidden rounded-b-[32px] lg:grid-cols-[minmax(0,42%)_minmax(0,58%)]">
        <div class="{{ $f['sec'] }} px-8 py-10 sm:px-12 xl:px-12 xl:py-14">
            <a href="{{ url('/') }}" class="inline-block shrink-0 transition hover:opacity-80">
                <img src="{{ asset('images/logo-white.svg') }}" alt="GOCARMAT — A sua oficina multimarca" class="h-10 w-auto 2xl:h-11">
            </a>

            <p class="mt-8 font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] {{ ($data['fundo'] ?? 'energia') === 'carbono' ? 'text-lima' : 'text-gelo' }}">
                {{ $data['breadcrumb_pai'] ?? 'Campanhas' }} / {{ $data['breadcrumb_atual'] ?? '' }}
            </p>

            <h1 class="mt-6 max-w-[480px] text-4xl font-bold leading-[1.15] tracking-[-0.03em] {{ $f['titulo'] }} sm:text-5xl">
                {{ $data['titulo'] }}
            </h1>

            @if (filled($data['texto'] ?? null))
                <p class="mt-6 max-w-[440px] text-base font-light leading-[1.68] tracking-[-0.16px] {{ $f['texto'] }}">
                    {{ $data['texto'] }}
                </p>
            @endif

            @if (filled($data['botoes'] ?? null))
                <div class="mt-8 flex flex-wrap gap-3">
                    @foreach ($data['botoes'] as $botao)
                        <x-pill :variant="$loop->first ? 'lima' : 'outline-light'" :href="$botao['link']">{{ $botao['texto'] }}</x-pill>
                    @endforeach
                </div>
            @endif

            @if (filled($data['faixa_texto'] ?? null))
                <p class="mt-6 max-w-[440px] text-sm font-light leading-[1.5] {{ $f['texto'] }}">{{ $data['faixa_texto'] }}</p>
            @endif
        </div>

        <div class="relative min-h-[300px]">
            <img src="{{ \App\Support\Blocos::imagem($data['imagem'] ?? null) }}" alt="" class="absolute inset-0 size-full object-cover">

            @if (filled($data['faixa_numero'] ?? null))
                <div class="absolute inset-x-0 bottom-0 flex flex-wrap items-center gap-4 bg-carbono/90 px-6 py-5 sm:px-10">
                    <p class="shrink-0 font-mono text-3xl font-extrabold uppercase leading-none tracking-[-0.03em] text-lima sm:text-4xl">{{ $data['faixa_numero'] }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Cartão do formulário: em fluxo normal (empilhado por baixo) até lg,
         só flutua sobre a imagem em ecrãs largos, onde há espaço de sobra.
         O id é o alvo do botão da barra de CTA fixa (campanha_cta_fixo). --}}
    <div id="{{ $data['formulario_id'] ?? 'form-marcacao' }}" class="relative z-10 mt-6 scroll-mt-24 px-4 sm:px-8 lg:absolute lg:z-auto lg:mt-0 lg:right-0 lg:top-[220px] lg:w-[480px] lg:px-0 xl:w-[557px]">
        <div class="rounded-[24px] bg-energia p-7 shadow-2xl xl:p-8">
            <h2 class="text-2xl font-bold leading-[1.2] tracking-[-0.03em] text-white">{{ $data['formulario_titulo'] ?? 'Marque a sua Revisão' }}</h2>

            @if (session('success'))
                <div class="mt-6 rounded-2xl bg-lima px-6 py-5">
                    <p class="text-base font-bold text-carbono">Pedido enviado com sucesso!</p>
                    <p class="mt-1 text-sm text-carbono">A nossa equipa entrará em contacto consigo brevemente.</p>
                </div>
            @else
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

                <form method="POST" action="{{ route('marcacoes.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <div class="hidden" aria-hidden="true">
                        <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <input type="hidden" name="service" value="{{ $data['servico'] ?? 'Outro assunto' }}">
                    <input type="hidden" name="office_id" value="{{ $data['office_id'] ?? '' }}">
                    <input type="hidden" name="redirect_to" value="{{ $data['redirect_to'] ?? url()->current() }}">

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="hero_name" class="mb-1 block text-sm font-semibold text-white">Nome</label>
                            <input id="hero_name" name="name" type="text" required maxlength="120" value="{{ old('name') }}" placeholder="ex: Maria Silva" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label for="hero_company" class="mb-1 block text-sm font-semibold text-white">Empresa</label>
                            <input id="hero_company" name="company" type="text" maxlength="120" value="{{ old('company') }}" placeholder="ex: Nome da empresa" class="{{ $inputClass }}">
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="hero_email" class="mb-1 block text-sm font-semibold text-white">E-mail</label>
                            <input id="hero_email" name="email" type="email" required maxlength="190" value="{{ old('email') }}" placeholder="ex: maria@email.com" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="hero_phone" class="mb-1 block text-sm font-semibold text-white">Telefone</label>
                            <input id="hero_phone" name="phone" type="tel" inputmode="tel" required maxlength="20" pattern="\+?[0-9]+" title="Apenas números e o símbolo + para indicativos" value="{{ old('phone') }}" placeholder="ex: +351912345678" class="{{ $inputClass }}" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                        </div>
                    </div>

                    <div>
                        <label for="hero_notes" class="mb-1 block text-sm font-semibold text-white">Notas</label>
                        <textarea id="hero_notes" name="notes" rows="2" maxlength="2000" placeholder="ex: Detalhes do veículo" class="{{ $inputClass }}">{{ old('notes', $data['notas_predefinidas'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2 pt-1">
                        <label class="flex items-start gap-2.5 text-xs font-light leading-snug text-gelo">
                            <input type="checkbox" name="newsletter_opt_in" value="1" {{ old('newsletter_opt_in') ? 'checked' : '' }} class="mt-0.5 size-4 shrink-0 accent-lima">
                            Quero subscrever a newsletter GOCARMAT e receber dicas e campanhas.
                        </label>
                        <label class="flex items-start gap-2.5 text-xs font-light leading-snug text-gelo">
                            <input type="checkbox" name="privacy" value="1" required class="mt-0.5 size-4 shrink-0 accent-lima">
                            <span>Aceito e dou o meu consentimento para a recolha e tratamento dos meus dados pessoais (RGPD), usados exclusivamente pela GOCARMAT para responder a este pedido. Consulte a <a href="{{ url('/politica-de-privacidade') }}" class="underline hover:text-white">Política de Privacidade</a>. *</span>
                        </label>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-3 rounded-full border-2 border-lima bg-lima px-6 py-3 text-sm font-semibold leading-[1.68] tracking-[-0.3px] text-carbono transition hover:opacity-85">
                        Enviar
                        <x-ui.icon name="arrow-right" class="size-4" />
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
