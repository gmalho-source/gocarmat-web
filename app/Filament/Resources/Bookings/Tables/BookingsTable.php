<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Recebida a')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->weight(fn ($record) => $record->read_at ? null : 'bold'),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                TextColumn::make('service')
                    ->label('Serviço')
                    ->badge(),
                IconColumn::make('newsletter_opt_in')
                    ->label('Newsletter')
                    ->boolean(),
                IconColumn::make('is_read')
                    ->label('Lida')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->read_at !== null),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Lida')
                    ->nullable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
