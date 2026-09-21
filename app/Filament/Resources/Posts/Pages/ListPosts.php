<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListPosts extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importar-markdown')
                ->label('Importar de Markdown ou Word')
                ->icon(Heroicon::OutlinedDocumentArrowUp)
                ->color('gray')
                ->url(fn (): string => PostResource::getUrl('importar-markdown')),
            CreateAction::make(),
        ];
    }
}
