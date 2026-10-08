<?php

namespace App\Filament\Resources\GasOrders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GasOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nome'),
                TextEntry::make('company')
                    ->label('Empresa')
                    ->placeholder('—'),
                TextEntry::make('email')
                    ->label('E-mail')
                    ->copyable(),
                TextEntry::make('phone')
                    ->label('Telefone'),
                TextEntry::make('bilha')
                    ->label('Bilha')
                    ->badge(),
                TextEntry::make('janela_entrega')
                    ->label('Janela de entrega'),
                TextEntry::make('created_at')
                    ->label('Recebido a')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
