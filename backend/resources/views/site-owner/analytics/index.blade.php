@extends('layouts.site-owner')

@section('title', 'Analytics')
@section('page-title', 'Analytics')
@section('page-description', 'Analizza le performance dei tuoi siti')

@section('content')
<div class="space-y-8">
    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Messaggi Totali</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_messages']) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Oggi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['messages_today']) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Questa Settimana</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['messages_this_week']) }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Siti Attivi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sites'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sites Performance -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Performance per Sito</h3>
            <div class="text-sm text-gray-500">
                Messaggi ricevuti negli ultimi 7 giorni
            </div>
        </div>
        
        @if($siteStats->count() > 0)
            <div class="space-y-4">
                @foreach($siteStats as $siteStat)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $siteStat['site']->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $siteStat['site']->url }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500">Totali</p>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($siteStat['messages_count']) }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500">Settimana</p>
                                <p class="text-lg font-semibold text-green-600">{{ number_format($siteStat['messages_this_week']) }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('site-owner.analytics.site', $siteStat['site']) }}" 
                                   class="btn-sm bg-orange-100 text-orange-700 hover:bg-orange-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Dettagli
                                </a>
                                <a href="{{ route('site-owner.sites.show', $siteStat['site']) }}" 
                                   class="btn-sm bg-blue-100 text-blue-700 hover:bg-blue-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Gestisci
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun dato disponibile</h3>
                <p class="text-gray-500 mb-6">Non ci sono ancora messaggi sui tuoi siti.</p>
                <a href="{{ route('site-owner.sites.index') }}" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                    </svg>
                    Gestisci Siti
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Azioni Rapide</h3>
            <div class="space-y-3">
                <a href="{{ route('site-owner.sites.index') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Gestisci Siti</h4>
                        <p class="text-sm text-gray-500">Visualizza e modifica i tuoi siti</p>
                    </div>
                </a>
                
                @if(auth()->user()->sites()->count() < auth()->user()->max_number_sites)
                    <a href="{{ route('site-owner.sites.create') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Nuovo Sito</h4>
                            <p class="text-sm text-gray-500">Aggiungi un nuovo sito web</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistiche Rapide</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Messaggi questo mese</span>
                    <span class="font-semibold text-gray-900">{{ number_format($stats['messages_this_month']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Media giornaliera</span>
                    <span class="font-semibold text-gray-900">
                        {{ $stats['messages_this_month'] > 0 ? number_format($stats['messages_this_month'] / 30, 1) : '0' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Siti con messaggi</span>
                    <span class="font-semibold text-gray-900">
                        {{ $siteStats->where('messages_count', '>', 0)->count() }} / {{ $stats['total_sites'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
