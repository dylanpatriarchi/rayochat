@extends('layouts.admin')

@section('title', 'Modifica Sito')
@section('page-title', 'Modifica Sito')
@section('page-description', 'Modifica le informazioni del sito')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form method="POST" action="{{ route('admin.sites.update', $site) }}">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Header -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-900 mb-2">Modifica Sito</h2>
                    <p class="text-gray-500">Aggiorna le informazioni per {{ $site->name }}</p>
                    <p class="text-sm text-gray-400 mt-1">Proprietario: {{ $site->user->name }}</p>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Sito
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $site->name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                        placeholder="Es. Il mio sito web"
                        required
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL Field -->
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                        URL Sito
                    </label>
                    <input 
                        type="url" 
                        id="url" 
                        name="url" 
                        value="{{ old('url', $site->url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 @error('url') border-red-500 @enderror"
                        placeholder="Es. https://www.miosito.com"
                        required
                    >
                    @error('url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Info -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Informazioni Sito</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">ID:</span>
                            <span class="font-medium text-gray-900 ml-2">#{{ $site->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Proprietario:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $site->user->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Creato:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $site->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Ultimo Aggiornamento:</span>
                            <span class="font-medium text-gray-900 ml-2">{{ $site->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Attenzione</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Come amministratore puoi modificare solo il nome e l'URL del sito. L'API Key rimane invariata e non è visibile per motivi di sicurezza.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.sites.show', $site) }}" class="btn-secondary">
                        Annulla
                    </a>
                    <button type="submit" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salva Modifiche
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
