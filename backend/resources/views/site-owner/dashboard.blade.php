@extends('layouts.site-owner')

@section('title', 'Dashboard')
@section('page-title', 'Benvenuto, ' . auth()->user()->name . '!')
@section('page-description', 'Panoramica completa dei tuoi siti e performance')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Ciao {{ auth()->user()->name }}! 👋</h2>
                <p class="text-orange-100 mb-4">Ecco un riepilogo delle tue attività oggi</p>
                <div class="flex items-center space-x-6 text-sm">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        {{ number_format($stats['messages_today']) }} messaggi oggi
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                        </svg>
                        {{ $stats['total_sites'] }} siti attivi
                    </div>
                    @if($stats['top_category'])
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Top: {{ $stats['top_category']['name'] }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Messages -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Messaggi Totali</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_messages']) }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($stats['messages_this_week']) }} questa settimana</p>
                </div>
            </div>
        </div>

        <!-- Sites -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Siti Attivi</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_sites'] }}</p>
                    <p class="text-xs text-gray-400">{{ $stats['remaining_sites'] }} disponibili</p>
                </div>
            </div>
        </div>

        <!-- Classification Rate -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Classificazione</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['classification_rate'], 1) }}%</p>
                    <p class="text-xs text-gray-400">{{ number_format($stats['classified_messages']) }} classificati</p>
                </div>
            </div>
        </div>

        <!-- Confidence -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confidenza Media</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['avg_confidence'] * 100, 1) }}%</p>
                    <p class="text-xs text-gray-400">Accuratezza AI</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Weekly Activity -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Attività Settimanale</h3>
                <a href="{{ route('site-owner.analytics.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Vedi tutto →
                </a>
            </div>
            <div class="h-64">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution -->
        @if(count($categoryChartData['labels']) > 0)
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Categorie Principali</h3>
                    <span class="text-sm text-gray-500">Top 5</span>
                </div>
                <div class="h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        @else
            <div class="card">
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun Dato</h3>
                    <p class="text-gray-500">Non ci sono ancora messaggi classificati.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Azioni Rapide</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @if($stats['remaining_sites'] > 0)
                <a href="{{ route('site-owner.sites.create') }}" class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors duration-200 group">
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-orange-600 transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Nuovo Sito</h4>
                        <p class="text-sm text-gray-500">Aggiungi un sito web</p>
                    </div>
                </a>
            @endif

            <a href="{{ route('site-owner.sites.index') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 group">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-blue-600 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Gestisci Siti</h4>
                    <p class="text-sm text-gray-500">Visualizza tutti i siti</p>
                </div>
            </a>

            <a href="{{ route('site-owner.analytics.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group">
                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-green-600 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Analytics</h4>
                    <p class="text-sm text-gray-500">Visualizza statistiche</p>
                </div>
            </a>

            <a href="{{ route('site-owner.profile') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-200 group">
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-4 group-hover:bg-purple-600 transition-colors">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Profilo</h4>
                    <p class="text-sm text-gray-500">Gestisci account</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Sites Performance -->
    @if($siteStats->count() > 0)
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Performance Siti</h3>
                <a href="{{ route('site-owner.sites.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Gestisci tutti →
                </a>
            </div>
            
            <div class="space-y-4">
                @foreach($siteStats->take(5) as $siteStat)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $siteStat['site']->name }}</h4>
                                <p class="text-sm text-gray-500">{{ Str::limit($siteStat['site']->url, 40) }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500">Oggi</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $siteStat['messages_today'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500">Settimana</p>
                                <p class="text-lg font-semibold text-green-600">{{ $siteStat['messages_this_week'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-medium text-gray-500">Totale</p>
                                <p class="text-lg font-semibold text-blue-600">{{ $siteStat['messages_count'] }}</p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('site-owner.analytics.site', $siteStat['site']) }}" 
                                   class="btn-sm bg-orange-100 text-orange-700 hover:bg-orange-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Analytics
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
        </div>
    @else
        <!-- Empty State -->
        <div class="card">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun sito trovato</h3>
                <p class="text-gray-500 mb-6">Non hai ancora creato nessun sito web. Inizia creando il tuo primo sito!</p>
                @if($stats['remaining_sites'] > 0)
                    <a href="{{ route('site-owner.sites.create') }}" class="btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crea il primo sito
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Activity Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: @json($weeklyChartData['labels']),
            datasets: [{
                label: 'Messaggi',
                data: @json($weeklyChartData['data']),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f97316',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });

    @if(count($categoryChartData['labels']) > 0)
    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryChartData['labels']),
            datasets: [{
                data: @json($categoryChartData['data']),
                backgroundColor: [
                    '#f97316', '#3b82f6', '#10b981', '#8b5cf6', 
                    '#f59e0b', '#ef4444', '#06b6d4', '#84cc16'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection