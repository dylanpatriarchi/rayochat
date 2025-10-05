@extends('layouts.admin')

@section('title', 'Gestione Utenti')
@section('page-title', 'Gestione Utenti')
@section('page-description', 'Visualizza e gestisci tutti gli utenti della piattaforma')

@section('content')
<div class="space-y-6">
    <!-- Header with Create User Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Tutti gli Utenti</h2>
            <p class="text-gray-600 mt-1">{{ $users->count() }} utenti totali</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Crea Utente
        </a>
    </div>

    @if($users->count() > 0)
        <!-- Users Accordion -->
        <div class="space-y-4">
            @foreach($users as $user)
                <div class="accordion-item">
                    <!-- Accordion Header -->
                    <div class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-lg font-semibold text-orange-600">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $user->email }}</span>
                                    <span>•</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $user->getRoleNames()->first() }}
                                    </span>
                                    <span>•</span>
                                    <span>{{ $user->sites->count() }} {{ $user->sites->count() === 1 ? 'sito' : 'siti' }}</span>
                                </div>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 accordion-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <!-- Accordion Content -->
                    <div class="accordion-content">
                        @if($user->sites->count() > 0)
                            <!-- Sites Section -->
                            <div class="mb-6">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Siti dell'utente</h4>
                                <div class="space-y-3">
                                    @foreach($user->sites as $site)
                                        <div class="site-card">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-gray-900">{{ $site->name }}</h5>
                                                    <p class="text-sm text-gray-500 mt-1">{{ $site->url }}</p>
                                                    <div class="flex items-center mt-2 text-xs text-gray-400">
                                                        <span>API Key: {{ substr($site->api_key, 0, 20) }}...</span>
                                                        <span class="ml-4">Creato: {{ $site->created_at->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="site-actions ml-4">
                                                    <a href="{{ route('admin.sites.show', $site) }}" class="btn-secondary btn-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        Visualizza
                                                    </a>
                                                    <a href="{{ route('admin.analytics.site', $site) }}" class="btn-secondary btn-sm" style="background-color: #f3e8ff; color: #7c3aed; border-color: #e9d5ff;">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                        </svg>
                                                        Analytics
                                                    </a>
                                                    <a href="{{ route('admin.sites.edit', $site) }}" class="btn-primary btn-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Modifica
                                                    </a>
                                                    <a href="{{ route('admin.sites.edit-info', $site) }}" class="btn-secondary btn-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Dati Aziendali
                                                    </a>
                                                    <a href="{{ route('admin.analytics.site', $site) }}" class="btn-secondary btn-sm">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                        </svg>
                                                        Analytics
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.sites.destroy', $site) }}" class="inline" 
                                                          onsubmit="return confirm('Sei sicuro di voler eliminare questo sito?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-danger btn-sm">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            Elimina
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-6 text-center py-4 bg-gray-50 rounded-lg">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Nessun sito creato</p>
                            </div>
                        @endif

                        @if(!$user->hasRole('admin'))
                            <!-- User Actions Section -->
                            <div class="border-t pt-4">
                                <h4 class="text-md font-semibold text-gray-900 mb-3">Azioni Utente</h4>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary btn-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifica Dati Utente
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" 
                                          onsubmit="return confirm('Sei sicuro di voler eliminare questo utente? Tutti i suoi siti verranno eliminati.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Elimina Utente
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="border-t pt-4">
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span class="text-sm text-purple-700 font-medium">Utente Amministratore - Azioni limitate per sicurezza</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun Utente</h3>
            <p class="text-gray-500 mb-6">Non ci sono ancora utenti registrati nella piattaforma.</p>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Crea il primo utente
            </a>
        </div>
    @endif
</div>
@endsection
