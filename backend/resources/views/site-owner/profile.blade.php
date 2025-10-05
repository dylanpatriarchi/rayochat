@extends('layouts.site-owner')

@section('title', 'Il Mio Profilo')
@section('page-title', 'Il Mio Profilo')
@section('page-description', 'Gestisci le informazioni del tuo account')

@section('content')
<div class="space-y-8">
    <!-- Profile Info Card -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Informazioni Account</h3>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm text-green-600 font-medium">Account Attivo</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nome Completo</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-lg text-gray-900">{{ $user->email }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Ruolo</label>
                    <p class="text-lg text-gray-900 capitalize">{{ $user->getRoleNames()->first() }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Limite Siti</label>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->max_number_sites == 999 ? 'Illimitati' : $user->max_number_sites }}
                    </p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Siti Creati</label>
                    <p class="text-lg text-gray-900">{{ $user->sites()->count() }}</p>
                </div>
                
                <div>
                    <label class="text-sm font-medium text-gray-500">Ultimo Accesso</label>
                    <p class="text-lg text-gray-900">
                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Mai' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Modifica Profilo</h3>
        </div>
        
        <form method="POST" action="{{ route('site-owner.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>

    <!-- Account Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Siti Totali</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $user->sites()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Messaggi Totali</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Analytics::whereIn('site_id', $user->sites()->pluck('id'))->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Account da</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ $user->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
