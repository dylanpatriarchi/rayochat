@extends('layouts.admin')

@section('title', 'Modifica Utente')
@section('page-title', 'Modifica Utente')
@section('page-description', 'Modifica i dati dell\'utente ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- User Info Header -->
                <div class="flex items-center pb-4 border-b border-gray-200">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-2xl font-bold text-orange-600">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $user->getRoleNames()->first() }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $user->sites->count() }} siti</span>
                        </div>
                    </div>
                </div>

                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
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
                           value="{{ old('email', $user->email) }}"
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
                        <option value="1" {{ old('max_number_sites', $user->max_number_sites) == 1 ? 'selected' : '' }}>1 sito</option>
                        <option value="3" {{ old('max_number_sites', $user->max_number_sites) == 3 ? 'selected' : '' }}>3 siti</option>
                        <option value="5" {{ old('max_number_sites', $user->max_number_sites) == 5 ? 'selected' : '' }}>5 siti</option>
                        <option value="10" {{ old('max_number_sites', $user->max_number_sites) == 10 ? 'selected' : '' }}>10 siti</option>
                        <option value="999" {{ old('max_number_sites', $user->max_number_sites) == 999 ? 'selected' : '' }}>Illimitati</option>
                    </select>
                    @error('max_number_sites')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Attualmente ha {{ $user->sites->count() }} siti creati
                    </p>
                </div>

                <!-- Account Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Informazioni Account</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Creato il:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Ultimo accesso:</span>
                            <span class="font-medium text-gray-900 ml-2">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Mai' }}
                            </span>
                        </div>
                    </div>
                </div>

                @if($user->hasRole('admin'))
                    <!-- Admin Warning -->
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-purple-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-purple-900 mb-1">Utente Amministratore</h4>
                                <p class="text-sm text-purple-700">
                                    Questo è un account amministratore. Fai attenzione quando modifichi i suoi dati.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
