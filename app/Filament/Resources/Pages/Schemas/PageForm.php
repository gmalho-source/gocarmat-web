<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Http\Controllers\BookingController;
use App\Models\Office;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    /** Opções de fundo comuns aos blocos, alinhadas com o design system. */
    private const FUNDOS = [
        'branco' => 'Branco',
        'cloud' => 'Cinza claro',
        'gelo' => 'Azul gelo',
        'energia' => 'Azul (destaque)',
        'carbono' => 'Escuro',
        'lima' => 'Lima',
    ];

    /** Ícones disponíveis nos blocos de chamada à ação. */
    private const ICONES = [
        'car' => 'Carro',
        'car-burst' => 'Carro (colisão)',
        'bolt' => 'Raio',
        'shield' => 'Escudo',
        'wrench' => 'Chave',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Página')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Conteúdo')
                            ->schema([
                                Builder::make('content')
                                    ->label('Composição da página')
                                    ->helperText('Adicione, ordene e configure blocos. Cada bloco segue automaticamente o design da marca.')
                                    ->blocks(self::blocos())
                                    ->collapsible()
                                    ->cloneable()
                                    ->blockNumbers(false)
                                    ->addActionLabel('Adicionar bloco')
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Definições')
                            ->schema([
                                Section::make('Identificação')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Título')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, ?string $state, callable $set) {
                                                if ($operation === 'create' && filled($state)) {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),
                                        TextInput::make('slug')
                                            ->label('Slug (URL)')
                                            ->helperText('Endereço: gocarmat.pt/{slug}. Pode ter barras para páginas dentro de uma secção (ex: campanhas/nome-da-campanha).')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->regex('/^[a-z0-9_-]+(\/[a-z0-9_-]+)*$/')
                                            ->validationMessages(['regex' => 'Use só letras minúsculas, números, hífenes e barras, sem espaços nem barra no início ou no fim.']),
                                        Select::make('layout')
                                            ->label('Modelo da página')
                                            ->options([
                                                'site' => 'Site completo (com menu e rodapé)',
                                                'landing' => 'Landing de campanha (sem menu nem rodapé)',
                                            ])
                                            ->default('site')
                                            ->required()
                                            ->native(false)
                                            ->helperText('As campanhas usam "Landing": a página não tem saídas para o resto do site.')
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Publicação')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('status')
                                            ->label('Estado')
                                            ->options(['draft' => 'Rascunho', 'published' => 'Publicada'])
                                            ->default('draft')
                                            ->required()
                                            ->native(false),
                                        DateTimePicker::make('published_at')
                                            ->label('Data de publicação')
                                            ->helperText('Datas futuras agendam a publicação.')
                                            ->seconds(false),
                                        Toggle::make('show_in_menu')
                                            ->label('Mostrar no menu do site'),
                                        TextInput::make('menu_order')
                                            ->label('Ordem no menu')
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                Section::make('SEO')
                                    ->description('Se ficarem vazios, usa-se o título da página.')
                                    ->columns(2)
                                    ->collapsible()
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta title')
                                            ->maxLength(70),
                                        FileUpload::make('og_image')
                                            ->label('Imagem de partilha (OG)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('pages/og'),
                                        Textarea::make('meta_description')
                                            ->label('Meta description')
                                            ->rows(2)
                                            ->maxLength(320)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /** Blocos disponíveis no composer. */
    private static function blocos(): array
    {
        return array_merge(self::blocosDeSeccao(), self::blocosGenericos());
    }

    /**
     * Blocos que reproduzem as secções desenhadas no mockup.
     * São os que compõem a Home, Sobre Nós, Serviços, EVA Powerlab e Marcações.
     */
    private static function blocosDeSeccao(): array
    {
        return [
            Builder\Block::make('hero_home')
                ->label('Hero da Home (com cartão de destaque)')
                ->icon('heroicon-o-home')
                ->schema([
                    TextInput::make('eyebrow')->label('Sobretítulo'),
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    FileUpload::make('imagem')->label('Imagem (deixe vazio para manter a atual)')->image()->disk('public')->directory('pages'),
                    Repeater::make('botoes')->label('Botões')
                        ->schema([
                            TextInput::make('texto')->label('Texto')->required(),
                            TextInput::make('link')->label('Link')->required(),
                        ])->maxItems(2),
                    TextInput::make('destaque_numero')->label('Número em destaque (ex: +16)'),
                    TextInput::make('destaque_unidade')->label('Unidade (ex: ANOS)'),
                    Textarea::make('destaque_texto')->label('Texto do destaque')->rows(2),
                ]),

            Builder\Block::make('hero_split')
                ->label('Hero de página interior')
                ->icon('heroicon-o-rectangle-group')
                ->schema([
                    TextInput::make('eyebrow')->label('Sobretítulo'),
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('titulo_destaque')->label('Parte do título a destacar (lima)'),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('carbono')->native(false),
                    Toggle::make('overlay_azul')->label('Aplicar sobreposição azul na imagem'),
                    TextInput::make('cartao_titulo')->label('Cartão sobreposto — título'),
                    TextInput::make('cartao_texto')->label('Cartão sobreposto — texto'),
                    Repeater::make('botoes')->label('Botões')
                        ->schema([
                            TextInput::make('texto')->label('Texto')->required(),
                            TextInput::make('link')->label('Link')->required(),
                        ])->maxItems(2),
                ]),

            Builder\Block::make('servicos_cards')
                ->label('Serviços — cards da Home')
                ->icon('heroicon-o-wrench-screwdriver')
                ->schema([
                    TextInput::make('titulo')->label('Título da secção'),
                    TextInput::make('botao_texto')->label('Texto do botão'),
                    TextInput::make('botao_link')->label('Link do botão'),
                    Repeater::make('itens')->label('Serviços')
                        ->schema([
                            TextInput::make('numero')->label('Número (ex: 01.)'),
                            TextInput::make('etiqueta')->label('Etiqueta'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(3),
                            FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                            TextInput::make('link')->label('Link'),
                        ])->reorderable(),
                ]),

            Builder\Block::make('servicos_detalhe')
                ->label('Serviços — cards detalhados')
                ->icon('heroicon-o-list-bullet')
                ->schema([
                    Repeater::make('itens')->label('Serviços')
                        ->schema([
                            TextInput::make('numero')->label('Número'),
                            TextInput::make('etiqueta')->label('Etiqueta'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(3),
                            Repeater::make('bullets')->label('Pontos')
                                ->simple(TextInput::make('texto')->required()),
                            FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                            Select::make('variante')->label('Cor do cartão')
                                ->options(['branco' => 'Branco', 'gelo' => 'Azul gelo', 'azul' => 'Azul'])
                                ->default('branco')->native(false),
                            TextInput::make('link')->label('Link'),
                        ])->reorderable(),
                ]),

            Builder\Block::make('eva_banner')
                ->label('EVA Powerlab — banda da Home')
                ->icon('heroicon-o-bolt')
                ->schema([
                    TextInput::make('etiqueta')->label('Etiqueta'),
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('subtitulo')->label('Subtítulo'),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    TextInput::make('botao_texto')->label('Texto do botão'),
                    TextInput::make('botao_link')->label('Link do botão'),
                    Repeater::make('servicos')->label('Serviços (pills)')
                        ->simple(TextInput::make('texto')->required()),
                ]),

            Builder\Block::make('eva_servicos')
                ->label('EVA Powerlab — cards de serviço')
                ->icon('heroicon-o-cpu-chip')
                ->schema([
                    Repeater::make('itens')->label('Serviços')
                        ->schema([
                            TextInput::make('etiqueta')->label('Etiqueta'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(3),
                            Select::make('icone')->label('Ícone')
                                ->options(['bolt' => 'Raio', 'car-burst' => 'Carro', 'shield' => 'Escudo', 'certificate' => 'Certificado', 'wrench' => 'Chave', 'unlock' => 'Desbloqueio'])
                                ->default('bolt')->native(false),
                        ])->reorderable(),
                ]),

            Builder\Block::make('porque_faq')
                ->label('Painel + perguntas frequentes')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->schema([
                    TextInput::make('titulo')->label('Título do painel')->required(),
                    Textarea::make('texto')->label('Texto do painel')->rows(3),
                    FileUpload::make('imagem')->label('Imagem do painel')->image()->disk('public')->directory('pages'),
                    TextInput::make('faq_titulo')->label('Título das perguntas')->default('Perguntas frequentes'),
                    Repeater::make('faqs')->label('Perguntas')
                        ->schema([
                            TextInput::make('pergunta')->label('Pergunta')->required(),
                            Textarea::make('resposta')->label('Resposta')->rows(3)->required(),
                        ]),
                ]),

            Builder\Block::make('missao_visao')
                ->label('Missão e Visão')
                ->icon('heroicon-o-flag')
                ->schema([
                    TextInput::make('titulo_1')->label('Título 1')->default('Missão')->required(),
                    Textarea::make('texto_1')->label('Texto 1')->rows(3)->required(),
                    TextInput::make('titulo_2')->label('Título 2')->default('Visão')->required(),
                    Textarea::make('texto_2')->label('Texto 2')->rows(3)->required(),
                ]),

            Builder\Block::make('valores')
                ->label('Valores (cartões numerados)')
                ->icon('heroicon-o-sparkles')
                ->schema([
                    TextInput::make('titulo')->label('Título da secção'),
                    Repeater::make('itens')->label('Valores')
                        ->schema([
                            TextInput::make('numero')->label('Número'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(2),
                        ])->reorderable(),
                    TextInput::make('destaque_titulo')->label('Cartão lima — título'),
                    Textarea::make('destaque_texto')->label('Cartão lima — texto')->rows(2),
                    TextInput::make('destaque_botao')->label('Cartão lima — botão'),
                    TextInput::make('destaque_link')->label('Cartão lima — link'),
                ]),

            Builder\Block::make('oficinas_titulo')
                ->label('Oficinas (com título e botão)')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('4 oficinas - o mesmo cuidado'),
                    TextInput::make('botao_texto')->label('Texto do botão'),
                    TextInput::make('botao_link')->label('Link do botão'),
                ]),

            Builder\Block::make('blog_grelha')
                ->label('Blog — grelha da Home')
                ->icon('heroicon-o-newspaper')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('Gocarmat Blog'),
                    TextInput::make('botao_texto')->label('Texto do botão')->default('Ver todos os Artigos'),
                ]),

            Builder\Block::make('cta_icone')
                ->label('Faixa de CTA com ícone')
                ->icon('heroicon-o-megaphone')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(2),
                    Select::make('icone')->label('Ícone')
                        ->options(self::ICONES)
                        ->native(false),
                    Select::make('cor_icone')->label('Cor do ícone')
                        ->options(['energia' => 'Azul', 'lima' => 'Lima'])->default('energia')->native(false),
                    TextInput::make('botao_texto')->label('Texto do botão'),
                    TextInput::make('botao_link')->label('Link do botão'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('carbono')->native(false),
                    Toggle::make('colar_ao_rodape')->label('Colar ao rodapé (sem margem inferior)'),
                    Toggle::make('ligar_barra_fixa')
                        ->label('É o chamamento final da campanha')
                        ->helperText('A barra fixa de marcação no fundo do ecrã esconde-se quando este bloco aparece.'),
                ]),

            Builder\Block::make('marcacoes_form')
                ->label('Formulário de marcações')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextInput::make('eyebrow')->label('Sobretítulo')->default('Contactos / Marcações'),
                    TextInput::make('titulo')->label('Título')->default('Marcações'),
                    TextInput::make('botao_texto')->label('Texto do botão')->default('Enviar'),
                    TextInput::make('sucesso_titulo')->label('Mensagem de sucesso — título'),
                    Textarea::make('sucesso_texto')->label('Mensagem de sucesso — texto')->rows(2),
                    Textarea::make('newsletter_texto')->label('Texto do opt-in de newsletter')->rows(2),
                    Textarea::make('rgpd_texto')->label('Texto do consentimento RGPD')->rows(3),
                ]),

            Builder\Block::make('contactos_lista')
                ->label('Lista de contactos por e-mail')
                ->icon('heroicon-o-envelope')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('Outros contactos'),
                    Repeater::make('itens')->label('Contactos')
                        ->schema([
                            TextInput::make('label')->label('Descrição')->required(),
                            TextInput::make('email')->label('E-mail')->email()->required(),
                        ])->reorderable(),
                ]),

            Builder\Block::make('servico_hero')
                ->label('Hero de serviço (com faixa de destaque)')
                ->icon('heroicon-o-rectangle-group')
                ->schema([
                    TextInput::make('breadcrumb_pai')->label('Migalha — secção (ex: Serviços)')->default('Serviços'),
                    TextInput::make('breadcrumb_link')->label('Migalha — link da secção'),
                    TextInput::make('breadcrumb_atual')->label('Migalha — página atual'),
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('energia')->native(false),
                    Repeater::make('botoes')->label('Botões')
                        ->schema([
                            TextInput::make('texto')->label('Texto')->required(),
                            TextInput::make('link')->label('Link')->required(),
                        ])->maxItems(2)->defaultItems(0),
                    TextInput::make('faixa_numero')->label('Faixa sobre a imagem — destaque (ex: 94,90€)'),
                    TextInput::make('faixa_texto')->label('Faixa sobre a imagem — texto'),
                ]),

            Builder\Block::make('servico_vantagens')
                ->label('Vantagens numeradas')
                ->icon('heroicon-o-check-badge')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('titulo_destaque')->label('Parte final do título a destacar (azul)'),
                    Select::make('colunas')->label('Cartões por linha')
                        ->options([3 => '3', 4 => '4'])->default(3)->native(false),
                    Toggle::make('compacto')->label('Menos espaço por cima (a seguir a um texto simples)'),
                    Toggle::make('sobre_hero')
                        ->label('Subir por baixo do formulário do hero de campanha')
                        ->helperText('Ligar só quando este bloco vem logo a seguir ao hero com formulário.'),
                    Repeater::make('itens')->label('Vantagens')
                        ->schema([
                            TextInput::make('numero')->label('Número (ex: 01.)'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(2),
                        ])->reorderable(),
                    Textarea::make('nota')->label('Nota em letra pequena')->rows(2),
                ]),

            Builder\Block::make('mapa_oficinas')
                ->label('Mapa das oficinas')
                ->icon('heroicon-o-map')
                ->schema([
                    TextInput::make('titulo')->label('Título (opcional)'),
                ]),

            Builder\Block::make('pagina_brevemente')
                ->label('Página "brevemente disponível"')
                ->icon('heroicon-o-clock')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('mensagem_titulo')->label('Mensagem — título')->default('Brevemente online.'),
                    Textarea::make('mensagem_texto')->label('Mensagem — texto')->rows(2),
                ]),

            Builder\Block::make('campanhas_grelha')
                ->label('Campanhas — listagem')
                ->icon('heroicon-o-tag')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('Gocarmat Campanhas'),
                    Repeater::make('itens')->label('Campanhas')
                        ->schema([
                            TextInput::make('titulo')->label('Título')->required(),
                            TextInput::make('preco')->label('Preço (ex: 94,90€)'),
                            Textarea::make('texto')->label('Descrição')->rows(2),
                            Textarea::make('condicoes')->label('Condições (letra pequena)')->rows(3),
                            TextInput::make('link')->label('Link da campanha (ex: /campanhas/nome)')->required(),
                            FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                        ])->reorderable()->defaultItems(0),
                ]),

            Builder\Block::make('campanha_cta_fixo')
                ->label('Campanha — barra fixa de marcação')
                ->icon('heroicon-o-bars-arrow-down')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('texto')->label('Texto'),
                    Select::make('icone')->label('Ícone')->options(self::ICONES)->default('car')->native(false),
                    Select::make('cor_icone')->label('Cor do ícone')
                        ->options(['energia' => 'Azul', 'lima' => 'Lima'])->default('energia')->native(false),
                    TextInput::make('botao_texto')->label('Texto do botão')->default('Marcar Agora'),
                    TextInput::make('botao_link')->label('Link do botão')
                        ->default('#form-marcacao')
                        ->helperText('"#form-marcacao" leva ao formulário do topo da página.'),
                    Toggle::make('mobile_so_botao')->label('No telemóvel mostrar só o botão')->default(true),
                ]),

            Builder\Block::make('campanha_hero_marcacao')
                ->label('Campanha — hero com formulário de marcação')
                ->icon('heroicon-o-inbox-arrow-down')
                ->schema([
                    TextInput::make('breadcrumb_pai')->label('Migalha — secção')->default('Campanhas'),
                    TextInput::make('aviso_topo')
                        ->label('Aviso grande no topo da página')
                        ->helperText('Faixa lima, em letras grandes, por cima do hero (ex: campanha exclusiva de uma oficina). Deixe vazio para não mostrar.'),
                    TextInput::make('breadcrumb_atual')->label('Migalha — campanha')->required(),
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('energia')->native(false),
                    Repeater::make('botoes')->label('Botões')
                        ->schema([
                            TextInput::make('texto')->label('Texto')->required(),
                            TextInput::make('link')->label('Link')->required(),
                        ])->maxItems(2)->defaultItems(0),
                    TextInput::make('faixa_numero')->label('Destaque sobre a imagem (ex: 94,90€)'),
                    TextInput::make('faixa_texto')->label('Texto por baixo do título'),
                    TextInput::make('formulario_titulo')->label('Título do formulário')->default('Marque a sua Revisão'),
                    Select::make('servico')->label('Serviço associado ao pedido')
                        ->options(array_combine(BookingController::SERVICES, BookingController::SERVICES))
                        ->default('Outro assunto')->native(false),
                    Select::make('office_id')->label('Oficina que recebe o pedido')
                        ->options(fn () => Office::active()->pluck('name', 'id'))
                        ->placeholder('A primeira oficina ativa')->native(false),
                    Select::make('oficinas_opcoes')->label('Oficinas a escolher no formulário')
                        ->helperText('Escolha duas ou mais para o cliente escolher a oficina no formulário (o pedido vai para a oficina escolhida). Com menos de duas, usa-se a oficina indicada acima.')
                        ->options(fn () => Office::active()->pluck('name', 'id'))
                        ->multiple()->native(false),
                    Textarea::make('notas_predefinidas')->label('Notas já preenchidas no pedido (ex: nome da campanha)')->rows(2),
                    Toggle::make('formulario_sobe')
                        ->label('Subir o cartão do formulário')
                        ->helperText('Ligar se, em ecrãs grandes, o formulário ficar por cima das caixas de baixo (acontece com títulos e notas compridos).'),
                ]),

            Builder\Block::make('campanha_checkup')
                ->label('Campanha — lista de check-up com imagem')
                ->icon('heroicon-o-clipboard-document-check')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    TextInput::make('texto')->label('Texto de introdução'),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                    Select::make('colunas')->label('Proporção imagem / texto')
                        ->options(['4-5' => 'Imagem um pouco mais pequena', '1-1' => '50 / 50', '1-2' => 'Imagem a um terço'])
                        ->default('4-5')->native(false),
                    Repeater::make('itens')->label('Itens verificados')
                        ->simple(TextInput::make('texto')->required())
                        ->reorderable(),
                ]),

            Builder\Block::make('campanha_rodape')
                ->label('Campanha — rodapé mínimo')
                ->icon('heroicon-o-minus')
                ->schema([
                    Text::make('Rodapé fixo com o logótipo e os links legais. Não tem campos: colocar sempre no fim da página, a seguir ao chamamento final.'),
                ]),
        ];
    }

    /** Blocos genéricos, para páginas novas. */
    private static function blocosGenericos(): array
    {
        return [
            Builder\Block::make('hero')
                ->label('Destaque (hero)')
                ->icon('heroicon-o-star')
                ->schema([
                    TextInput::make('eyebrow')->label('Sobretítulo')->maxLength(120),
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(3),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('energia')->native(false),
                    Repeater::make('botoes')
                        ->label('Botões')
                        ->schema([
                            TextInput::make('texto')->label('Texto')->required(),
                            TextInput::make('link')->label('Link')->required(),
                        ])
                        ->maxItems(2)
                        ->defaultItems(0),
                ]),

            Builder\Block::make('texto')
                ->label('Texto')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->schema([
                    TextInput::make('titulo')->label('Título (opcional)'),
                    RichEditor::make('corpo')
                        ->label('Conteúdo')
                        ->required()
                        ->fileAttachmentsDisk('public')
                        ->fileAttachmentsDirectory('pages/inline'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('branco')->native(false),
                ]),

            Builder\Block::make('imagem_texto')
                ->label('Imagem + texto')
                ->icon('heroicon-o-photo')
                ->schema([
                    TextInput::make('titulo')->label('Título'),
                    RichEditor::make('texto')->label('Texto')->fileAttachmentsDisk('public'),
                    FileUpload::make('imagem')->label('Imagem')->image()->disk('public')->directory('pages')->required(),
                    Select::make('posicao_imagem')
                        ->label('Posição da imagem')
                        ->options(['esquerda' => 'À esquerda', 'direita' => 'À direita'])
                        ->default('direita')
                        ->native(false),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('branco')->native(false),
                ]),

            Builder\Block::make('cards')
                ->label('Cartões')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    TextInput::make('titulo')->label('Título da secção'),
                    Select::make('colunas')
                        ->label('Colunas')
                        ->options([2 => '2', 3 => '3', 4 => '4'])
                        ->default(3)
                        ->native(false),
                    Repeater::make('itens')
                        ->label('Cartões')
                        ->schema([
                            TextInput::make('etiqueta')->label('Etiqueta (ex: 01. ou "manutenção")'),
                            TextInput::make('titulo')->label('Título')->required(),
                            Textarea::make('texto')->label('Texto')->rows(3),
                            FileUpload::make('imagem')->label('Imagem (opcional)')->image()->disk('public')->directory('pages'),
                            TextInput::make('link')->label('Link (opcional)'),
                        ])
                        ->defaultItems(3)
                        ->reorderable(),
                ]),

            Builder\Block::make('destaques')
                ->label('Lista de destaques (pills)')
                ->icon('heroicon-o-tag')
                ->schema([
                    TextInput::make('titulo')->label('Título'),
                    Repeater::make('itens')
                        ->label('Destaques')
                        ->simple(TextInput::make('texto')->label('Texto')->required())
                        ->defaultItems(3),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('carbono')->native(false),
                ]),

            Builder\Block::make('faq')
                ->label('Perguntas frequentes')
                ->icon('heroicon-o-question-mark-circle')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('Perguntas frequentes'),
                    Repeater::make('itens')
                        ->label('Perguntas')
                        ->schema([
                            TextInput::make('pergunta')->label('Pergunta')->required(),
                            Textarea::make('resposta')->label('Resposta')->rows(3)->required(),
                        ])
                        ->defaultItems(3),
                ]),

            Builder\Block::make('cta')
                ->label('Faixa de chamada à ação')
                ->icon('heroicon-o-megaphone')
                ->schema([
                    TextInput::make('titulo')->label('Título')->required(),
                    Textarea::make('texto')->label('Texto')->rows(2),
                    TextInput::make('botao_texto')->label('Texto do botão'),
                    TextInput::make('botao_link')->label('Link do botão'),
                    Select::make('fundo')->label('Fundo')->options(self::FUNDOS)->default('carbono')->native(false),
                ]),

            Builder\Block::make('oficinas')
                ->label('Grelha de oficinas')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('4 oficinas - o mesmo cuidado'),
                ]),

            Builder\Block::make('artigos')
                ->label('Últimos artigos do blog')
                ->icon('heroicon-o-newspaper')
                ->schema([
                    TextInput::make('titulo')->label('Título')->default('Gocarmat Blog'),
                    TextInput::make('quantidade')->label('Quantos artigos')->numeric()->default(3)->minValue(1)->maxValue(6),
                ]),

            Builder\Block::make('galeria')
                ->label('Galeria de imagens')
                ->icon('heroicon-o-rectangle-stack')
                ->schema([
                    TextInput::make('titulo')->label('Título'),
                    FileUpload::make('imagens')
                        ->label('Imagens')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('pages/galeria'),
                ]),

            Builder\Block::make('espaco')
                ->label('Espaçamento')
                ->icon('heroicon-o-arrows-up-down')
                ->schema([
                    Select::make('altura')
                        ->label('Altura')
                        ->options(['pequeno' => 'Pequeno', 'medio' => 'Médio', 'grande' => 'Grande'])
                        ->default('medio')
                        ->native(false),
                ]),
        ];
    }
}
