<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StripeSettingsController extends Controller
{
    /**
     * Display the Stripe settings page.
     */
    public function index()
    {
        $currentMode = config('services.stripe.mode', 'test');
        
        $settings = [
            'mode' => $currentMode,
            'test' => [
                'key' => config('services.stripe.test.key'),
                'secret' => config('services.stripe.test.secret') ? '••••••••••••••••' : '',
                'webhook_secret' => config('services.stripe.test.webhook_secret') ? '••••••••••••••••' : '',
            ],
            'live' => [
                'key' => config('services.stripe.live.key'),
                'secret' => config('services.stripe.live.secret') ? '••••••••••••••••' : '',
                'webhook_secret' => config('services.stripe.live.webhook_secret') ? '••••••••••••••••' : '',
            ],
        ];

        return inertia('Admin/StripeSettings', [
            'settings' => $settings,
            'currentMode' => $currentMode,
        ]);
    }

    /**
     * Update the Stripe mode.
     */
    public function updateMode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mode' => 'required|in:test,live',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $mode = $request->mode;
        
        // Vérifier que les clés pour le mode demandé sont configurées
        $key = config("services.stripe.{$mode}.key");
        $secret = config("services.stripe.{$mode}.secret");
        
        if (empty($key) || empty($secret)) {
            return back()->with('error', "Les clés Stripe pour le mode {$mode} ne sont pas configurées.");
        }

        // Mettre à jour le fichier .env
        $this->updateEnvFile('STRIPE_MODE', $mode);

        return back()->with('success', "Mode Stripe changé vers: " . ($mode === 'live' ? 'Production' : 'Test'));
    }

    /**
     * Update Stripe credentials.
     */
    public function updateCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mode' => 'required|in:test,live',
            'key' => 'required|string',
            'secret' => 'required|string',
            'webhook_secret' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $mode = $request->mode;
        
        // Mettre à jour les variables d'environnement
        $this->updateEnvFile("STRIPE_{$mode}_KEY", $request->key);
        $this->updateEnvFile("STRIPE_{$mode}_SECRET", $request->secret);
        
        if ($request->webhook_secret) {
            $this->updateEnvFile("STRIPE_{$mode}_WEBHOOK_SECRET", $request->webhook_secret);
        }

        return back()->with('success', "Clés Stripe pour le mode {$mode} mises à jour avec succès.");
    }

    /**
     * Test Stripe connection.
     */
    public function testConnection(Request $request)
    {
        $mode = $request->mode ?? config('services.stripe.mode', 'test');
        
        try {
            $key = config("services.stripe.{$mode}.key");
            $secret = config("services.stripe.{$mode}.secret");
            
            if (empty($key) || empty($secret)) {
                return response()->json([
                    'success' => false,
                    'message' => "Les clés Stripe pour le mode {$mode} ne sont pas configurées."
                ]);
            }

            // Test avec une simple requête à l'API Stripe
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/account");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $secret . ":");
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Stripe-Version: 2023-10-16",
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200) {
                $account = json_decode($response, true);
                return response()->json([
                    'success' => true,
                    'message' => "Connexion Stripe réussie en mode {$mode}",
                    'account' => [
                        'id' => $account['id'],
                        'display_name' => $account['display_name'],
                        'country' => $account['country'],
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Échec de connexion à Stripe (HTTP {$httpCode})"
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => "Erreur lors du test de connexion: " . $e->getMessage()
            ]);
        }
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
        
        // Si la variable existe, la mettre à jour
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            // Sinon, l'ajouter à la fin
            $content .= "\n{$key}={$value}";
        }
        
        file_put_contents($envPath, $content);
        
        // Recharger la configuration
        app()->make('config')->set("services.stripe." . strtolower(str_replace('STRIPE_', '', $key)), $value);
        
        return true;
    }
}
