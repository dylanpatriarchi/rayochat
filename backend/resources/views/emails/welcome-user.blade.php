<x-mail::message>
# Benvenuto in RayoChat, {{ $user->name }}!

Ciao {{ $user->name }},

Il tuo account è stato creato con successo! Ora puoi accedere alla piattaforma RayoChat e iniziare a gestire i tuoi siti web.

## Come accedere

1. Vai alla pagina di login di RayoChat
2. Inserisci la tua email: **{{ $user->email }}**
3. Clicca su "Invia Codice OTP"
4. Controlla la tua email per il codice di verifica
5. Inserisci il codice ricevuto per accedere

<x-mail::button :url="config('app.url') . '/login'">
Accedi Ora
</x-mail::button>

## Cosa puoi fare

- **Creare siti**: Puoi creare fino a {{ $user->max_number_sites == 999 ? 'illimitati' : $user->max_number_sites }} siti
- **Gestire contenuti**: Aggiungi informazioni aziendali per ogni sito
- **Integrare il widget**: Usa l'API key per integrare RayoChat nei tuoi siti
- **Monitorare le conversazioni**: Visualizza le interazioni dei visitatori

## Hai bisogno di aiuto?

Se hai domande o problemi con il tuo account, non esitare a contattarci.

Grazie per aver scelto RayoChat!

Il Team RayoChat<br>
{{ config('app.name') }}
</x-mail::message>
