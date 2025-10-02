@extends('layouts.admin')

@section('title', 'Nuovo Site Owner')
@section('page-title', 'Nuovo Site Owner')
@section('page-description', 'Crea un nuovo utente site-owner')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form method="POST" action="{{ route('admin.site-owners.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Header -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Crea Nuovo Site Owner</h2>
                    <p class="text-gray-500">Inserisci le informazioni per il nuovo utente site-owner</p>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Es. Mario Rossi"
                        required
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Indirizzo Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                        placeholder="Es. mario.rossi@example.com"
                        required
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Number Sites Field -->
                <div>
                    <label for="max_number_sites" class="block text-sm font-medium text-gray-700 mb-2">
                        Numero Massimo di Siti
                    </label>
                    <select 
                        id="max_number_sites" 
                        name="max_number_sites" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('max_number_sites') border-red-500 @enderror"
                        required
                    >
                        <option value="">Seleziona il numero massimo di siti</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('max_number_sites') == $i ? 'selected' : '' }}>
                                {{ $i }} {{ $i == 1 ? 'sito' : 'siti' }}
                            </option>
                        @endfor
                    </select>
                    @error('max_number_sites')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informazioni Importanti</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>L'utente riceverà automaticamente il ruolo "site-owner"</li>
                                    <li>Un'email di benvenuto verrà inviata all'indirizzo specificato</li>
                                    <li>L'utente potrà accedere alla piattaforma dopo la verifica OTP</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.site-owners.index') }}" class="btn-secondary">
                        Annulla
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crea Site Owner
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
