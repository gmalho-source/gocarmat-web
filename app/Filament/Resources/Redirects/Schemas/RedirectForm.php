<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('from_path')
                    ->label('Caminho antigo')
                    ->placeholder('/caminho-antigo')
                    ->helperText('O URL antigo que deixou de existir, sem o domínio — tem de começar com "/".')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['regex:/^\/\S*$/'])
                    ->validationMessages(['regex' => 'Tem de começar com "/" e não pode ter espaços.']),
                TextInput::make('to_path')
                    ->label('Caminho novo')
                    ->placeholder('/blog/novo-slug')
                    ->helperText('Para onde os visitantes são enviados — um caminho (/blog/slug) ou um URL completo.')
                    ->required(),
                TextInput::make('hits')
                    ->label('Vezes usado')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
