<?php

namespace App\Filament\Resources\NewsletterSubscribers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NewsletterSubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('name'),
                TextInput::make('source')
                    ->required()
                    ->default('footer'),
                TextInput::make('brevo_status')
                    ->required()
                    ->default('pending'),
                Textarea::make('brevo_error')
                    ->columnSpanFull(),
                DateTimePicker::make('synced_at'),
            ]);
    }
}
