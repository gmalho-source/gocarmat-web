<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Schemas\ImportMarkdownForm;
use App\Services\DocxArtigoImportador;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ImportarArtigoMarkdown extends CreateRecord
{
    protected static string $resource = PostResource::class;

    public function form(Schema $schema): Schema
    {
        return ImportMarkdownForm::configure($schema);
    }

    /** @param  array<string, mixed>  $data */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $caminho = $data['markdown_file'];

        $resultado = app(DocxArtigoImportador::class)->processar(Storage::disk('local')->path($caminho), $data['slug']);

        $data['body'] = $resultado['body'];

        // A imagem de destaque já pode ter sido guardada mais cedo, ao
        // carregar o ficheiro (pré-visualização) — evita duplicar o ficheiro
        // em disco se processar() encontrar a mesma imagem outra vez.
        if (filled($data['featured_image'] ?? null) && filled($resultado['imagem_preambulo'])) {
            Storage::disk('public')->delete($resultado['imagem_preambulo']);
        } else {
            $data['featured_image'] ??= $resultado['imagem_preambulo'];
        }

        // Categorias e tags já ficam definidas ao carregar o ficheiro (ver
        // ImportMarkdownForm) através do estado normal do formulário — não se
        // mexe aqui em $data['categories']/$data['tags']: são relações, não
        // colunas de posts, e o Filament já as extrai e grava à parte.

        if (blank($data['meta_title'] ?? null) && filled($resultado['meta_title'])) {
            $data['meta_title'] = $resultado['meta_title'];
        }

        if (blank($data['meta_description'] ?? null) && filled($resultado['meta_description'])) {
            $data['meta_description'] = $resultado['meta_description'];
        }

        unset($data['markdown_file']);

        Storage::disk('local')->delete($caminho);

        return $data;
    }
}
