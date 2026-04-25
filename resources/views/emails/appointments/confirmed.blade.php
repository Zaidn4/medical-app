<x-mail::message>
# Bonjour {{ $appointment->patient->name }},

Votre rendez-vous a été planifié avec succès au sein de notre cabinet.

**Détails de votre consultation :**
- **Médecin :** {{ $appointment->doctor->name }}
- **Service :** {{ $appointment->service->name }}
- **Date :** {{ $appointment->appointment_date->format('d/m/Y à H:i') }}

Si vous avez des questions ou souhaitez annuler, merci de nous contacter.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>