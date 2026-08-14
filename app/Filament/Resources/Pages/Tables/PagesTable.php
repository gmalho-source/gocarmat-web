<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Endereço')
                    ->formatStateUsing(fn (string $state): string => '/'.$state)
                    ->searchable(),
                TextColumn::make('content')
                    ->label('Blocos')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => is_array($state) ? count($state).' blocos' : '—'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'published' ? 'Publicada' : 'Rascunho')
                    ->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
                IconColumn::make('show_in_menu')
                    ->label('No menu')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(['draft' => 'Rascunho', 'published' => 'Publicada']),
            ])
            ->recordActions([
                Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record): string => url('/'.$record->slug))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
