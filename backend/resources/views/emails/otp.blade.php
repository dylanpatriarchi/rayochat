@extends('emails.layout')

@section('title', 'Codice OTP per RayoChat')

@section('content')
    <div class="greeting">
        Ciao {{ $user->name }}! 🔐
    </div>

    <div class="message">
        <p>Hai richiesto l'accesso al tuo account RayoChat. Ecco il tuo codice di verifica:</p>
    </div>

    <div class="code-display">
        <div class="code-label">Il tuo codice OTP è:</div>
        <div class="code">{{ $otpCode }}</div>
        <div class="code-label">Valido per {{ $expiryMinutes }} minuti</div>
    </div>

    <div class="info-box">
        <h4>🛡️ Informazioni di sicurezza</h4>
        <p><strong>Questo codice scadrà tra {{ $expiryMinutes }} minuti.</strong></p>
        <p>Se non hai richiesto questo codice, ignora questa email. Il tuo account rimane sicuro.</p>
    </div>

    <div class="btn-container">
        <a href="{{ config('app.url') }}/verify-otp" class="btn">
            ✅ Verifica Codice
        </a>
    </div>

    <div class="message">
        <h4 style="color: #111827; font-size: 18px; font-weight: 600; margin-bottom: 15px;">📝 Come utilizzare il codice:</h4>
        <p><strong>1.</strong> Torna alla pagina di login di RayoChat</p>
        <p><strong>2.</strong> Inserisci il codice: <code style="background-color: #f3f4f6; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 16px; font-weight: bold; color: #ea580c;">{{ $otpCode }}</code></p>
        <p><strong>3.</strong> Clicca su "Verifica" per accedere</p>
    </div>

    <div class="message">
        <h4 style="color: #111827; font-size: 18px; font-weight: 600; margin-bottom: 15px;">⚠️ Importante:</h4>
        <p>• <strong>Non condividere</strong> questo codice con nessuno</p>
        <p>• <strong>Il codice scade</strong> automaticamente dopo {{ $expiryMinutes }} minuti</p>
        <p>• <strong>Usa il codice</strong> solo sul sito ufficiale RayoChat</p>
        <p>• <strong>Se hai problemi,</strong> richiedi un nuovo codice</p>
    </div>

    <div class="message" style="text-align: center; margin-top: 30px;">
        <p style="font-size: 16px; color: #6b7280;">Accesso richiesto per: <strong>{{ $user->email }}</strong></p>
        <p style="font-size: 14px; color: #9ca3af; margin-top: 10px;">{{ now()->format('d/m/Y H:i') }}</p>
    </div>
@endsection
