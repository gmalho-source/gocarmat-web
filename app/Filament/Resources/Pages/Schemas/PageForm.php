<?php

namespace App\Filament\Resources\Pages\Schemas;

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
                                            ->helperText('Endereço: gocarmat.pt/{slug}')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->rules(['alpha_dash']),
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
