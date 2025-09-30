@extends('layouts.app')

@section('title', 'Admin Dashboard - RayoChat')

@section('content')
<div class="container-wide">
    <div class="page-header">
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">Panoramica generale della piattaforma</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Site Owners Totali</div>
            <div class="stat-value highlight">{{ $totalSiteOwners }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Aziende Attive</div>
            <div class="stat-value">{{ $activeCompanies }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-label">Conversazioni Totali</div>
            <div class="stat-value">{{ number_format($totalConversations) }}</div>
        </div>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <a href="{{ route('admin.site-owners') }}" class="btn btn-primary">Gestisci Site Owners</a>
        <a href="{{ route('admin.create-site-owner') }}" class="btn btn-secondary">Crea Nuovo Site Owner</a>
    </div>

    <div class="card">
        <div class="card-header">Conversazioni Recenti</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Azienda</th>
                    <th>Domanda</th>
                    <th>Valutazione</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentConversations as $conv)
                <tr>
                    <td><strong>{{ $conv->company->name }}</strong></td>
                    <td>{{ \Str::limit($conv->question, 60) }}</td>
                    <td>
                        @if($conv->rating)
                            <span class="badge badge-success">{{ $conv->rating }}⭐</span>
                        @else
                            <span class="badge badge-gray">Non valutata</span>
                        @endif
                    </td>
                    <td>{{ $conv->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--color-gray-600);">
                        Nessuna conversazione ancora
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
