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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Textarea } from '@/Components/ui/textarea';
import { 
  Mail, 
  Settings, 
  TestTube, 
  Server, 
  Cloud, 
  CheckCircle, 
  AlertCircle,
  RefreshCw,
  Send,
  FileText
} from 'lucide-react';

export default function EmailSettings({ currentMailer, mailers, from }) {
    const { flash } = usePage().props;
    const [testingEmail, setTestingEmail] = useState(false);
    const [testResult, setTestResult] = useState(null);
    const [showLogs, setShowLogs] = useState(false);
    const [emailLogs, setEmailLogs] = useState([]);

    const form = useForm({
        mailer: currentMailer,
        from_address: from.address,
        from_name: from.name,
        smtp_host: '',
        smtp_port: '',
        smtp_username: '',
        smtp_password: '',
        smtp_encryption: 'tls',
        ses_key: '',
        ses_secret: '',
        ses_region: '',
    });

    const testEmailForm = useForm({
        test_email: '',
    });

    const handleMailerChange = (value) => {
        form.setData('mailer', value);
    };

    const updateSettings = (e) => {
        e.preventDefault();
        form.post(route('admin.email.update'), {
            onSuccess: () => {
                window.location.reload();
            },
        });
    };

    const sendTestEmail = async (e) => {
        e.preventDefault();
        setTestingEmail(true);
        setTestResult(null);

        try {
            const response = await fetch(route('admin.email.test'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ test_email: testEmailForm.data.test_email }),
            });

            const result = await response.json();
            setTestResult(result);
        } catch (error) {
            setTestResult({
                success: false,
                message: 'Erreur lors du test d\'envoi',
            });
        } finally {
            setTestingEmail(false);
        }
    };

    const loadLogs = async () => {
        try {
            const response = await fetch(route('admin.email.logs'));
            const data = await response.json();
            setEmailLogs(data.logs);
            setShowLogs(true);
        } catch (error) {
            console.error('Erreur lors du chargement des logs:', error);
        }
    };

    const getMailerIcon = (mailer) => {
        switch (mailer) {
            case 'mailpit': return <TestTube className="h-4 w-4" />;
            case 'smtp': return <Server className="h-4 w-4" />;
            case 'ses': return <Cloud className="h-4 w-4" />;
            default: return <Mail className="h-4 w-4" />;
        }
    };

    const getMailerColor = (mailer) => {
        switch (mailer) {
            case 'mailpit': return 'bg-blue-500';
            case 'smtp': return 'bg-green-500';
            case 'ses': return 'bg-orange-500';
            default: return 'bg-gray-500';
        }
    };

    return (
        <>
            <Head title="Paramètres Email" />

            <div className="space-y-6">
                <div>
                    <h2 className="text-3xl font-bold tracking-tight">Paramètres Email</h2>
                    <p className="text-muted-foreground">
                        Gérez la configuration d'envoi d'emails pour le développement et la production.
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

                <Tabs defaultValue="settings" className="space-y-4">
                    <TabsList>
                        <TabsTrigger value="settings">Configuration</TabsTrigger>
                        <TabsTrigger value="test">Test d'envoi</TabsTrigger>
                        <TabsTrigger value="logs">Logs</TabsTrigger>
                    </TabsList>

                    <TabsContent value="settings">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Settings className="h-5 w-5" />
                                    Configuration Email
                                </CardTitle>
                                <CardDescription>
                                    Choisissez le service d'envoi d'emails et configurez les paramètres.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div>
                                    <Label>Service d'envoi actuel</Label>
                                    <div className="flex items-center gap-3 mt-2">
                                        <Badge className={`${getMailerColor(currentMailer)} text-white`}>
                                            {getMailerIcon(currentMailer)}
                                            <span className="ml-1">{mailers[currentMailer]?.name}</span>
                                        </Badge>
                                        <span className="text-sm text-muted-foreground">
                                            {mailers[currentMailer]?.description}
                                        </span>
                                    </div>
                                </div>

                                <Separator />

                                <form onSubmit={updateSettings} className="space-y-6">
                                    <div>
                                        <Label>Choisir un service</Label>
                                        <RadioGroup value={form.data.mailer} onValueChange={handleMailerChange} className="mt-2">
                                            {Object.entries(mailers).map(([key, mailer]) => (
                                                <div key={key} className="flex items-center space-x-2">
                                                    <RadioGroupItem value={key} id={key} />
                                                    <Label htmlFor={key} className="flex items-center gap-2 cursor-pointer">
                                                        {getMailerIcon(key)}
                                                        <div>
                                                            <div className="font-medium">{mailer.name}</div>
                                                            <div className="text-sm text-muted-foreground">{mailer.description}</div>
                                                        </div>
                                                    </Label>
                                                </div>
                                            ))}
                                        </RadioGroup>
                                    </div>

                                    <Separator />

                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <Label htmlFor="from_address">Email de l'expéditeur</Label>
                                            <Input
                                                id="from_address"
                                                type="email"
                                                value={form.data.from_address}
                                                onChange={(e) => form.setData('from_address', e.target.value)}
                                                className="mt-1"
                                                placeholder="contact@nativescale.co"
                                            />
                                        </div>
                                        <div>
                                            <Label htmlFor="from_name">Nom de l'expéditeur</Label>
                                            <Input
                                                id="from_name"
                                                type="text"
                                                value={form.data.from_name}
                                                onChange={(e) => form.setData('from_name', e.target.value)}
                                                className="mt-1"
                                                placeholder="NativeMeta"
                                            />
                                        </div>
                                    </div>

                                    {form.data.mailer === 'smtp' && (
                                        <div className="space-y-4 border rounded-lg p-4">
                                            <h4 className="font-medium">Configuration SMTP</h4>
                                            <div className="grid grid-cols-2 gap-4">
                                                <div>
                                                    <Label htmlFor="smtp_host">Hôte SMTP</Label>
                                                    <Input
                                                        id="smtp_host"
                                                        type="text"
                                                        value={form.data.smtp_host}
                                                        onChange={(e) => form.setData('smtp_host', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="smtp.gmail.com"
                                                    />
                                                </div>
                                                <div>
                                                    <Label htmlFor="smtp_port">Port SMTP</Label>
                                                    <Input
                                                        id="smtp_port"
                                                        type="number"
                                                        value={form.data.smtp_port}
                                                        onChange={(e) => form.setData('smtp_port', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="587"
                                                    />
                                                </div>
                                            </div>
                                            <div className="grid grid-cols-2 gap-4">
                                                <div>
                                                    <Label htmlFor="smtp_username">Nom d'utilisateur</Label>
                                                    <Input
                                                        id="smtp_username"
                                                        type="text"
                                                        value={form.data.smtp_username}
                                                        onChange={(e) => form.setData('smtp_username', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="contact@nativescale.co"
                                                    />
                                                </div>
                                                <div>
                                                    <Label htmlFor="smtp_password">Mot de passe</Label>
                                                    <Input
                                                        id="smtp_password"
                                                        type="password"
                                                        value={form.data.smtp_password}
                                                        onChange={(e) => form.setData('smtp_password', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="••••••••••••••••"
                                                    />
                                                </div>
                                            </div>
                                            <div>
                                                <Label htmlFor="smtp_encryption">Chiffrement</Label>
                                                <Select value={form.data.smtp_encryption} onValueChange={(value) => form.setData('smtp_encryption', value)}>
                                                    <SelectTrigger className="mt-1">
                                                        <SelectValue placeholder="Choisir le chiffrement" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectItem value="tls">TLS</SelectItem>
                                                        <SelectItem value="ssl">SSL</SelectItem>
                                                    </SelectContent>
                                                </Select>
                                            </div>
                                        </div>
                                    )}

                                    {form.data.mailer === 'ses' && (
                                        <div className="space-y-4 border rounded-lg p-4">
                                            <h4 className="font-medium">Configuration Amazon SES</h4>
                                            <div className="grid grid-cols-2 gap-4">
                                                <div>
                                                    <Label htmlFor="ses_key">Clé d'accès AWS</Label>
                                                    <Input
                                                        id="ses_key"
                                                        type="text"
                                                        value={form.data.ses_key}
                                                        onChange={(e) => form.setData('ses_key', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="AKIAIOSFODNN7EXAMPLE"
                                                    />
                                                </div>
                                                <div>
                                                    <Label htmlFor="ses_region">Région AWS</Label>
                                                    <Input
                                                        id="ses_region"
                                                        type="text"
                                                        value={form.data.ses_region}
                                                        onChange={(e) => form.setData('ses_region', e.target.value)}
                                                        className="mt-1"
                                                        placeholder="eu-west-1"
                                                    />
                                                </div>
                                            </div>
                                            <div>
                                                <Label htmlFor="ses_secret">Clé secrète AWS</Label>
                                                <Input
                                                    id="ses_secret"
                                                    type="password"
                                                    value={form.data.ses_secret}
                                                    onChange={(e) => form.setData('ses_secret', e.target.value)}
                                                    className="mt-1"
                                                    placeholder="wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY"
                                                />
                                            </div>
                                        </div>
                                    )}

                                    <Button 
                                        type="submit" 
                                        disabled={form.processing}
                                        className="w-full"
                                    >
                                        {form.processing ? (
                                            <RefreshCw className="mr-2 h-4 w-4 animate-spin" />
                                        ) : null}
                                        Mettre à jour la configuration
                                    </Button>
                                </form>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="test">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Send className="h-5 w-5" />
                                    Test d'envoi d'email
                                </CardTitle>
                                <CardDescription>
                                    Envoyez un email de test pour vérifier que la configuration fonctionne.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <form onSubmit={sendTestEmail} className="space-y-4">
                                    <div>
                                        <Label htmlFor="test_email">Adresse email de test</Label>
                                        <Input
                                            id="test_email"
                                            type="email"
                                            value={testEmailForm.data.test_email}
                                            onChange={(e) => testEmailForm.setData('test_email', e.target.value)}
                                            className="mt-1"
                                            placeholder="test@example.com"
                                            required
                                        />
                                    </div>

                                    <Button 
                                        type="submit" 
                                        disabled={testingEmail || !testEmailForm.data.test_email}
                                        className="w-full"
                                    >
                                        {testingEmail ? (
                                            <RefreshCw className="mr-2 h-4 w-4 animate-spin" />
                                        ) : (
                                            <Send className="mr-2 h-4 w-4" />
                                        )}
                                        Envoyer un email de test
                                    </Button>
                                </form>

                                {testResult && (
                                    <Alert className={testResult.success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}>
                                        {testResult.success ? (
                                            <CheckCircle className="h-4 w-4 text-green-600" />
                                        ) : (
                                            <AlertCircle className="h-4 w-4 text-red-600" />
                                        )}
                                        <AlertDescription className={testResult.success ? 'text-green-800' : 'text-red-800'}>
                                            {testResult.message}
                                        </AlertDescription>
                                    </Alert>
                                )}

                                <Alert>
                                    <Mail className="h-4 w-4" />
                                    <AlertDescription>
                                        En développement avec Mailpit, vous pouvez consulter les emails envoyés sur 
                                        <a href="http://localhost:8025" target="_blank" rel="noopener noreferrer" className="text-blue-600 underline ml-1">
                                            http://localhost:8025
                                        </a>
                                    </AlertDescription>
                                </Alert>
                            </CardContent>
                        </Card>
                    </TabsContent>

                    <TabsContent value="logs">
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <FileText className="h-5 w-5" />
                                    Logs Email
                                </CardTitle>
                                <CardDescription>
                                    Consultez les logs d'envoi d'emails pour diagnostiquer les problèmes.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <Button onClick={loadLogs} variant="outline">
                                    <RefreshCw className="mr-2 h-4 w-4" />
                                    Charger les logs
                                </Button>

                                {showLogs && (
                                    <div className="border rounded-lg">
                                        <div className="bg-gray-50 px-4 py-2 border-b">
                                            <span className="text-sm font-medium">Logs récents ({emailLogs.length})</span>
                                        </div>
                                        <div className="max-h-96 overflow-y-auto">
                                            {emailLogs.length > 0 ? (
                                                <div className="space-y-1">
                                                    {emailLogs.map((log, index) => (
                                                        <div key={index} className="px-4 py-2 text-sm font-mono border-b last:border-b-0">
                                                            {log}
                                                        </div>
                                                    ))}
                                                </div>
                                            ) : (
                                                <div className="px-4 py-8 text-center text-muted-foreground">
                                                    Aucun log email trouvé
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                )}
                            </CardContent>
                        </Card>
                    </TabsContent>
                </Tabs>
            </div>
        </>
    );
}
