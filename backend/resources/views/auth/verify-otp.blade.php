@extends('layouts.app')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px);">
    <div class="card" style="max-width: 400px; width: 100%; margin: 0 auto;">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verifica Codice</h1>
            <p class="text-gray">Inserisci il codice a 6 cifre inviato alla tua email</p>
            @if(session('email'))
                <p class="text-orange text-sm mt-2">{{ session('email') }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('otp.verify.submit') }}" id="otpForm">
            @csrf
            
            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
            <input type="hidden" name="otp_code" id="otpCodeInput">
            
            <div class="form-group">
                <div class="otp-container" style="display: flex; gap: 0.75rem; justify-content: center; margin-bottom: 1rem;">
                    <input type="text" class="otp-digit" maxlength="1" data-index="0" autocomplete="off">
                    <input type="text" class="otp-digit" maxlength="1" data-index="1" autocomplete="off">
                    <input type="text" class="otp-digit" maxlength="1" data-index="2" autocomplete="off">
                    <input type="text" class="otp-digit" maxlength="1" data-index="3" autocomplete="off">
                    <input type="text" class="otp-digit" maxlength="1" data-index="4" autocomplete="off">
                    <input type="text" class="otp-digit" maxlength="1" data-index="5" autocomplete="off">
                </div>
                @error('otp_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center mt-6">
                <p class="text-gray text-sm">
                    Non hai ricevuto il codice? 
                    <a href="{{ route('login') }}" class="text-orange hover:underline">Riprova</a>
                </p>
                <p class="text-gray text-xs mt-2">
                    Questo codice è valido per 10 minuti e solo per questa sessione del browser.
                </p>
            </div>
        </form>
    </div>
</div>

<style>
    .otp-digit {
        width: 3rem;
        height: 3rem;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 600;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        transition: all 0.2s ease;
        outline: none;
    }

    .otp-digit:focus {
        border-color: #ff6b35;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    }

    .otp-digit.filled {
        border-color: #ff6b35;
        background: #fff7ed;
    }

    .otp-digit.error {
        border-color: #e53e3e;
        background: #fed7d7;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpDigits = document.querySelectorAll('.otp-digit');
    const otpCodeInput = document.getElementById('otpCodeInput');
    const otpForm = document.getElementById('otpForm');
    
    // Focus sul primo input
    otpDigits[0].focus();
    
    otpDigits.forEach((digit, index) => {
        digit.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Solo numeri
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Rimuovi classe error se presente
            e.target.classList.remove('error');
            e.target.classList.add('filled');
            
            // Vai al prossimo input
            if (value && index < otpDigits.length - 1) {
                otpDigits[index + 1].focus();
            }
            
            // Controlla se abbiamo tutti i 6 numeri
            checkCompleteOTP();
        });
        
        digit.addEventListener('keydown', function(e) {
            // Backspace
            if (e.key === 'Backspace') {
                if (!e.target.value && index > 0) {
                    // Se l'input è vuoto, vai al precedente
                    otpDigits[index - 1].focus();
                } else {
                    // Rimuovi il valore corrente
                    e.target.value = '';
                    e.target.classList.remove('filled');
                }
                checkCompleteOTP();
            }
            
            // Arrow keys
            if (e.key === 'ArrowLeft' && index > 0) {
                otpDigits[index - 1].focus();
            }
            if (e.key === 'ArrowRight' && index < otpDigits.length - 1) {
                otpDigits[index + 1].focus();
            }
        });
        
        digit.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text');
            const numbers = pastedData.replace(/\D/g, '').slice(0, 6);
            
            numbers.split('').forEach((num, i) => {
                if (otpDigits[i]) {
                    otpDigits[i].value = num;
                    otpDigits[i].classList.add('filled');
                    otpDigits[i].classList.remove('error');
                }
            });
            
            // Focus sull'ultimo input riempito o sul primo vuoto
            const lastFilledIndex = Math.min(numbers.length - 1, otpDigits.length - 1);
            const nextIndex = Math.min(numbers.length, otpDigits.length - 1);
            otpDigits[nextIndex].focus();
            
            checkCompleteOTP();
        });
    });
    
    function checkCompleteOTP() {
        const otpValue = Array.from(otpDigits).map(digit => digit.value).join('');
        otpCodeInput.value = otpValue;
        
        if (otpValue.length === 6) {
            // Auto-submit dopo un breve delay
            setTimeout(() => {
                otpForm.submit();
            }, 300);
        }
    }
    
    // Gestione errori dal server
    @if($errors->has('otp_code'))
        otpDigits.forEach(digit => {
            digit.classList.add('error');
        });
    @endif
});
</script>
@endsection
