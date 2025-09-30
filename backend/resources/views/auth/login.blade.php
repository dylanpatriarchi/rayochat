@extends('layouts.app')

@section('title', 'Login - RayoChat')

@section('content')
<style>
    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .login-box {
        background: var(--color-white);
        border: 1px solid var(--color-gray-200);
        border-radius: 12px;
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .login-logo {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-logo span {
        color: var(--color-orange);
    }

    .login-subtitle {
        text-align: center;
        color: var(--color-gray-600);
        margin-bottom: 2rem;
    }
</style>

<div class="login-container">
    <div class="login-box">
        <div class="login-logo">Rayo<span>Chat</span></div>
        <p class="login-subtitle">Accedi con il tuo indirizzo email</p>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('auth.send-otp') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="il-tuo-email@esempio.it"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">
                Invia Codice OTP
            </button>
        </form>
    </div>
</div>
@endsection
