{{-- Landing page de campanha, fora do backoffice de propósito: não é editável
     no composer de páginas — ver campanhas/campanha-teste.blade.php para o
     mesmo template aplicado a uma campanha nova. --}}
@extends('layouts.landing')

@section('title', 'Pastilhas de Travão por 84,90€ — Campanha GOCARMAT')
@section('meta_description', 'Substituição de um jogo de pastilhas de travão, com check-up incluído, por apenas 84,90€.')

@push('meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Pastilhas de Travão por 84,90€ — Campanha GOCARMAT">
    <meta property="og:description" content="Substituição de um jogo de pastilhas de travão, com check-up incluído, por apenas 84,90€.">
    <link rel="canonical" href="{{ url('/campanhas/pastilhas-travao') }}">
@endpush

@section('content')
    @include('blocks.campanha_cta_fixo', ['data' => [
        'titulo' => 'Precisa de trocar as pastilhas?',
        'texto' => 'Marque já a substituição com este preço especial.',
        'icone' => 'car',
        'cor_icone' => 'energia',
        'botao_texto' => 'Marcar Agora',
        'botao_link' => '#form-marcacao',
    ]])

<div class="mx-auto w-full max-w-[1920px] px-4 sm:px-8 xl:px-16">

    @include('blocks.campanha_hero_marcacao', ['data' => [
        'breadcrumb_pai' => 'Campanhas',
        'breadcrumb_atual' => 'Pastilhas de Travão',
        'titulo' => 'Pastilhas de travão por apenas 84,90€.',
        'texto' => 'Substituição de um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.',
        'imagem' => 'images/campanha-pastilhas-travao.jpg',
        'botoes' => [
            ['texto' => 'Ver outras Campanhas', 'link' => '/campanhas'],
        ],
        'faixa_numero' => '84,90€',
        'faixa_texto' => 'Preço fixo por um jogo de pastilhas de travão, com check-up incluído.',
        'formulario_titulo' => 'Marque a Substituição',
        'servico' => 'Outro assunto',
        'notas_predefinidas' => 'Campanha: Pastilhas de Travão por 84,90€',
        'office_id' => optional(\App\Models\Office::active()->first())->id,
        'redirect_to' => '/campanhas/pastilhas-travao',
    ]])

    @include('blocks.servico_vantagens', ['data' => [
        'titulo' => 'O que está incluído nesta ',
        'titulo_destaque' => 'campanha',
        'margem' => 'mt-16 xl:-mt-[124px]',
        'itens' => [
            ['numero' => '01.', 'titulo' => 'Pastilhas de travão', 'texto' => 'Substituição de um jogo de pastilhas de travão, no eixo dianteiro ou traseiro.'],
            ['numero' => '02.', 'titulo' => 'Inspeção do disco', 'texto' => 'Avaliação do estado dos discos de travão associados.'],
            ['numero' => '03.', 'titulo' => 'Verificação do fluído', 'texto' => 'Verificação do nível e estado do fluído de travões.'],
            ['numero' => '04.', 'titulo' => 'Check-up geral', 'texto' => 'Verificação do sistema de arranque e carga, iluminação, direção e suspensão.'],
            ['numero' => '05.', 'titulo' => 'Diagnóstico digital', 'texto' => 'Diagnóstico EOBD digital, para viaturas a partir de 2010.'],
            ['numero' => '06.', 'titulo' => 'Sem sensores de desgaste', 'texto' => 'O preço não inclui sensores de desgaste, quando aplicável à sua viatura.'],
        ],
    ]])

    @include('blocks.campanha_checkup', ['data' => [
        'titulo' => 'Todos os serviços GOCARMAT em promoção incluem um check-up à sua viatura',
        'texto' => 'O check-up engloba a verificação de:',
        'imagem' => 'images/campanha-pastilhas-travao.jpg',
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
        'corpo' => '<p>Preço fixo por um jogo de pastilhas de travão (eixo dianteiro ou traseiro), com check-up incluído.<br>'
            .'Esta campanha não inclui os avisadores de desgaste das pastilhas.<br>'
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
        'botao_link' => '/marcacoes?servico='.rawurlencode('Outro assunto').'&nota='.rawurlencode('Campanha: Pastilhas de Travão por 84,90€'),
        'fundo' => 'carbono',
        'colar_ao_rodape' => true,
        'ligar_barra_fixa' => true,
    ]])

    @include('blocks.campanha_rodape')

    <div class="h-4"></div>
</div>
@endsection
