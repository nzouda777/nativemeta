<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue sur NativeMeta</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #0A0A0B; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table td { padding: 10px; border: 1px solid #ddd; }
        .table td:first-child { font-weight: bold; background: #f0f0f0; }
        .button { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Bienvenue sur NativeMeta !</h1>
        </div>
        
        <div class="content">
            <p>Félicitations <strong>{{ $user->name }}</strong> ! Votre compte a été créé automatiquement suite à votre achat.</p>
            <!-- details de la formation -->
             
            <h2>🔑 Vos identifiants de connexion</h2>
            <table class="table">
                <tr>
                    <td>Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <td>Mot de passe</td>
                    <td><code>{{ $password }}</code></td>
                </tr>
                <tr>
                    <td>Lien de connexion</td>
                    <td><a href="{{ $loginUrl }}">Se connecter</a></td>
                </tr>
            </table>
            
            <h2>🔐 Sécurité</h2>
            <p>⚠️ <strong>Important</strong> :</p>
            <ul>
                <li>Changez votre mot de passe lors de votre première connexion</li>
                <li>Gardez vos identifiants confidentiels</li>
                <li>Ne partagez jamais votre mot de passe</li>
            </ul>
            
            <h2>📞 Besoin d'aide ?</h2>
            <p>Si vous avez des questions ou rencontrez des difficultés :</p>
            <ul>
                <li>Consultez notre centre d'aide</li>
                <li>Contactez notre support par email</li>
            </ul>
            
            <p><em>Bienvenue dans la communauté NativeMeta !</em> 🚀</p>
            
            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="button">Se connecter maintenant</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas répondre.</p>
        </div>
    </div>
</body>
</html>
