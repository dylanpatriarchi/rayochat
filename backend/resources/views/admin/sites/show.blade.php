@extends('layouts.admin')

@section('title', 'Dettagli Sito')
@section('page-title', 'Dettagli Sito')
@section('page-description', 'Visualizza le informazioni del sito')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $site->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Sito di {{ $site->user->name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.sites.edit', $site) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifica
            </a>
            <a href="{{ route('admin.sites.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Torna alla Lista
            </a>
        </div>
    </div>

    <!-- Site Information Card -->
    <div class="card">
        <div class="flex items-start space-x-6">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                    </svg>
                </div>
            </div>

            <!-- Site Details -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nome Sito</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">URL</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            <a href="{{ $site->url }}" target="_blank" class="text-orange-600 hover:text-orange-700">
                                {{ $site->url }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Proprietario</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $site->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID Sito</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">#{{ $site->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Creato il</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ultimo Aggiornamento</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Owner Information -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Proprietario</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Nome</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->user->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->user->email }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Limite Siti</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->user->max_number_sites }} siti</p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.site-owners.show', $site->user) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Visualizza Proprietario
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Stato</p>
                    <p class="text-lg font-semibold text-green-600">Attivo</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Giorni Attivo</p>
                    <p class="text-lg font-semibold text-gray-900">
                        @if($site->created_at->isToday())
                            Oggi
                        @elseif($site->created_at->isYesterday())
                            Ieri
                        @else
                            {{ $site->created_at->diffInDays(now()) }} {{ $site->created_at->diffInDays(now()) == 1 ? 'giorno' : 'giorni' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ultimo Aggiornamento</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $site->updated_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="card border-yellow-200 bg-yellow-50">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Informazioni API Key</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>L'API Key di questo sito è visibile solo al proprietario per motivi di sicurezza. Come amministratore puoi modificare nome e URL del sito, ma non puoi visualizzare o modificare l'API Key.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-red-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-red-900">Zona Pericolosa</h3>
                <p class="text-sm text-red-600 mt-1">Azioni irreversibili per questo sito</p>
            </div>
            <form method="POST" action="{{ route('admin.sites.destroy', $site) }}" onsubmit="return confirm('Sei sicuro di voler eliminare questo sito? Questa azione non può essere annullata e cancellerà anche l\'API Key.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Elimina Sito
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
