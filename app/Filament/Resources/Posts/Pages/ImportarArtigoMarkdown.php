<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Schemas\ImportMarkdownForm;
use App\Models\Category;
use App\Models\Tag;
use App\Services\DocxArtigoImportador;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $data['featured_image'] ??= $resultado['imagem_preambulo'];

        if (blank($data['categories'] ?? null) && filled($resultado['categoria'])) {
            $categoria = Category::firstOrCreate(
                ['slug' => Str::slug($resultado['categoria'])],
                ['name' => $resultado['categoria']],
            );
            $data['categories'] = [$categoria->id];
        }

        if (blank($data['tags'] ?? null) && filled($resultado['tags'])) {
            $data['tags'] = collect($resultado['tags'])
                ->map(fn (string $nome) => Tag::firstOrCreate(
                    ['slug' => Str::slug($nome)],
                    ['name' => $nome],
                )->id)
                ->all();
        }

        unset($data['markdown_file']);

        Storage::disk('local')->delete($caminho);

        return $data;
    }
}
