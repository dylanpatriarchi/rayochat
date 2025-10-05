@extends('emails.layout')

@section('title', 'Benvenuto in RayoChat')

@section('content')
    <div class="greeting">
        Benvenuto in RayoChat, {{ $user->name }}! 🎉
    </div>

    <div class="message">
        <p>Ciao <strong>{{ $user->name }}</strong>,</p>
        
        <p>Benvenuto nella famiglia RayoChat! Il tuo account è stato creato con successo e sei pronto per iniziare a trasformare la comunicazione sui tuoi siti web.</p>
    </div>

    <div class="info-box">
        <h4>📋 Dettagli del tuo account</h4>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Numero massimo di siti:</strong> {{ $user->max_number_sites == 999 ? 'Illimitati' : $user->max_number_sites }}</p>
    </div>

    <div class="btn-container">
        <a href="{{ config('app.url') }}/login" class="btn">
            🚀 Accedi alla Dashboard
        </a>
    </div>

    <div class="message">
        <h4 style="color: #111827; font-size: 18px; font-weight: 600; margin-bottom: 15px;">🔐 Come accedere:</h4>
        <p><strong>1.</strong> Clicca sul pulsante sopra o vai a: <a href="{{ config('app.url') }}/login" style="color: #f97316;">{{ config('app.url') }}/login</a></p>
        <p><strong>2.</strong> Inserisci la tua email: <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-family: monospace;">{{ $user->email }}</code></p>
        <p><strong>3.</strong> Clicca su "Invia Codice OTP"</p>
        <p><strong>4.</strong> Controlla la tua email per il codice di verifica</p>
        <p><strong>5.</strong> Inserisci il codice ricevuto per accedere</p>
    </div>

    <div class="message">
        <h4 style="color: #111827; font-size: 18px; font-weight: 600; margin-bottom: 15px;">✨ Cosa puoi fare:</h4>
        <p>• <strong>Creare siti:</strong> Puoi creare fino a {{ $user->max_number_sites == 999 ? 'illimitati' : $user->max_number_sites }} siti</p>
        <p>• <strong>Gestire contenuti:</strong> Aggiungi informazioni aziendali per ogni sito</p>
        <p>• <strong>Integrare il widget:</strong> Usa l'API key per integrare RayoChat nei tuoi siti</p>
        <p>• <strong>Monitorare conversazioni:</strong> Visualizza le interazioni dei visitatori</p>
        <p>• <strong>Analizzare i dati:</strong> Accedi a statistiche e insights avanzati</p>
    </div>

    <div class="info-box">
        <h4>💡 Hai bisogno di aiuto?</h4>
        <p>Il nostro team di supporto è sempre disponibile per assisterti. Non esitare a contattarci per qualsiasi domanda!</p>
    </div>

    <div class="message" style="text-align: center; margin-top: 30px;">
        <p style="font-size: 18px; font-weight: 600; color: #f97316;">Grazie per aver scelto RayoChat! 🙏</p>
        <p style="font-style: italic; color: #6b7280;">Il Team RayoChat</p>
    </div>
@endsection