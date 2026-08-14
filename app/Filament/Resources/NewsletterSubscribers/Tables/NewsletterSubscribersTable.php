<?php

namespace App\Filament\Resources\NewsletterSubscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsletterSubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('source')
                    ->label('Origem')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'marcacoes' ? 'Marcações' : 'Rodapé'),
                TextColumn::make('mailchimp_status')
                    ->label('Mailchimp')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'subscribed' => 'Sincronizado',
                        'failed' => 'Falhou',
                        default => 'Por sincronizar',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'subscribed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->tooltip(fn ($record): ?string => $record->mailchimp_error),
                TextColumn::make('created_at')
                    ->label('Subscreveu a')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('mailchimp_status')
                    ->label('Estado no Mailchimp')
                    ->options([
                        'subscribed' => 'Sincronizado',
                        'pending' => 'Por sincronizar',
                        'failed' => 'Falhou',
                    ]),
                SelectFilter::make('source')
                    ->label('Origem')
                    ->options(['footer' => 'Rodapé', 'marcacoes' => 'Marcações']),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
