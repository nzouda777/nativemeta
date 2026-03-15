@component('mail::message')
# ✅ Ton achat est confirmé !

Merci pour ton achat de la formation **{{ $courseName }}**.

**Récapitulatif de ta commande :**

@component('mail::table')
| | |
|:---|:---|
| **Formation** | {{ $courseName }} |
| **Montant** | {{ $amount }} |
@endcomponent

Ta formation est désormais accessible depuis ton tableau de bord :

@component('mail::button', ['url' => $dashboardUrl, 'color' => 'primary'])
Accéder à ma formation
@endcomponent

Si tu as des questions ou besoin d'aide, n'hésite pas à nous contacter.

Bonne formation ! 💪

{{ config('app.name') }}
@endcomponent
