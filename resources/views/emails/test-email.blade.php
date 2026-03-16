@component('mail::message')
# 🧪 Email de test réussi !

Ceci est un email de test envoyé depuis **{{ $appName }}**.

**Informations de test :**

@component('mail::table')
| | |
|:---|:---|
| **Application** | {{ $appName }} |
| **Date/Heure** | {{ $testTime }} |
| **Mailer utilisé** | {{ $mailer }} |
| **Environnement** | {{ config('app.env') }} |
@endcomponent

Si vous recevez cet email, cela signifie que la configuration email fonctionne correctement ! 🎉

---

*Email envoyé automatiquement pour tester la configuration.*
@endcomponent
