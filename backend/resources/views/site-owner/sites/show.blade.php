@extends('layouts.site-owner')

@section('title', 'Dettagli Sito')
@section('page-title', 'Dettagli Sito')
@section('page-description', 'Visualizza le informazioni del sito')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header with Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">{{ $site->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Dettagli del sito web</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('site-owner.sites.edit', $site) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifica
            </a>
            <a href="{{ route('site-owner.sites.edit-info', $site) }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Info Aziendali
            </a>
            <a href="{{ route('site-owner.sites.index') }}" class="btn-secondary">
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
                        <label class="text-sm font-medium text-gray-500">ID Sito</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">#{{ $site->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Creato il</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $site->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Site Business Information -->
    @if($site->siteInfoMD && $site->siteInfoMD->html_content)
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Informazioni Aziendali</h3>
            <a href="{{ route('site-owner.sites.edit-info', $site) }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                Modifica
            </a>
        </div>
        <div class="prose max-w-none">
            {!! $site->siteInfoMD->html_content !!}
        </div>
    </div>
    @else
    <div class="card border-gray-200 bg-gray-50">
        <div class="text-center py-8">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna informazione aziendale</h3>
            <p class="text-gray-500 mb-4">Aggiungi informazioni aziendali per il tuo sito</p>
            <a href="{{ route('site-owner.sites.edit-info', $site) }}" class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Aggiungi Informazioni
            </a>
        </div>
    </div>
    @endif

    <!-- API Key Card -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">API Key</h3>
                <p class="text-sm text-gray-500 mt-1">Usa questa chiave per integrare il sito con RayoChat</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="copyApiKey()" class="btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copia
                </button>
            </div>
        </div>
        
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <code id="api-key" class="text-sm font-mono text-gray-900 break-all">{{ $site->api_key }}</code>
            </div>
        </div>
        
        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Attenzione</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Conserva questa API Key in un posto sicuro. Non potrai rigenerarla e sarà necessaria per l'integrazione.</p>
                    </div>
                </div>
            </div>
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

    <!-- Danger Zone -->
    <div class="card border-red-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-medium text-red-900">Zona Pericolosa</h3>
                <p class="text-sm text-red-600 mt-1">Azioni irreversibili per questo sito</p>
            </div>
            <form method="POST" action="{{ route('site-owner.sites.destroy', $site) }}" onsubmit="return confirm('Sei sicuro di voler eliminare questo sito? Questa azione non può essere annullata e cancellerà anche l\'API Key.')">
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

<script>
function copyApiKey() {
    const apiKey = document.getElementById('api-key').textContent;
    navigator.clipboard.writeText(apiKey).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copiato!';
        button.classList.add('bg-green-500', 'hover:bg-green-600');
        button.classList.remove('bg-gray-100', 'hover:bg-gray-200');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500', 'hover:bg-green-600');
            button.classList.add('bg-gray-100', 'hover:bg-gray-200');
        }, 2000);
    });
}
</script>
@endsection
