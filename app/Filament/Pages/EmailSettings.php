<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class EmailSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Configuration Email';
    protected static ?string $title = 'Configuration Email';
    protected static string $view = 'filament.pages.email-settings';

    public ?array $data = [];
    public ?string $testResult = null;
    public ?bool $testingEmail = false;

    public function mount(): void
    {
        $this->form->fill([
            'mail_mailer' => config('mail.default', 'mailpit'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Configuration générale')
                    ->schema([
                        Select::make('mail_mailer')
                            ->label('Service d\'envoi')
                            ->options([
                                'mailpit' => 'Mailpit (Développement)',
                                'smtp' => 'SMTP (Production)',
                                'ses' => 'Amazon SES (Production)',
                            ])
                            ->required()
                            ->reactive(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('mail_from_address')
                                    ->label('Email de l\'expéditeur')
                                    ->email()
                                    ->required(),
                                TextInput::make('mail_from_name')
                                    ->label('Nom de l\'expéditeur')
                                    ->required(),
                            ]),
                    ]),

                Section::make('Configuration SMTP')
                    ->description('Visible uniquement quand le service SMTP est sélectionné')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('mail_host')
                                    ->label('Hôte SMTP')
                                    ->placeholder('mail.privateemail.com')
                                    ->helperText('Pour PrivateEmail.com: mail.privateemail.com'),
                                TextInput::make('mail_port')
                                    ->label('Port SMTP')
                                    ->numeric()
                                    ->placeholder('587')
                                    ->helperText('Généralement 587 pour TLS, 465 pour SSL'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('mail_username')
                                    ->label('Nom d\'utilisateur')
                                    ->helperText('Votre email professionnel: contact@peakconversion.dev')
                                    ->placeholder('contact@peakconversion.dev'),
                                TextInput::make('mail_password')
                                    ->label('Mot de passe')
                                    ->password()
                                    ->revealable()
                                    ->helperText('Mot de passe de votre email PrivateEmail.com'),
                            ]),
                        Select::make('mail_encryption')
                            ->label('Chiffrement')
                            ->options([
                                'tls' => 'TLS (recommandé)',
                                'ssl' => 'SSL',
                                '' => 'Aucun (non recommandé)',
                            ])
                            ->default('tls')
                            ->helperText('TLS utilise le port 587, SSL utilise le port 465'),
                    ])
                    ->visible(fn ($get) => $get('mail_mailer') === 'smtp'),

                Section::make('Configuration Amazon SES')
                    ->description('Visible uniquement quand Amazon SES est sélectionné')
                    ->schema([
                        TextInput::make('aws_access_key_id')
                            ->label('Clé d\'accès AWS')
                            ->placeholder('AKIAIOSFODNN7EXAMPLE'),
                        TextInput::make('aws_secret_access_key')
                            ->label('Clé secrète AWS')
                            ->password()
                            ->placeholder('wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY'),
                        TextInput::make('aws_default_region')
                            ->label('Région AWS')
                            ->placeholder('eu-west-1'),
                    ])
                    ->visible(fn ($get) => $get('mail_mailer') === 'ses'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Mettre à jour les configurations
        $updates = [];
        
        switch ($data['mail_mailer']) {
            case 'smtp':
                $updates = [
                    'MAIL_MAILER' => 'smtp',
                    'MAIL_HOST' => $data['mail_host'],
                    'MAIL_PORT' => $data['mail_port'],
                    'MAIL_USERNAME' => $data['mail_username'],
                    'MAIL_PASSWORD' => $data['mail_password'],
                    'MAIL_ENCRYPTION' => $data['mail_encryption'],
                ];
                break;
            case 'ses':
                $updates = [
                    'MAIL_MAILER' => 'ses',
                    'AWS_ACCESS_KEY_ID' => $data['aws_access_key_id'],
                    'AWS_SECRET_ACCESS_KEY' => $data['aws_secret_access_key'],
                    'AWS_DEFAULT_REGION' => $data['aws_default_region'],
                ];
                break;
            default:
                $updates = [
                    'MAIL_MAILER' => 'mailpit',
                ];
                break;
        }

        // Ajouter les champs communs
        $updates['MAIL_FROM_ADDRESS'] = $data['mail_from_address'];
        $updates['MAIL_FROM_NAME'] = $data['mail_from_name'];

        // Mettre à jour le fichier .env
        foreach ($updates as $key => $value) {
            $this->updateEnvFile($key, $value);
        }

        Notification::make()
            ->title('Configuration email mise à jour')
            ->success()
            ->send();
    }

    public function testEmail(): void
    {
        $this->testingEmail = true;
        $this->testResult = null;

        try {
            // Vérifier la configuration actuelle
            $currentMailer = config('mail.default');
            $this->testResult = "Test d'envoi avec le service: {$currentMailer}...";
            
            Mail::to('rodriguenzouda35@gmail.com')->send(new TestEmail());
            $this->testResult = 'Email de test envoyé avec succès !';
            
            // Informations de débogage
            if ($currentMailer === 'mailpit') {
                $this->testResult .= ' (Consultez http://localhost:8025 pour voir l\'email)';
            }
            
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Détecter les erreurs SMTP courantes
            if (str_contains($errorMessage, 'Username and Password not accepted')) {
                $this->testResult = '❌ **Erreur SMTP** : Nom d\'utilisateur ou mot de passe incorrect.<br><br>';
                $this->testResult .= '🔧 **Vérifiez** :<br>';
                $this->testResult .= '1. L\'email de l\'expéditeur (contact@peakconversion.dev) existe bien<br>';
                $this->testResult .= '2. Le mot de passe est correct pour cet email<br>';
                $this->testResult .= '3. L\'hôte SMTP est correct (mail.privateemail.com)<br>';
                $this->testResult .= '4. Le port et le chiffrement correspondent à votre configuration';
            } elseif (str_contains($errorMessage, 'Connection refused') || str_contains($errorMessage, 'No connection')) {
                $this->testResult = '❌ **Erreur de connexion** : Impossible de se connecter au serveur SMTP.<br><br>';
                $this->testResult .= '🔧 **Vérifiez** :<br>';
                $this->testResult .= '1. L\'hôte SMTP est correct<br>';
                $this->testResult .= '2. Le port est ouvert et accessible<br>';
                $this->testResult .= '3. Aucun firewall ne bloque la connexion';
            } else {
                $this->testResult = 'Erreur lors de l\'envoi : ' . $errorMessage;
            }
            
            // Ajouter des informations de débogage
            $this->testResult .= '<br><br>📊 **Configuration actuelle** :<br>';
            $this->testResult .= '- Service : ' . config('mail.default') . '<br>';
            $this->testResult .= '- Hôte : ' . config('mail.mailers.' . config('mail.default') . '.host') . '<br>';
            $this->testResult .= '- Port : ' . config('mail.mailers.' . config('mail.default') . '.port') . '<br>';
            $this->testResult .= '- Username : ' . config('mail.mailers.' . config('mail.default') . '.username');
        }

        $this->testingEmail = false;
    }

    protected function getActions(): array
    {
        return [
            Action::make('save')
                ->label('Enregistrer')
                ->action('save')
                ->icon('heroicon-o-check'),
                
            Action::make('testEmail')
                ->label('Tester l\'envoi')
                ->action('testEmail')
                ->icon('heroicon-o-paper-airplane')
                ->color('warning'),
        ];
    }

    private function updateEnvFile($key, $value): void
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            return;
        }

        $content = file_get_contents($envPath);
        
        // Échapper les caractères spéciaux
        $value = str_replace(['"', "'"], ['\"', "\'"], $value);
        
        // Si la variable existe, la mettre à jour
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
        } else {
            // Sinon, l'ajouter à la fin
            $content .= "\n{$key}=\"{$value}\"";
        }
        
        file_put_contents($envPath, $content);
    }
}
