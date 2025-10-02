@extends('layouts.admin')

@section('title', 'Dettagli Site Owner')
@section('page-title', 'Dettagli Site Owner')
@section('page-description', 'Visualizza le informazioni dettagliate del site owner')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $siteOwner->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Dettagli utente site-owner</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.site-owners.edit', $siteOwner) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifica
            </a>
            <a href="{{ route('admin.site-owners.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Torna alla Lista
            </a>
        </div>
    </div>

    <!-- User Information Card -->
    <div class="card">
        <div class="flex items-start space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-orange-600">
                        {{ substr($siteOwner->name, 0, 1) }}
                    </span>
                </div>
            </div>

            <!-- User Details -->
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nome Completo</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $siteOwner->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $siteOwner->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID Utente</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">#{{ $siteOwner->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ruolo</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Site Owner
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Registrato il</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $siteOwner->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ultimo Accesso</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">
                            @if($siteOwner->last_login_at)
                                {{ $siteOwner->last_login_at->format('d/m/Y H:i') }}
                            @else
                                <span class="text-gray-400">Mai</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Max Siti</label>
                        <p class="text-lg font-semibold text-orange-600 mt-1">{{ $siteOwner->max_number_sites }} {{ $siteOwner->max_number_sites == 1 ? 'sito' : 'siti' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Stats -->
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
                    <p class="text-sm font-medium text-gray-500">Stato Account</p>
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
                    <p class="text-lg font-semibold text-gray-900">{{ $siteOwner->created_at->diffInDays(now()) }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Permessi</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $siteOwner->permissions->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-red-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-red-900">Zona Pericolosa</h3>
                <p class="text-sm text-red-600 mt-1">Azioni irreversibili per questo utente</p>
            </div>
            <form method="POST" action="{{ route('admin.site-owners.destroy', $siteOwner) }}" onsubmit="return confirm('Sei sicuro di voler eliminare questo site owner? Questa azione non può essere annullata.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Elimina Site Owner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
