<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
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
                TextEntry::make('service')
                    ->label('Serviço')
                    ->placeholder('—'),
                TextEntry::make('notes')
                    ->label('Notas')
                    ->placeholder('—')
                    ->columnSpanFull(),
                IconEntry::make('newsletter_opt_in')
                    ->label('Aceitou newsletter')
                    ->boolean(),
                TextEntry::make('source')
                    ->label('Origem')
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Recebida a')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
