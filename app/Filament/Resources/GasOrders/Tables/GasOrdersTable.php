<?php

namespace App\Filament\Resources\GasOrders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class GasOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Recebido a')
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
                TextColumn::make('bilha')
                    ->label('Bilha')
                    ->badge(),
                TextColumn::make('janela_entrega')
                    ->label('Janela de entrega'),
                IconColumn::make('is_read')
                    ->label('Lido')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => $record->read_at !== null),
            ])
            ->filters([
                TernaryFilter::make('read_at')
                    ->label('Lido')
                    ->nullable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
