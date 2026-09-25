{{-- Landing page de campanha, fora do backoffice de propósito: não é editável
     no composer de páginas — ver campanhas/campanha-teste.blade.php para o
     mesmo template aplicado a uma campanha nova. --}}
@extends('layouts.landing')

@section('title', 'Revisão Oficial por 94,90€ — Campanha GOCARMAT')
@section('meta_description', 'Revisão oficial com óleo, filtro de óleo, filtro de ar e check-up completo, por apenas 94,90€ — mão de obra incluída.')

@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Revisão Oficial por 94,90€ — Campanha GOCARMAT">
    <meta property="og:description" content="Revisão oficial com óleo, filtro de óleo, filtro de ar e check-up completo, por apenas 94,90€ — mão de obra incluída.">
    <link rel="canonical" href="{{ url('/campanhas/revisao-oficial') }}">
@endpush

@section('content')
    @include('blocks.campanha_cta_fixo', ['data' => [
        'titulo' => 'Pronto para marcar?',
        'texto' => 'Marque já a sua revisão oficial com este preço especial.',
        'icone' => 'car',
        'cor_icone' => 'energia',
        'botao_texto' => 'Marcar Agora',
        'botao_link' => '#form-marcacao',
    ]])

<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    @include('blocks.campanha_hero_marcacao', ['data' => [
        'breadcrumb_pai' => 'Campanhas',
        'breadcrumb_atual' => 'Revisão Oficial',
        'titulo' => 'Revisão oficial por apenas 94,90€.',
        'texto' => 'Substituição do óleo do motor, filtro de óleo e filtro de ar, com check-up completo à viatura — mão de obra incluída.',
        'imagem' => 'images/campanha-revisao-oficial.jpg',
        'botoes' => [
            ['texto' => 'Ver outras Campanhas', 'link' => '/campanhas'],
        ],
        'faixa_numero' => '94,90€',
        'faixa_texto' => 'Preço fixo, com mão de obra incluída — válido para a generalidade das marcas.',
        'formulario_titulo' => 'Marque a sua Revisão',
        'servico' => 'Revisão Oficial',
        'office_id' => optional(\App\Models\Office::active()->first())->id,
        'redirect_to' => '/campanhas/revisao-oficial',
    ]])

    @include('blocks.servico_vantagens', ['data' => [
        'titulo' => 'O que está incluído nesta ',
        'titulo_destaque' => 'campanha',
        'margem' => 'mt-16 xl:-mt-[124px]',
        'itens' => [
            ['numero' => '01.', 'titulo' => 'Óleo do motor', 'texto' => 'Substituição do óleo do motor (5W30 / 5W40 / 10W40, até 5 litros).'],
            ['numero' => '02.', 'titulo' => 'Filtro de óleo', 'texto' => 'Substituição do filtro de óleo.'],
            ['numero' => '03.', 'titulo' => 'Filtro de ar', 'texto' => 'Substituição do filtro de ar.'],
            ['numero' => '04.', 'titulo' => 'Check-up completo', 'texto' => 'Verificação do sistema de travagem, arranque e carga, iluminação, direção e suspensão.'],
            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
            ['numero' => '06.', 'titulo' => 'Mão de obra incluída', 'texto' => 'Sem custos adicionais de mão de obra no serviço realizado.'],
        ],
    ]])

    @include('blocks.campanha_checkup', ['data' => [
        'titulo' => 'Todos os serviços GOCARMAT em promoção incluem um check-up à sua viatura',
        'texto' => 'O check-up engloba a verificação de:',
        'imagem' => 'images/campanha-revisao-oficial-checkup.jpg',
        'margem' => 'mt-16 xl:mt-8',
        'colunas' => 'xl:grid-cols-[minmax(0,4fr)_minmax(0,5fr)]',
        'padding' => 'px-8 py-8 xl:px-16 xl:py-16',
        'itens' => [
            ['texto' => 'Sistema de travagem (discos, pastilhas, calços, maxilas e líquido dos travões)'],
            ['texto' => 'Sistema de arranque e carga (bateria, alternador e motor de arranque)'],
            ['texto' => 'Sistema de iluminação, lâmpadas e óticas, piscas e iluminação da matrícula'],
            ['texto' => 'Sistema de direção e suspensão/amortecedores'],
            ['texto' => 'Diagnóstico digital EOBD (conjunto de testes imprescindíveis para a deteção de avarias na sua viatura, para veículos a partir de 2010)'],
        ],
    ]])

    @include('blocks.texto', ['data' => [
        'titulo' => 'Condições',
        'corpo' => '<p>Promoção válida para a generalidade das marcas, com mão de obra incluída.<br>'
            .'As promoções indicadas não são acumuláveis com outras campanhas ou promoções em vigor, salvo quando previamente autorizado pela GOCARMAT.<br>'
            .'A promoção não é válida para as viaturas Ferrari, Maserati, Lamborghini, Porsche, Aston Martin, Jaguar, Bentley, Lexus, BMW série 5 e 7, Audi A5, A6, A7 e A8, Mercedes Classe E/S/5/6/7, monovolumes e jipes. Monovolumes, autocaravanas e jipes poderão ser incluídos mediante um suplemento pago diretamente no local — informe-se deste valor, pois pode haver oscilações de acordo com a marca e modelo da sua viatura.<br>'
            .'A GOCARMAT reserva-se no direito de incluir ou excluir qualquer viatura não mencionada acima. Consulte a oficina para mais detalhes.</p>',
    ]])

    @include('blocks.cta_icone', ['data' => [
        'titulo' => 'Precisa de trocar as pastilhas?',
        'texto' => 'Marque já a substituição com este preço especial.',
        'icone' => 'car',
        'cor_icone' => 'energia',
        'botao_texto' => 'Marcar Agora',
        'botao_link' => '/marcacoes?servico='.rawurlencode('Outro assunto').'&nota='.rawurlencode('Campanha: Revisão Oficial por 94,90€'),
        'fundo' => 'carbono',
        'colar_ao_rodape' => true,
        'ligar_barra_fixa' => true,
    ]])

    @include('blocks.campanha_rodape')

    <div class="h-4"></div>
</div>
@endsection
