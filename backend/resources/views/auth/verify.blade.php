@extends('layouts.app')

@section('title', 'Verifica OTP - RayoChat')

@section('content')
<style>
    .verify-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .verify-box {
        background: var(--color-white);
        border: 1px solid var(--color-gray-200);
        border-radius: 12px;
        padding: 3rem;
        width: 100%;
        max-width: 450px;
    }

    .verify-logo {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .verify-logo span {
        color: var(--color-orange);
    }

    .verify-subtitle {
        text-align: center;
        color: var(--color-gray-600);
        margin-bottom: 2rem;
    }

    .otp-input {
        text-align: center;
        font-size: 1.5rem;
        letter-spacing: 0.5rem;
        font-weight: 600;
    }
</style>

<div class="verify-container">
    <div class="verify-box">
        <div class="verify-logo">Rayo<span>Chat</span></div>
        <p class="verify-subtitle">Inserisci il codice a 6 cifre inviato a<br><strong>{{ $email }}</strong></p>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.verify-otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            
            <div class="form-group">
                <label class="form-label">Codice OTP</label>
                <input 
                    type="text" 
                    name="code" 
                    class="form-input otp-input" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Verifica e Accedi
            </button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('auth.login') }}" class="navbar-link">← Torna al login</a>
        </div>
    </div>
</div>
@endsection
