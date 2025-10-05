@extends('layouts.admin')

@section('title', 'Analytics - ' . $site->name)
@section('page-title', 'Analytics - ' . $site->name)
@section('page-description', 'Analisi dettagliate per il sito ' . $site->name . ' (' . $site->user->name . ')')

@section('content')
<div class="space-y-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-600">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <a href="{{ route('admin.analytics.index') }}" class="hover:text-orange-600">Analytics</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 font-medium">{{ $site->name }}</span>
    </div>

    <!-- Site Info Card -->
    <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $site->name }}</h2>
                <p class="text-blue-100 mb-1">{{ $site->url }}</p>
                <p class="text-blue-100">Proprietario: {{ $site->user->name }} ({{ $site->user->email }})</p>
            </div>
            <div class="hidden md:block">
                <div class="text-right">
                    <p class="text-blue-100 text-sm">API Key</p>
                    <p class="font-mono text-sm bg-blue-700 px-3 py-1 rounded">{{ Str::mask($site->api_key, '*', 8, -8) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Messaggi Totali</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_messages']) }}</p>
                    <p class="text-xs text-gray-400">{{ $stats['messages_today'] }} oggi</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Questa Settimana</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['messages_this_week']) }}</p>
                    <p class="text-xs text-gray-400">messaggi</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Confidenza ML</p>
                    <p class="text-3xl font-bold text-gray-900">{{ round($stats['avg_confidence'] * 100, 1) }}%</p>
                    <p class="text-xs text-gray-400">Accuratezza</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Categoria Top</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['top_category']['name'] ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-400">{{ $stats['top_category']['count'] ?? 0 }} messaggi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Distribution -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Distribuzione Categorie</h3>
                <span class="text-sm text-gray-500">Solo questo sito</span>
            </div>
            @if(count($categoryChartData['data']) > 0)
                <div class="h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            @else
                <div class="h-80 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p>Nessun messaggio classificato</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Timeline -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Timeline Messaggi</h3>
                <span class="text-sm text-gray-500">Ultimi 30 giorni</span>
            </div>
            <div class="h-80">
                <canvas id="timelineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Hourly Distribution -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Distribuzione Oraria</h3>
            <span class="text-sm text-gray-500">Ultimi 7 giorni</span>
        </div>
        <div class="h-64">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Details -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Dettaglio Categorie</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    ML Attivo
                </span>
            </div>
            
            @if(count($categoryStats) > 0)
                <div class="space-y-4">
                    @foreach($categoryStats as $category => $data)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-900 capitalize">
                                    {{ str_replace('_', ' ', $category) }}
                                </h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $data['count'] }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Percentuale:</span>
                                    <span class="font-medium">{{ $data['percentage'] }}%</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Confidenza:</span>
                                    <span class="font-medium">{{ round($data['avg_confidence'] * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna Categoria</h3>
                    <p class="text-gray-500">Non ci sono ancora messaggi classificati per questo sito.</p>
                </div>
            @endif
        </div>

        <!-- Top Keywords -->
        <div class="card">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Keywords più Frequenti</h3>
                <span class="text-sm text-gray-500">Estratte dal ML</span>
            </div>
            
            @if(count($topKeywords) > 0)
                <div class="space-y-3">
                    @foreach($topKeywords as $keyword => $count)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-900">{{ $keyword }}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500">{{ $count }} volte</span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min(($count / max($topKeywords)) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Nessuna Keyword</h3>
                    <p class="text-gray-500">Non ci sono ancora keywords estratte per questo sito.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Messaggi Recenti</h3>
            <span class="text-sm text-gray-500">Con classificazione ML</span>
        </div>
        
        @if($recentMessages->count() > 0)
            <div class="space-y-4">
                @foreach($recentMessages as $message)
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($message->category === 'supporto_tecnico') bg-red-100 text-red-800
                                        @elseif($message->category === 'vendite_commerciale') bg-green-100 text-green-800
                                        @elseif($message->category === 'informazioni_prodotto') bg-blue-100 text-blue-800
                                        @elseif($message->category === 'feedback_recensioni') bg-purple-100 text-purple-800
                                        @elseif($message->category === 'lamentela_problema') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ str_replace('_', ' ', $message->category ?? 'non classificato') }}
                                    </span>
                                    <span class="ml-2 text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">{{ Str::limit($message->message, 150) }}</p>
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Confidenza: {{ round(($message->confidence ?? 0) * 100, 1) }}%</span>
                                    @if($message->classification_data && isset($message->classification_data['keywords']))
                                        <span>Keywords: {{ implode(', ', array_slice($message->classification_data['keywords'], 0, 4)) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun Messaggio</h3>
                <p class="text-gray-500">Non ci sono ancora messaggi per questo sito.</p>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(count($categoryChartData['data']) > 0)
    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryChartData);
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.labels,
            datasets: [{
                data: categoryData.data,
                backgroundColor: [
                    '#3b82f6', // blue
                    '#10b981', // green
                    '#f59e0b', // amber
                    '#8b5cf6', // purple
                    '#ef4444', // red
                    '#6b7280', // gray
                    '#f97316'  // orange
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
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

    // Timeline Chart
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    const timelineData = @json($timelineChartData);
    
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: timelineData.labels,
            datasets: [{
                label: 'Messaggi',
                data: timelineData.data,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 4
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
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });

    // Hourly Chart
    const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
    const hourlyData = @json($hourlyChartData);
    
    new Chart(hourlyCtx, {
        type: 'bar',
        data: {
            labels: hourlyData.labels,
            datasets: [{
                label: 'Messaggi per Ora',
                data: hourlyData.data,
                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                borderColor: '#3b82f6',
                borderWidth: 1
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
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
});
</script>
@endsection