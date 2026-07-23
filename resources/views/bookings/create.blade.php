@extends('layouts.site')

@section('title', 'Marcações — GOCARMAT · Marque o seu serviço online')
@section('meta_description', 'Marque online o serviço para o seu carro numa das 4 oficinas GOCARMAT da Grande Lisboa: revisão oficial, pneus, inspeção, colisão e assistência a elétricos.')

@php
    $inputClass = 'w-full rounded-lg border-2 border-white/80 bg-white px-5 py-3.5 text-base text-carbono placeholder:text-carbono/40 focus:border-lima focus:outline-none';
    $labelClass = 'mb-2 block text-base font-semibold text-white';
@endphp

@section('content')
<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    {{-- FORMULÁRIO --}}
    <section class="mt-7 overflow-hidden rounded-[32px] bg-energia px-8 py-14 sm:px-12 xl:px-24 xl:py-[100px]">
        <p class="font-mono text-[13px] font-extrabold uppercase leading-[1.68] tracking-[0.39px] text-gelo">
            Contactos / Marcações
        </p>
        <h1 class="mt-6 text-5xl font-bold leading-[1.1] tracking-[-0.03em] text-white sm:text-6xl 2xl:text-7xl">
            Marcações
        </h1>

        @if (session('success'))
            <div class="mt-10 max-w-[900px] rounded-2xl bg-lima px-8 py-6">
                <p class="text-lg font-bold text-carbono">Pedido enviado com sucesso!</p>
                <p class="mt-1 text-base text-carbono">Recebemos a sua marcação e enviámos um e-mail de confirmação. A nossa equipa entrará em contacto consigo brevemente.</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-10 max-w-[900px] rounded-2xl bg-white px-8 py-6">
                <p class="text-lg font-bold text-red-600">Verifique os campos assinalados:</p>
                <ul class="mt-2 list-disc pl-5 text-base text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('marcacoes.store') }}" class="mt-12 max-w-[1100px]">
            @csrf
            {{-- Honeypot invisível anti-spam --}}
            <div class="hidden" aria-hidden="true">
                <label>Website<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="name" class="{{ $labelClass }}">Nome *</label>
                    <input id="name" name="name" type="text" required maxlength="120" value="{{ old('name') }}" placeholder="ex: Maria Silva" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="company" class="{{ $labelClass }}">Empresa</label>
                    <input id="company" name="company" type="text" maxlength="120" value="{{ old('company') }}" placeholder="ex: Nome da empresa" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="email" class="{{ $labelClass }}">E-mail *</label>
                    <input id="email" name="email" type="email" required maxlength="190" value="{{ old('email') }}" placeholder="ex: maria@email.com" class="{{ $inputClass }}">
                </div>
                <div>
                    <label for="phone" class="{{ $labelClass }}">Telefone *</label>
                    <input id="phone" name="phone" type="tel" required maxlength="30" value="{{ old('phone') }}" placeholder="ex: +351 987 654 321" class="{{ $inputClass }}">
                </div>
                <div class="sm:col-span-2">
                    <label for="service" class="{{ $labelClass }}">Serviço *</label>
                    <select id="service" name="service" required class="{{ $inputClass }}">
                        <option value="" disabled {{ old('service') ? '' : 'selected' }}>Escolha o serviço pretendido</option>
                        @foreach ($services as $service)
                            <option value="{{ $service }}" {{ old('service') === $service ? 'selected' : '' }}>{{ $service }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="notes" class="{{ $labelClass }}">Notas</label>
                    <textarea id="notes" name="notes" rows="4" maxlength="2000" placeholder="Marca e modelo do carro, matrícula, oficina preferida, disponibilidade..." class="{{ $inputClass }}">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-8 space-y-4">
                <label class="flex items-start gap-3 text-base font-light text-gelo">
                    <input type="checkbox" name="newsletter_opt_in" value="1" {{ old('newsletter_opt_in') ? 'checked' : '' }} class="mt-1 size-5 shrink-0 accent-lima">
                    Quero subscrever a newsletter GOCARMAT e receber dicas e campanhas.
                </label>
                <label class="flex items-start gap-3 text-base font-light text-gelo">
                    <input type="checkbox" name="privacy" value="1" required class="mt-1 size-5 shrink-0 accent-lima">
                    <span>Aceito e dou o meu consentimento para a recolha e tratamento dos meus dados pessoais (RGPD), usados exclusivamente pela GOCARMAT para responder a este pedido. Consulte a <a href="{{ url('/politica-de-privacidade') }}" class="underline hover:text-white">Política de Privacidade</a>. *</span>
                </label>
            </div>

            <button type="submit" class="mt-10 inline-flex items-center gap-4 rounded-full border-2 border-lima bg-lima px-[30px] py-[15px] text-[15px] font-semibold leading-[1.68] tracking-[-0.3px] text-carbono transition hover:opacity-85">
                Enviar
                <x-ui.icon name="arrow-right" class="size-5" />
            </button>
        </form>
    </section>

    {{-- OUTROS CONTACTOS --}}
    <section class="mt-20 xl:mt-[110px]">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
            Outros contactos
        </h2>
        <div class="mt-12 grid gap-8 sm:grid-cols-3">
            @foreach ([
                ['label' => 'Apoio ao Cliente', 'email' => 'apoiocliente@gocarmat.pt'],
                ['label' => 'Quer trabalhar na Equipa Gocarmat', 'email' => 'recrutamento@gocarmat.pt'],
                ['label' => 'Fornecedores / Outros assuntos', 'email' => 'geral@gocarmat.pt'],
            ] as $contact)
                <div class="bg-white px-10 py-9">
                    <p class="text-base font-light leading-[1.68] tracking-[-0.16px]">{{ $contact['label'] }}:</p>
                    <div class="mt-2 flex items-center gap-3">
                        <x-ui.icon name="envelope" class="size-5 shrink-0 text-energia" />
                        <a href="mailto:{{ $contact['email'] }}" class="break-all text-lg font-bold leading-[1.2] tracking-[-0.54px] hover:text-energia">{{ $contact['email'] }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- OFICINAS --}}
    <section class="mt-20 xl:mt-[110px]">
        <h2 class="font-mono text-4xl font-extrabold uppercase leading-[1.2] tracking-[-0.03em] sm:text-[52px]">
            4 oficinas - o mesmo cuidado
        </h2>
        @include('partials.offices-grid')
    </section>

    <div class="h-24 xl:h-[128px]"></div>
</div>
@endsection
