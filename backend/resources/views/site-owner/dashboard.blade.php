@extends('layouts.app')

@section('title', 'Dashboard - RayoChat')

@section('content')
<div class="container-wide">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Benvenuto, {{ session('user_name') }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Conversazioni Totali</div>
            <div class="stat-value">{{ number_format($totalConversations) }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Conversazioni Oggi</div>
            <div class="stat-value highlight">{{ number_format($conversationsToday) }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Valutazione Media</div>
            <div class="stat-value">{{ $averageRating ? number_format($averageRating, 1) . '⭐' : 'N/A' }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Documenti Caricati</div>
            <div class="stat-value">{{ $totalDocuments }}</div>
        </div>
    </div>

    @if($pendingChangeRequests > 0)
    <div class="alert alert-error">
        Hai <strong>{{ $pendingChangeRequests }}</strong> richieste di modifica in attesa di approvazione.
        <a href="{{ route('site-owner.change-requests') }}" style="text-decoration: underline; font-weight: 600;">Vedi richieste</a>
    </div>
    @endif

    <div class="card">
        <div class="card-header">Informazioni Azienda</div>
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong>Nome:</strong> {{ $company->name }}
            </div>
            @if($company->website)
            <div>
                <strong>Sito Web:</strong> <a href="{{ $company->website }}" target="_blank" style="color: var(--color-orange);">{{ $company->website }}</a>
            </div>
            @endif
            @if($company->email)
            <div>
                <strong>Email:</strong> {{ $company->email }}
            </div>
            @endif
            <div>
                <a href="{{ route('site-owner.company-info') }}" class="btn btn-secondary">Gestisci Informazioni</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Azioni Rapide</div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('site-owner.documents') }}" class="btn btn-primary">Carica Documenti</a>
            <a href="{{ route('site-owner.analytics') }}" class="btn btn-secondary">Vedi Analitiche</a>
            <a href="{{ route('site-owner.api-key') }}" class="btn btn-secondary">API Key Widget</a>
            <a href="{{ route('site-owner.download-plugin') }}" class="btn btn-ghost">Scarica Plugin WordPress</a>
        </div>
    </div>
</div>
@endsection
