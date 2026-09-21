<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use App\Models\Tag;
use App\Services\ArtigoMarkdownImportador;
use App\Services\DocxArtigoImportador;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
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
                            ->label('Ficheiro Markdown (.md) ou Word (.docx)')
                            ->helperText('O título, o slug e (num .docx) a categoria/tags preenchem-se sozinhos a partir do ficheiro. Num .docx as imagens embutidas são guardadas automaticamente; num .md só imagens já publicadas num URL são descarregadas.')
                            ->disk('local')
                            ->directory('markdown-imports')
                            ->acceptedFileTypes([
                                'text/markdown', 'text/plain', 'text/x-markdown', '.md',
                                // .docx é tecnicamente um ZIP — o servidor deteta-o pelo
                                // conteúdo real, e consoante a instalação de PHP pode sair
                                // como application/zip em vez do MIME "correto" do OOXML.
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'application/zip', 'application/octet-stream', '.docx',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, callable $set) {
                                if (blank($state)) {
                                    return;
                                }

                                $ext = strtolower(pathinfo($state, PATHINFO_EXTENSION));

                                if ($ext === 'docx') {
                                    self::preencherDoDocx($state, $set);

                                    return;
                                }

                                $conteudo = Storage::disk('local')->get($state);
                                $titulo = filled($conteudo) ? app(ArtigoMarkdownImportador::class)->extrairTitulo($conteudo) : null;

                                if (filled($titulo)) {
                                    $set('title', $titulo);
                                    $set('slug', Str::slug($titulo));
                                }
                            }),
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
                            ->helperText('Num .docx, se houver uma imagem antes do título, é sugerida aqui automaticamente ao criar o artigo.')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->imageEditor()
                            ->maxSize(4096),
                        Select::make('tags')
                            ->label('Tags')
                            ->helperText('Palavras-chave do artigo; escreva para pesquisar ou criar novas.')
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

    /**
     * Só o título/categoria/tags (sem imagens nem corpo — isso fica para o
     * momento de criar o artigo, em ImportarArtigoMarkdown, para não
     * processar o ficheiro duas vezes).
     */
    private static function preencherDoDocx(string $caminhoRelativo, callable $set): void
    {
        $caminhoAbsoluto = Storage::disk('local')->path($caminhoRelativo);
        $meta = app(DocxArtigoImportador::class)->metadados($caminhoAbsoluto);

        if (filled($meta['titulo'])) {
            $set('title', $meta['titulo']);
            $set('slug', $meta['slug_sugerido'] ?: Str::slug($meta['titulo']));
        }

        if (filled($meta['categoria'])) {
            $categoria = Category::firstOrCreate(
                ['slug' => Str::slug($meta['categoria'])],
                ['name' => $meta['categoria']],
            );
            $set('categories', [$categoria->id]);
        }

        if (filled($meta['tags'])) {
            $tagIds = collect($meta['tags'])
                ->map(fn (string $nome) => Tag::firstOrCreate(
                    ['slug' => Str::slug($nome)],
                    ['name' => $nome],
                )->id)
                ->all();
            $set('tags', $tagIds);
        }
    }
}
