<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ImportMarkdownForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ficheiro')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('markdown_file')
                            ->label('Ficheiro Word (.docx)')
                            ->helperText('O corpo do artigo e as imagens embutidas são extraídos automaticamente. Título, slug, categorias e tags preenchem-se em baixo.')
                            ->disk('local')
                            ->directory('markdown-imports')
                            ->acceptedFileTypes([
                                // .docx é tecnicamente um ZIP — o servidor deteta-o pelo
                                // conteúdo real, e consoante a instalação de PHP pode sair
                                // como application/zip em vez do MIME "correto" do OOXML.
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip', 'application/octet-stream', '.docx',
                            ])
                            ->required(),
                    ]),

                Section::make('Conteúdo')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (?string $state, callable $set) => filled($state) ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->helperText('Endereço do artigo: gocarmat.pt/blog/{slug}')
                            ->required()
                            ->maxLength(255)
                            ->unique('posts', 'slug')
                            ->rules(['alpha_dash']),
                        Textarea::make('excerpt')
                            ->label('Excerto')
                            ->helperText('Resumo curto apresentado nas listagens do blog.')
                            ->rows(3)
                            ->maxLength(500),
                    ]),

                Section::make('Publicação')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft' => 'Rascunho',
                                'published' => 'Publicado',
                            ])
                            ->default('draft')
                            ->required()
                            ->native(false),
                        DateTimePicker::make('published_at')
                            ->label('Data de publicação')
                            ->helperText('Datas futuras agendam a publicação.')
                            ->seconds(false),
                        Select::make('categories')
                            ->label('Categorias')
                            ->helperText('Se o ficheiro .docx tiver uma linha "Categorias do artigo: ...", é aplicada automaticamente ao criar.')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                                TextInput::make('slug')->required(),
                            ]),
                        FileUpload::make('featured_image')
                            ->label('Imagem de destaque')
                            ->helperText('Se houver uma imagem antes do título no .docx, é sugerida aqui automaticamente ao criar o artigo.')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->imageEditor()
                            ->maxSize(4096),
                        Select::make('tags')
                            ->label('Tags')
                            ->helperText('Se o ficheiro .docx tiver uma linha "Tags: ...", são aplicadas automaticamente ao criar.')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (?string $state, callable $set) => $set('slug', Str::slug((string) $state))),
                                TextInput::make('slug')->required(),
                            ])
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO')
                    ->description('Meta-tags apresentadas nos motores de busca e redes sociais. Se ficarem vazias, usa-se o título e o excerto.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta title')
                            ->maxLength(70)
                            ->helperText('Recomendado: até 60 caracteres.'),
                        FileUpload::make('og_image')
                            ->label('Imagem de partilha (OG)')
                            ->image()
                            ->disk('public')
                            ->directory('blog/og')
                            ->helperText('1200×630px. Se vazia, usa-se a imagem de destaque.'),
                        Textarea::make('meta_description')
                            ->label('Meta description')
                            ->rows(2)
                            ->maxLength(320)
                            ->helperText('Recomendado: 150–160 caracteres.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
