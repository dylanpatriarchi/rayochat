@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="card max-w-md w-full mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Verify OTP</h1>
            <p class="text-gray">Enter the 6-digit code sent to your email</p>
            @if(session('email'))
                <p class="text-orange text-sm mt-2">{{ session('email') }}</p>
            @endif
        </div>

        <form method="POST" action="{{ route('otp.verify.submit') }}">
            @csrf
            
            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">
            
            <div class="form-group">
                <label for="otp_code" class="form-label">OTP Code</label>
                <input 
                    type="text" 
                    id="otp_code" 
                    name="otp_code" 
                    class="form-input @error('otp_code') border-red-500 @enderror text-center text-2xl tracking-widest" 
                    value="{{ old('otp_code') }}" 
                    placeholder="000000"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    required 
                    autofocus
                >
                @error('otp_code')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Verify & Login
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray text-sm">
                Didn't receive the code? 
                <a href="{{ route('login') }}" class="text-orange hover:underline">Try again</a>
            </p>
            <p class="text-gray text-xs mt-2">
                This OTP is valid for 10 minutes and only for this browser session.
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-focus and format OTP input
    document.getElementById('otp_code').addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limit to 6 digits
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
    });
</script>
@endsection
