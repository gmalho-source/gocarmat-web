<?php

namespace App\Filament\Resources\Offices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, ?string $state, callable $set) {
                        if ($operation === 'create' && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('address_line1')
                    ->label('Morada (linha 1)')
                    ->required(),
                TextInput::make('address_line2')
                    ->label('Morada (linha 2 — código postal e localidade)'),
                TextInput::make('schedule')
                    ->label('Horário')
                    ->placeholder('SEG-SEX: 08:30 – 18:00')
                    ->required(),
                TextInput::make('phones')
                    ->label('Telefones')
                    ->placeholder('925 410 248 / 910 684 941')
                    ->required(),
                TextInput::make('phone_note')
                    ->label('Nota dos telefones')
                    ->placeholder('Chamada para a rede móvel nacional'),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                TextInput::make('maps_url')
                    ->label('Link Google Maps')
                    ->url(),
                TextInput::make('sort_order')
                    ->label('Ordem')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Ativa')
                    ->default(true),
            ]);
    }
}
