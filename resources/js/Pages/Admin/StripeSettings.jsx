import React, { useState } from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group';
import { Separator } from '@/Components/ui/separator';
import { 
  CreditCard, 
  TestTube, 
  Production, 
  Key, 
  Shield, 
  CheckCircle, 
  AlertCircle,
  RefreshCw
} from 'lucide-react';

export default function StripeSettings({ settings, currentMode }) {
    const { flash } = usePage().props;
    const [testingConnection, setTestingConnection] = useState(null);
    const [testResult, setTestResult] = useState(null);

    const modeForm = useForm({
        mode: currentMode,
    });

    const credentialsForm = useForm({
        mode: 'test',
        key: '',
        secret: '',
        webhook_secret: '',
    });

    const handleModeChange = (e) => {
        modeForm.setData('mode', e.target.value);
    };

    const updateMode = (e) => {
        e.preventDefault();
        modeForm.post(route('admin.stripe.mode'), {
            onSuccess: () => {
                window.location.reload();
            },
        });
    };

    const updateCredentials = (e) => {
        e.preventDefault();
        credentialsForm.post(route('admin.stripe.credentials'), {
            onSuccess: () => {
                credentialsForm.reset();
                window.location.reload();
            },
        });
    };

    const testConnection = async (mode) => {
        setTestingConnection(mode);
        setTestResult(null);

        try {
            const response = await fetch(route('admin.stripe.test'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ mode }),
            });

            const result = await response.json();
            setTestResult(result);
        } catch (error) {
            setTestResult({
                success: false,
                message: 'Erreur lors du test de connexion',
            });
        } finally {
            setTestingConnection(null);
        }
    };

    const getModeIcon = (mode) => {
        return mode === 'live' ? <Production className="h-4 w-4" /> : <TestTube className="h-4 w-4" />;
    };

    const getModeColor = (mode) => {
        return mode === 'live' ? 'bg-green-500' : 'bg-blue-500';
    };

    const getModeLabel = (mode) => {
        return mode === 'live' ? 'Production' : 'Test';
    };

    return (
        <>
            <Head title="Paramètres Stripe" />

            <div className="space-y-6">
                <div>
                    <h2 className="text-3xl font-bold tracking-tight">Paramètres Stripe</h2>
                    <p className="text-muted-foreground">
                        Gérez les clés API Stripe et basculez entre les modes test et production.
                    </p>
                </div>

                {flash.success && (
                    <Alert className="bg-green-50 border-green-200">
                        <CheckCircle className="h-4 w-4 text-green-600" />
                        <AlertDescription className="text-green-800">
                            {flash.success}
                        </AlertDescription>
                    </Alert>
                )}

                {flash.error && (
                    <Alert className="bg-red-50 border-red-200">
                        <AlertCircle className="h-4 w-4 text-red-600" />
                        <AlertDescription className="text-red-800">
                            {flash.error}
                        </AlertDescription>
                    </Alert>
                )}

                <Tabs defaultValue="mode" className="space-y-4">
                    <TabsList>
                        <TabsTrigger value="mode">Mode</TabsTrigger>
                        <TabsTrigger value="credentials">Clés API</TabsTrigger>
                        <TabsTrigger value="test">Test de connexion</TabsTrigger>
                    </TabsList>

                    <TabsContent value="mode">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <CreditCard className="h-5 w-5" />
                                    Mode Stripe actuel
                                </CardTitle>
                                <CardDescription>
                                    Choisissez entre le mode test et le mode production.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="flex items-center gap-3">
                                    <Badge className={`${getModeColor(currentMode)} text-white`}>
                                        {getModeIcon(currentMode)}
                                        <span className="ml-1">{getModeLabel(currentMode)}</span>
                                    </Badge>
                                    <span className="text-sm text-muted-foreground">
                                        Mode actuellement actif
                                    </span>
                                </div>

                                <Separator />

                                <form onSubmit={updateMode} className="space-y-4">
                                    <RadioGroup value={modeForm.data.mode} onValueChange={handleModeChange}>
                                        <div className="flex items-center space-x-2">
                                            <RadioGroupItem value="test" id="test" />
                                            <Label htmlFor="test" className="flex items-center gap-2 cursor-pointer">
                                                <TestTube className="h-4 w-4" />
                                                <span>Mode Test</span>
                                                <Badge variant="outline" className="ml-2">Recommandé pour développement</Badge>
                                            </Label>
                                        </div>
                                        <div className="flex items-center space-x-2">
                                            <RadioGroupItem value="live" id="live" />
                                            <Label htmlFor="live" className="flex items-center gap-2 cursor-pointer">
                                                <Production className="h-4 w-4" />
                                                <span>Mode Production</span>
                                                <Badge variant="destructive" className="ml-2">Paiements réels</Badge>
                                            </Label>
                                        </div>
                                    </RadioGroup>

                                    <Button 
                                        type="submit" 
                                        disabled={modeForm.processing || modeForm.data.mode === currentMode}
                                        className="w-full"
                                    >
                                        {modeForm.processing ? (
                                            <RefreshCw className="mr-2 h-4 w-4 animate-spin" />
                                        ) : null}
                                        Changer vers {getModeLabel(modeForm.data.mode)}
                                    </Button>
                                </form>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="credentials">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Key className="h-5 w-5" />
                                    Clés API Stripe
                                </CardTitle>
                                <CardDescription>
                                    Configurez les clés API pour les modes test et production.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <form onSubmit={updateCredentials} className="space-y-6">
                                    <div>
                                        <Label>Mode</Label>
                                        <RadioGroup 
                                            value={credentialsForm.data.mode} 
                                            onValueChange={(value) => credentialsForm.setData('mode', value)}
                                            className="mt-2"
                                        >
                                            <div className="flex items-center space-x-2">
                                                <RadioGroupItem value="test" id="cred-test" />
                                                <Label htmlFor="cred-test">Mode Test</Label>
                                            </div>
                                            <div className="flex items-center space-x-2">
                                                <RadioGroupItem value="live" id="cred-live" />
                                                <Label htmlFor="cred-live">Mode Production</Label>
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <Separator />

                                    <div className="space-y-4">
                                        <div>
                                            <Label htmlFor="key">Clé Publique</Label>
                                            <Input
                                                id="key"
                                                type="text"
                                                placeholder="pk_test_..."
                                                value={credentialsForm.data.key}
                                                onChange={(e) => credentialsForm.setData('key', e.target.value)}
                                                className="mt-1"
                                            />
                                        </div>

                                        <div>
                                            <Label htmlFor="secret">Clé Secrète</Label>
                                            <Input
                                                id="secret"
                                                type="password"
                                                placeholder="sk_test_..."
                                                value={credentialsForm.data.secret}
                                                onChange={(e) => credentialsForm.setData('secret', e.target.value)}
                                                className="mt-1"
                                            />
                                        </div>

                                        <div>
                                            <Label htmlFor="webhook_secret">Secret Webhook (optionnel)</Label>
                                            <Input
                                                id="webhook_secret"
                                                type="password"
                                                placeholder="whsec_..."
                                                value={credentialsForm.data.webhook_secret}
                                                onChange={(e) => credentialsForm.setData('webhook_secret', e.target.value)}
                                                className="mt-1"
                                            />
                                        </div>
                                    </div>

                                    <Alert>
                                        <Shield className="h-4 w-4" />
                                        <AlertDescription>
                                            Les clés secrètes sont stockées de manière sécurisée et ne sont jamais affichées en clair.
                                        </AlertDescription>
                                    </Alert>

                                    <Button 
                                        type="submit" 
                                        disabled={credentialsForm.processing}
                                        className="w-full"
                                    >
                                        {credentialsForm.processing ? (
                                            <RefreshCw className="mr-2 h-4 w-4 animate-spin" />
                                        ) : null}
                                        Mettre à jour les clés {credentialsForm.data.mode === 'live' ? 'Production' : 'Test'}
                                    </Button>
                                </form>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="test">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <RefreshCw className="h-5 w-5" />
                                    Test de connexion
                                </CardTitle>
                                <CardDescription>
                                    Vérifiez que vos clés API sont correctement configurées.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="grid grid-cols-2 gap-4">
                                    <Button
                                        onClick={() => testConnection('test')}
                                        disabled={testingConnection === 'test'}
                                        variant="outline"
                                        className="flex items-center gap-2"
                                    >
                                        {testingConnection === 'test' ? (
                                            <RefreshCw className="h-4 w-4 animate-spin" />
                                        ) : (
                                            <TestTube className="h-4 w-4" />
                                        )}
                                        Tester Mode Test
                                    </Button>

                                    <Button
                                        onClick={() => testConnection('live')}
                                        disabled={testingConnection === 'live'}
                                        variant="outline"
                                        className="flex items-center gap-2"
                                    >
                                        {testingConnection === 'live' ? (
                                            <RefreshCw className="h-4 w-4 animate-spin" />
                                        ) : (
                                            <Production className="h-4 w-4" />
                                        )}
                                        Tester Mode Live
                                    </Button>
                                </div>

                                {testResult && (
                                    <Alert className={testResult.success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}>
                                        {testResult.success ? (
                                            <CheckCircle className="h-4 w-4 text-green-600" />
                                        ) : (
                                            <AlertCircle className="h-4 w-4 text-red-600" />
                                        )}
                                        <AlertDescription className={testResult.success ? 'text-green-800' : 'text-red-800'}>
                                            {testResult.message}
                                            {testResult.account && (
                                                <div className="mt-2 text-sm">
                                                    <div>Compte: {testResult.account.display_name}</div>
                                                    <div>ID: {testResult.account.id}</div>
                                                    <div>Pays: {testResult.account.country}</div>
                                                </div>
                                            )}
                                        </AlertDescription>
                                    </Alert>
                                )}

                                <div className="text-sm text-muted-foreground">
                                    <p>Le test de connexion vérifie que vos clés API sont valides et peuvent communiquer avec l'API Stripe.</p>
                                </div>
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>
            </div>
        </>
    );
}
