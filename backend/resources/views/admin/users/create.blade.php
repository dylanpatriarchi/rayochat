@extends('layouts.admin')

@section('title', 'Crea Nuovo Utente')
@section('page-title', 'Crea Nuovo Utente')
@section('page-description', 'Aggiungi un nuovo utente site-owner alla piattaforma')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror"
                           placeholder="Inserisci il nome completo"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror"
                           placeholder="utente@esempio.com"
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numero massimo siti -->
                <div>
                    <label for="max_number_sites" class="block text-sm font-medium text-gray-700 mb-2">
                        Numero Massimo Siti
                    </label>
                    <select id="max_number_sites" 
                            name="max_number_sites" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('max_number_sites') border-red-500 @enderror">
                        <option value="1" {{ old('max_number_sites', 3) == 1 ? 'selected' : '' }}>1 sito</option>
                        <option value="3" {{ old('max_number_sites', 3) == 3 ? 'selected' : '' }}>3 siti</option>
                        <option value="5" {{ old('max_number_sites', 3) == 5 ? 'selected' : '' }}>5 siti</option>
                        <option value="10" {{ old('max_number_sites', 3) == 10 ? 'selected' : '' }}>10 siti</option>
                        <option value="999" {{ old('max_number_sites', 3) == 999 ? 'selected' : '' }}>Illimitati</option>
                    </select>
                    @error('max_number_sites')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Numero massimo di siti che l'utente può creare
                    </p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-900 mb-1">Informazioni importanti</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• L'utente verrà creato automaticamente come <strong>site-owner</strong></li>
                                <li>• Riceverà un'email con le istruzioni per accedere</li>
                                <li>• Potrà accedere usando il sistema OTP con la sua email</li>
                                <li>• Non è possibile creare utenti amministratori da questa interfaccia</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Annulla
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Crea Utente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
