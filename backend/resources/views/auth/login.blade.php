@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="card max-w-md w-full mx-auto">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray">Enter your email to receive an OTP code</p>
            @if(session('success'))
                <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('otp.send') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input @error('email') border-red-500 @enderror" 
                    value="{{ old('email') }}" 
                    placeholder="Enter your email address"
                    required 
                    autofocus
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Send OTP Code
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-gray text-sm">
                We'll send you a 6-digit code to verify your identity
            </p>
        </div>
    </div>
</div>
@endsection
