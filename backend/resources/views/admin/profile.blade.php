@extends('layouts.admin')

@section('title', 'Il Mio Profilo')
@section('page-title', 'Il Mio Profilo')
@section('page-description', 'Gestisci i tuoi dati personali e le impostazioni account')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Header -->
    <div class="card">
        <div class="flex items-center">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mr-6">
                <span class="text-3xl font-bold text-purple-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                <p class="text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                <div class="flex items-center mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Amministratore
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Informazioni Personali</h3>
            <button onclick="toggleEditMode()" id="editButton" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Modifica
            </button>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" id="profileForm">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome Completo
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', auth()->user()->name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                           disabled>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', auth()->user()->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('email') border-red-500 @enderror"
                           disabled>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions (hidden by default) -->
            <div id="formActions" class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200" style="display: none;">
                <button type="button" onclick="cancelEdit()" class="btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Annulla
                </button>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salva Modifiche
                </button>
            </div>
        </form>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">{{ \App\Models\User::role('site-owner')->count() }}</h4>
            <p class="text-sm text-gray-500">Site Owners Gestiti</p>
        </div>

        <div class="card text-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">{{ \App\Models\Site::count() }}</h4>
            <p class="text-sm text-gray-500">Siti Totali</p>
        </div>

        <div class="card text-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-900">{{ \App\Models\Analytics::count() }}</h4>
            <p class="text-sm text-gray-500">Interazioni Analytics</p>
        </div>
    </div>

    <!-- Account Info -->
    <div class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Account</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Account creato il</h4>
                <p class="text-gray-900">{{ auth()->user()->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Ultimo accesso</h4>
                <p class="text-gray-900">
                    {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y H:i') : 'Primo accesso' }}
                </p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Ruolo</h4>
                <p class="text-gray-900">{{ auth()->user()->getRoleNames()->first() }}</p>
            </div>
            <div>
                <h4 class="text-sm font-medium text-gray-500 mb-1">Stato Account</h4>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Attivo
                </span>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const editButton = document.getElementById('editButton');
    const formActions = document.getElementById('formActions');
    
    // Enable inputs
    nameInput.disabled = false;
    emailInput.disabled = false;
    
    // Show form actions
    formActions.style.display = 'flex';
    
    // Hide edit button
    editButton.style.display = 'none';
    
    // Focus on first input
    nameInput.focus();
}

function cancelEdit() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const editButton = document.getElementById('editButton');
    const formActions = document.getElementById('formActions');
    
    // Reset values
    nameInput.value = "{{ auth()->user()->name }}";
    emailInput.value = "{{ auth()->user()->email }}";
    
    // Disable inputs
    nameInput.disabled = true;
    emailInput.disabled = true;
    
    // Hide form actions
    formActions.style.display = 'none';
    
    // Show edit button
    editButton.style.display = 'inline-flex';
}
</script>
@endsection
