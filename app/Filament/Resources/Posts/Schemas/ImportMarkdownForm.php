<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Category;
use App\Models\Tag;
use App\Services\DocxArtigoImportador;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                            ->helperText('O título, o excerto, as categorias, as tags, os campos de SEO e as imagens (corpo e destaque) preenchem-se sozinhos a partir do ficheiro.')
                            ->disk('local')
                            ->directory('markdown-imports')
                            // Não se usa acceptedFileTypes(): a regra "mimetypes" que essa
                            // função adiciona automaticamente não lida bem com a forma como
                            // o Filament guarda o estado deste campo, e falhava sempre na
                            // submissão mesmo com um .docx válido. Valida-se a extensão à
                            // mão, o que chega para o que precisamos aqui.
                            ->rule(function () {
                                return function (string $attribute, mixed $value, \Closure $fail) {
                                    $arquivo = is_array($value) ? \Illuminate\Support\Arr::first($value) : $value;
                                    $nome = $arquivo instanceof \Illuminate\Http\UploadedFile
                                        ? $arquivo->getClientOriginalName()
                                        : (string) $arquivo;

                                    if (strtolower(pathinfo($nome, PATHINFO_EXTENSION)) !== 'docx') {
                                        $fail('O ficheiro tem de ser um documento Word (.docx).');
                                    }
                                };
                            })
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (mixed $state, callable $set) {
                                // Enquanto o formulário ainda não foi submetido, o ficheiro
                                // ainda não tem um caminho gravado — $state é o próprio
                                // ficheiro temporário do Livewire, não uma string.
                                if (! $state instanceof TemporaryUploadedFile) {
                                    return;
                                }

                                $previa = app(DocxArtigoImportador::class)->previa($state->getRealPath());

                                if (filled($previa['titulo'])) {
                                    $set('title', $previa['titulo']);
                                    $set('slug', Str::slug($previa['titulo']));
                                }

                                if (filled($previa['excerto'])) {
                                    $set('excerpt', $previa['excerto']);
                                }

                                if (filled($previa['meta_title'])) {
                                    $set('meta_title', $previa['meta_title']);
                                }

                                if (filled($previa['meta_description'])) {
                                    $set('meta_description', $previa['meta_description']);
                                }

                                if (filled($previa['categoria'])) {
                                    $categoria = Category::firstOrCreate(
                                        ['slug' => Str::slug($previa['categoria'])],
                                        ['name' => $previa['categoria']],
                                    );
                                    $set('categories', [$categoria->id]);
                                }

                                if (filled($previa['tags'])) {
                                    $tagIds = collect($previa['tags'])
                                        ->map(fn (string $nome) => Tag::firstOrCreate(
                                            ['slug' => Str::slug($nome)],
                                            ['name' => $nome],
                                        )->id)
                                        ->all();
                                    $set('tags', $tagIds);
                                }

                                $imagemDestaque = app(DocxArtigoImportador::class)->imagemDestaque($state->getRealPath());

                                if (filled($imagemDestaque)) {
                                    $set('featured_image', $imagemDestaque);
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
                            ->helperText('Se o ficheiro .docx tiver uma linha "Categorias do artigo: ...", é aplicada automaticamente ao carregar o ficheiro.')
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
                            ->helperText('Se o .docx tiver uma imagem marcada "Imagem de topo/destaque:" (ou, na falta dessa, uma imagem antes do título), é aplicada aqui automaticamente ao carregar o ficheiro.')
                            ->image()
                            ->disk('public')
                            ->directory('blog')
                            ->imageEditor()
                            ->maxSize(4096),
                        Select::make('tags')
                            ->label('Tags')
                            ->helperText('Se o ficheiro .docx tiver uma linha "Tags: ...", são aplicadas automaticamente ao carregar o ficheiro.')
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
                            ->helperText('Recomendado: até 60 caracteres. Se o ficheiro .docx tiver uma linha "Meta title: ...", é aplicada automaticamente ao carregar o ficheiro.'),
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
                            ->helperText('Recomendado: 150–160 caracteres. Se o ficheiro .docx tiver uma linha "Meta description: ...", é aplicada automaticamente ao carregar o ficheiro.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
