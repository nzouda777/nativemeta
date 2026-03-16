<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\TestEmail;

class EmailSettingsController extends Controller
{
    /**
     * Display email settings page.
     */
    public function index()
    {
        $currentMailer = config('mail.default');
        
        $mailers = [
            'mailpit' => [
                'name' => 'Mailpit (Développement)',
                'description' => 'Service de mail local pour le développement',
                'config' => [
                    'host' => config('mail.mailers.mailpit.host'),
                    'port' => config('mail.mailers.mailpit.port'),
                ],
            ],
            'smtp' => [
                'name' => 'SMTP (Production)',
                'description' => 'Service SMTP professionnel pour la production',
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                ],
            ],
            'ses' => [
                'name' => 'Amazon SES (Production)',
                'description' => 'Service email Amazon pour la production',
                'config' => [
                    'key' => config('services.ses.key') ? '••••••••••••••••' : '',
                    'region' => config('services.ses.region'),
                ],
            ],
        ];

        return inertia('Admin/EmailSettings', [
            'currentMailer' => $currentMailer,
            'mailers' => $mailers,
            'from' => [
                'address' => config('mail.from.address'),
                'name' => config('mail.from.name'),
            ],
        ]);
    }

    /**
     * Update email configuration.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mailer' => 'required|in:mailpit,smtp,ses',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
            'smtp_host' => 'required_if:mailer,smtp|string',
            'smtp_port' => 'required_if:mailer,smtp|integer',
            'smtp_username' => 'required_if:mailer,smtp|string',
            'smtp_password' => 'required_if:mailer,smtp|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'ses_key' => 'required_if:mailer,ses|string',
            'ses_secret' => 'required_if:mailer,ses|string',
            'ses_region' => 'required_if:mailer,ses|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Mettre à jour le fichier .env
        $updates = [
            'MAIL_MAILER' => $request->mailer,
            'MAIL_FROM_ADDRESS' => $request->from_address,
            'MAIL_FROM_NAME' => $request->from_name,
        ];

        if ($request->mailer === 'smtp') {
            $updates['MAIL_HOST'] = $request->smtp_host;
            $updates['MAIL_PORT'] = $request->smtp_port;
            $updates['MAIL_USERNAME'] = $request->smtp_username;
            $updates['MAIL_PASSWORD'] = $request->smtp_password;
            $updates['MAIL_ENCRYPTION'] = $request->smtp_encryption ?? 'tls';
        } elseif ($request->mailer === 'ses') {
            $updates['AWS_ACCESS_KEY_ID'] = $request->ses_key;
            $updates['AWS_SECRET_ACCESS_KEY'] = $request->ses_secret;
            $updates['AWS_DEFAULT_REGION'] = $request->ses_region;
        }

        foreach ($updates as $key => $value) {
            $this->updateEnvFile($key, $value);
        }

        return back()->with('success', 'Configuration email mise à jour avec succès.');
    }

    /**
     * Test email configuration.
     */
    public function testEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Adresse email invalide.',
            ], 400);
        }

        try {
            Mail::to($request->test_email)->send(new TestEmail());
            
            return response()->json([
                'success' => true,
                'message' => 'Email de test envoyé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get email logs.
     */
    public function getLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            return response()->json(['logs' => []]);
        }

        $content = file_get_contents($logFile);
        $lines = explode("\n", $content);
        
        // Filtrer les logs liés aux emails (derniers 100)
        $emailLogs = array_filter(array_reverse($lines), function($line) {
            return strpos($line, 'Mail') !== false || 
                   strpos($line, 'mail') !== false || 
                   strpos($line, 'SMTP') !== false;
        });

        $emailLogs = array_slice($emailLogs, 0, 100);

        return response()->json(['logs' => $emailLogs]);
    }

    /**
     * Update environment file.
     */
    private function updateEnvFile($key, $value)
    {
        $envPath = base_path('.env');
        
        if (!file_exists($envPath)) {
            return false;
        }

        $content = file_get_contents($envPath);
        
        // Échapper les caractères spéciaux
        $value = str_replace(['"', "'"], ['\"', "\'"], $value);
        
        // Si la variable existe, la mettre à jour
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            // Sinon, l'ajouter à la fin
            $content .= "\n{$key}={$value}";
        }
        
        file_put_contents($envPath, $content);
        
        return true;
    }
}
