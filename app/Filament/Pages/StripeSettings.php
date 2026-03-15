<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class StripeSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Configuration Stripe';
    protected static ?string $title = 'Configuration Stripe';

    protected static string $view = 'filament.pages.stripe-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::where('group', 'stripe')->pluck('value', 'key')->toArray();
        
        $this->form->fill([
            'stripe_mode' => $settings['stripe_mode'] ?? 'test',
            'stripe_test_public_key' => $settings['stripe_test_public_key'] ?? config('services.stripe.test_key'),
            'stripe_test_secret_key' => $settings['stripe_test_secret_key'] ?? config('services.stripe.test_secret'),
            'stripe_live_public_key' => $settings['stripe_live_public_key'] ?? config('services.stripe.live_key'),
            'stripe_live_secret_key' => $settings['stripe_live_secret_key'] ?? config('services.stripe.live_secret'),
            'stripe_webhook_secret' => $settings['stripe_webhook_secret'] ?? config('services.stripe.webhook_secret'),
            'stripe_currency' => $settings['stripe_currency'] ?? 'eur',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mode de fonctionnement')
                    ->schema([
                        Select::make('stripe_mode')
                            ->label('Mode Stripe')
                            ->options([
                                'test' => 'Test (Sandbox)',
                                'live' => 'Production (Live)',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('stripe_currency')
                            ->label('Devise par défaut')
                            ->default('eur')
                            ->required(),
                    ]),

                Section::make('Clés de Test')
                    ->description('Utilisées quand le mode est sur "Test"')
                    ->schema([
                        TextInput::make('stripe_test_public_key')
                            ->label('Clé Publique Test (pk_test_...)')
                            ->password()
                            ->revealable(),
                        TextInput::make('stripe_test_secret_key')
                            ->label('Clé Secrète Test (sk_test_...)')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Section::make('Clés de Production')
                    ->description('⚠️ Utilisées quand le mode est sur "Production"')
                    ->schema([
                        TextInput::make('stripe_live_public_key')
                            ->label('Clé Publique Live (pk_live_...)')
                            ->password()
                            ->revealable(),
                        TextInput::make('stripe_live_secret_key')
                            ->label('Clé Secrète Live (sk_live_...)')
                            ->password()
                            ->revealable(),
                    ])->columns(2),

                Section::make('Webhooks')
                    ->schema([
                        TextInput::make('stripe_webhook_secret')
                            ->label('Secret Webhook (whsec_...)')
                            ->password()
                            ->revealable(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value, 'stripe');
        }

        Notification::make()
            ->title('Configuration Stripe enregistrée')
            ->success()
            ->send();
    }
}
