@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px);">
    <div class="card" style="max-width: 400px; width: 100%; margin: 0 auto;">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Benvenuto</h1>
            <p class="text-gray">Inserisci la tua email per ricevere il codice OTP</p>
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('otp.send') }}">
            @csrf
            
            <div class="form-group">
                <div class="relative">
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input pr-12 @error('email') border-red-500 @enderror" 
                        value="{{ old('email') }}" 
                        placeholder="Inserisci la tua email"
                        required 
                        autofocus
                        style="padding-right: 3rem;"
                    >
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-orange-500 hover:text-orange-600 transition-colors" style="background: none; border: none; cursor: pointer; padding: 0;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray text-sm">
                Ti invieremo un codice a 6 cifre per verificare la tua identità
            </p>
        </div>
    </div>
</div>
@endsection
