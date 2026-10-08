{{-- Landing page da parceria Repsol Gás (entrega de bilhas de gás ao
     domicílio), fora do backoffice de propósito — ver campanhas/revisao-oficial.blade.php
     para o mesmo padrão aplicado a uma campanha de oficina.
     Pedidos ficam guardados em GasOrder (não são marcações), geridos em
     /admin/gas-orders. --}}
@extends('layouts.landing')

@section('title', 'Gás ao domicílio em Oeiras — GOCARMAT × Repsol')
@section('meta_description', 'Encomende a sua bilha de gás Repsol e receba-a em casa, em todo o concelho de Oeiras. Pagamento no ato da entrega, por MB Way ou Multibanco.')

@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Gás ao domicílio em Oeiras — GOCARMAT × Repsol">
    <meta property="og:description" content="Encomende a sua bilha de gás Repsol e receba-a em casa, em todo o concelho de Oeiras. Pagamento no ato da entrega, por MB Way ou Multibanco.">
    <link rel="canonical" href="{{ url('/repsol-gas') }}">
@endpush

@section('content')
    @include('blocks.campanha_cta_fixo', ['data' => [
        'titulo' => 'Ficou sem gás?',
        'texto' => 'Encomende já a sua bilha Repsol, com entrega ao domicílio.',
        'icone' => 'droplet',
        'cor_icone' => 'lima',
        'botao_texto' => 'Encomendar Agora',
        'botao_link' => '#form-marcacao',
    ]])

<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    @include('blocks.repsol_topo', ['data' => [
        'telefones' => ['214 419 243', '214 416 266'],
        'email' => 'qscmcgasoeiras@gocarmat.pt',
    ]])

    @include('blocks.repsol_hero', ['data' => [
        'eyebrow' => 'Pagamento no ato da entrega - ',
        'eyebrow_destaque' => 'de segunda-feira a sábado',
        'titulo' => 'Gás à porta, em todo o concelho de Oeiras.',
        'texto' => 'Encomende a sua bilha de gás Repsol e receba-a na sua morada e no horário selecionados.',
        'bilha_titulo' => 'Qual é a bilha de que precisa?',
        'bilha_texto' => 'Escolha uma das opções disponíveis para venda e entrega ao domicílio.',
        'entrega_titulo' => 'Entregamos em todo o concelho de Oeiras',
        'entrega_itens' => [
            'Segunda a sexta-feira: 09h00–20h00',
            'Sábado: 09h00–15h00',
        ],
        'imagem' => 'images/repsol-gas-hero.webp',
        'imagem_garrafas' => 'images/repsol-garrafas.png',
        'formulario_titulo' => 'Encomende a sua bilha de gás',
        'formulario_texto' => 'Preencha os seus dados e escolha a data e o horário mais convenientes para a entrega.',
        'redirect_to' => '/repsol-gas',
    ]])

    <div class="mt-8 grid gap-6 xl:mt-12 xl:grid-cols-2 xl:gap-10">
        @include('blocks.repsol_pagamento', ['data' => [
            'etiqueta' => 'Métodos de pagamento',
            'titulo' => 'Pague como for mais conveniente',
            'imagem' => 'images/repsol-pagamento-fundo.webp',
            'texto' => '*O pagamento é feito apenas no ato da entrega.',
            'texto_destaque' => '<strong class="text-white">Fale connosco</strong> e garanta já a entrega da sua bilha <strong class="text-white">Repsol</strong>.',
            'metodos' => [
                ['nome' => 'MB WAY', 'logo' => 'images/logo-mbway.png'],
                ['nome' => 'Multibanco', 'logo' => 'images/logo-multibanco.png'],
            ],
        ]])

        @include('blocks.faq', [
            'data' => [
                'titulo' => 'Perguntas frequentes',
                'margem' => '',
                'colunas' => 1,
                'abrir_primeiro' => true,
                'itens' => [
                    ['pergunta' => 'Onde efetuam entregas?', 'resposta' => 'As entregas estão disponíveis em todo o concelho de Oeiras.'],
                    ['pergunta' => 'Que bilhas estão disponíveis?', 'resposta' => 'Repsol K11, Repsol K6 e Repsol K13 — escolha a que precisa no formulário de encomenda.'],
                    ['pergunta' => 'Em que horários posso receber a encomenda?', 'resposta' => 'De segunda a sexta-feira das 09h00 às 20h00, ou aos sábados das 09h00 às 15h00.'],
                    ['pergunta' => 'Como posso pagar?', 'resposta' => 'O pagamento é feito no ato da entrega, por MB Way ou Multibanco.'],
                    ['pergunta' => 'O pedido fica imediatamente confirmado?', 'resposta' => 'Depois de enviar o formulário, a nossa equipa entra em contacto consigo para confirmar a data e o horário da entrega.'],
                ],
            ],
        ])
    </div>

    @include('blocks.cta_icone', ['data' => [
        'titulo' => 'Ficou sem gás? ',
        'titulo_destaque' => 'Nós levamo-lo até si.',
        'texto' => 'Escolha a sua bilha Repsol e peça a entrega em qualquer localidade do concelho de Oeiras.',
        'texto_max' => 'max-w-none',
        'margem' => 'mt-8 xl:mt-12',
        'icone_imagem' => 'images/repsol-icone-gas.png',
        'botao_texto' => 'Encomendar Agora',
        'botao_link' => '#form-marcacao',
        'fundo' => 'carbono',
        'colar_ao_rodape' => true,
        'ligar_barra_fixa' => true,
    ]])

    @include('blocks.campanha_rodape')

    <div class="h-4"></div>
</div>
@endsection
