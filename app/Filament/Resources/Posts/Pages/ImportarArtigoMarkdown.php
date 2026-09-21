<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Schemas\ImportMarkdownForm;
use App\Services\ArtigoMarkdownImportador;
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
        $ext = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

        if ($ext === 'docx') {
            $resultado = app(DocxArtigoImportador::class)->processar(Storage::disk('local')->path($caminho), $data['slug']);
            $data['body'] = $resultado['body'];
            $data['featured_image'] ??= $resultado['imagem_preambulo'];
        } else {
            $markdown = Storage::disk('local')->get($caminho);
            $data['body'] = app(ArtigoMarkdownImportador::class)->paraHtml($markdown, $data['slug']);
        }

        unset($data['markdown_file']);

        Storage::disk('local')->delete($caminho);

        return $data;
    }
}
