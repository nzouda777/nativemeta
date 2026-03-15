@component('mail::message')
# 🎉 Félicitations !

Ton paiement a été confirmé pour la formation **{{ $courseName }}**.

Pour accéder à ta formation, crée ton compte NativeMeta en cliquant sur le bouton ci-dessous :

@component('mail::button', ['url' => $registerUrl, 'color' => 'primary'])
Créer mon compte et accéder à ma formation
@endcomponent

> **⚠️ Important :** Ce lien expire le **{{ $expiresAt }}**. Si tu as déjà un compte NativeMeta, connecte-toi directement et retrouve ta formation dans ton tableau de bord.

Si tu as des questions, n'hésite pas à nous contacter.

À très vite sur NativeMeta ! 🚀

{{ config('app.name') }}
@endcomponent
