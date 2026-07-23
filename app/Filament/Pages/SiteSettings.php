<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Definições';

    protected static ?string $title = 'Definições do site';

    protected string $view = 'filament.pages.site-settings';

    /** @var array<string, mixed> */
    public array $data = [];

    /** Chaves geridas nesta página. */
    protected const KEYS = [
        'ga4_id',
        'meta_pixel_id',
        'notification_email',
        'mailchimp_api_key',
        'mailchimp_list_id',
    ];

    public function mount(): void
    {
        foreach (self::KEYS as $key) {
            $this->data[$key] = Setting::get($key);
        }

        $this->form->fill($this->data);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Analytics e tracking')
                    ->description('Os scripts só são carregados no site depois do consentimento de cookies do visitante (RGPD).')
                    ->schema([
                        TextInput::make('ga4_id')
                            ->label('Google Analytics 4 — Measurement ID')
                            ->placeholder('G-XXXXXXXXXX'),
                        TextInput::make('meta_pixel_id')
                            ->label('Meta Pixel ID')
                            ->placeholder('123456789012345'),
                    ]),
                Section::make('Notificações')
                    ->schema([
                        TextInput::make('notification_email')
                            ->label('E-mail que recebe as marcações')
                            ->email()
                            ->placeholder('apoiocliente@gocarmat.pt'),
                    ]),
                Section::make('Newsletter (Mailchimp)')
                    ->schema([
                        TextInput::make('mailchimp_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('mailchimp_list_id')
                            ->label('Audience ID'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()
            ->title('Definições guardadas')
            ->success()
            ->send();
    }
}
