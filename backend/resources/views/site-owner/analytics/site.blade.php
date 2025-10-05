@extends('layouts.site-owner')

@section('title', 'Analytics - ' . $site->name)
@section('page-title', 'Analytics: ' . $site->name)
@section('page-description', 'Analisi dettagliata per ' . $site->url)

@section('content')
<div class="space-y-8">
    <!-- Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('site-owner.analytics.index') }}" class="flex items-center text-orange-600 hover:text-orange-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Torna alle Analytics
        </a>
        
        <div class="flex space-x-3">
            <a href="{{ route('site-owner.sites.show', $site) }}" class="btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Gestisci Sito
            </a>
        </div>
    </div>

    <!-- Site Info -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">{{ $site->name }}</h2>
                    <p class="text-gray-500">
                        <a href="{{ $site->url }}" target="_blank" class="text-orange-600 hover:text-orange-700">
                            {{ $site->url }}
                        </a>
                    </p>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-sm text-gray-500">Creato il</p>
                <p class="font-medium text-gray-900">{{ $site->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Classificati</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['classified_messages']) }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($stats['classification_rate'], 1) }}%</p>
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
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confidenza Media</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['avg_confidence'] * 100, 1) }}%</p>
                    @if($stats['top_category'])
                        <p class="text-xs text-gray-400">{{ $stats['top_category']['name'] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Timeline Chart -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Andamento Messaggi (30 giorni)</h3>
            </div>
            <div class="h-64">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution -->
        @if(count($categoryStats) > 0)
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Distribuzione Categorie</h3>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna Classificazione</h3>
                    <p class="text-gray-500">I messaggi non sono ancora stati classificati.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Hourly Distribution -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Distribuzione Oraria (Ultimi 7 giorni)</h3>
        </div>
        <div class="h-64">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>

    <!-- Category Stats & Keywords -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Category Statistics -->
        @if(count($categoryStats) > 0)
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Statistiche Categorie</h3>
                </div>
                <div class="space-y-4">
                    @foreach($categoryStats as $category => $data)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $category) }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $data['count'] }} messaggi ({{ $data['percentage'] }}%)
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($data['avg_confidence'] * 100, 1) }}%
                                </div>
                                <div class="text-xs text-gray-500">confidenza</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Top Keywords -->
        @if(count($topKeywords) > 0)
            <div class="card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Parole Chiave Frequenti</h3>
                </div>
                <div class="space-y-3">
                    @foreach($topKeywords as $keyword => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-900">{{ $keyword }}</span>
                            <div class="flex items-center">
                                <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ min(($count / max($topKeywords)) * 100, 100) }}%"></div>
                                </div>
                                <span class="text-sm text-gray-500 w-8 text-right">{{ $count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Recent Messages -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Messaggi Recenti</h3>
            <span class="text-sm text-gray-500">Ultimi {{ $recentMessages->count() }} messaggi</span>
        </div>
        
        @if($recentMessages->count() > 0)
            <div class="space-y-4">
                @foreach($recentMessages as $message)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-gray-900 mb-2">{{ Str::limit($message->message, 150) }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($message->category)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            {{ ucfirst(str_replace('_', ' ', $message->category)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($message->confidence)
                                <div class="ml-4 text-right">
                                    <div class="text-xs text-gray-500">Confidenza</div>
                                    <div class="text-sm font-medium {{ $message->confidence > 0.7 ? 'text-green-600' : ($message->confidence > 0.4 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($message->confidence * 100, 1) }}%
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun messaggio</h3>
                <p class="text-gray-500">Non ci sono ancora messaggi per questo sito.</p>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: @json($timelineChartData['labels']),
            datasets: [{
                label: 'Messaggi',
                data: @json($timelineChartData['data']),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
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
                    radius: 4,
                    hoverRadius: 6
                }
            }
        }
    });

    @if(count($categoryStats) > 0)
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
                borderWidth: 0
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
                        usePointStyle: true
                    }
                }
            }
        }
    });
    @endif

    // Hourly Distribution Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: @json($hourlyChartData['labels']),
            datasets: [{
                label: 'Messaggi per Ora',
                data: @json($hourlyChartData['data']),
                backgroundColor: 'rgba(249, 115, 22, 0.6)',
                borderColor: '#f97316',
                borderWidth: 1,
                borderRadius: 4
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
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection
