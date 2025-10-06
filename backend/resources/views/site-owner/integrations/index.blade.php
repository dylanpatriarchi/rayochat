@extends('layouts.site-owner')

@section('page-title', 'Integrazioni')
@section('page-description', 'Scarica e installa i plugin per integrare RayoChat nel tuo sito')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-900">Integrazioni RayoChat</h1>
                <p class="text-gray-600">Integra facilmente RayoChat nel tuo sito web con i nostri plugin ufficiali</p>
            </div>
        </div>
    </div>

    <!-- Integration Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- WordPress Plugin -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <!-- WordPress Header -->
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border border-gray-200 shadow-sm">
                        <!-- WordPress Official Icon -->
                        <img src="{{ asset('assets/icons/wordpress.svg') }}" alt="WordPress" class="w-10 h-10">
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">Plugin WordPress</h2>
                        <p class="text-sm text-gray-600">Widget di chat AI per siti WordPress</p>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Caratteristiche:</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Design moderno stile iMessage
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Completamente responsive
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Pannello di configurazione integrato
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Sicurezza e validazione avanzata
                        </li>
                    </ul>
                </div>

                <!-- Download Button -->
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('site-owner.integrations.wordpress.download') }}" 
                       class="btn-primary text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Scarica Plugin WordPress
                    </a>
                    <button type="button" class="btn-secondary text-center" onclick="toggleWordPressInstructions()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Istruzioni di Installazione
                    </button>
                </div>
            </div>

            <!-- WordPress Instructions (Hidden by default) -->
            <div id="wordpress-instructions" class="border-t border-gray-200 bg-gray-50 p-6 hidden">
                <h4 class="font-semibold text-gray-900 mb-3">Istruzioni di Installazione:</h4>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Scarica il file ZIP del plugin cliccando il pulsante sopra</li>
                    <li>Accedi al tuo pannello di amministrazione WordPress</li>
                    <li>Vai su <strong>Plugin → Aggiungi nuovo</strong></li>
                    <li>Clicca su <strong>"Carica plugin"</strong> in alto</li>
                    <li>Seleziona il file ZIP scaricato e clicca <strong>"Installa ora"</strong></li>
                    <li>Attiva il plugin dopo l'installazione</li>
                    <li>Vai su <strong>Impostazioni → RayoChat Widget</strong></li>
                    <li>Inserisci la tua API Key (formato: <code>rc_s_...</code>)</li>
                    <li>Personalizza il widget secondo le tue preferenze</li>
                    <li>Abilita il widget e salva le impostazioni</li>
                </ol>
                
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-700">
                            <strong>Nota:</strong> Avrai bisogno della tua API Key per configurare il plugin. 
                            Puoi trovarla nella sezione "Siti" di questo pannello.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shopify App -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <!-- Shopify Header -->
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center border border-gray-200 shadow-sm">
                        <!-- Shopify Official Icon -->
                        <img src="{{ asset('assets/icons/shopify.svg') }}" alt="Shopify" class="w-10 h-10">
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-900">App Shopify</h2>
                        <p class="text-sm text-gray-600">Applicazione completa per negozi Shopify</p>
                    </div>
                </div>

                <!-- Features -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Caratteristiche:</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Dashboard admin integrata
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            API Key sicura nel database
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Analytics e conversazioni
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Installazione automatica
                        </li>
                    </ul>
                </div>

                <!-- Download Button -->
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('site-owner.integrations.shopify.download') }}" 
                       class="btn-primary text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Scarica App Shopify
                    </a>
                    <button type="button" class="btn-secondary text-center" onclick="toggleShopifyInstructions()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Istruzioni di Installazione
                    </button>
                </div>
            </div>

            <!-- Shopify Instructions (Hidden by default) -->
            <div id="shopify-instructions" class="border-t border-gray-200 bg-gray-50 p-6 hidden">
                <h4 class="font-semibold text-gray-900 mb-3">Istruzioni di Installazione:</h4>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Scarica il file ZIP dell'app cliccando il pulsante sopra</li>
                    <li>Estrai il contenuto del file ZIP</li>
                    <li>Crea un account <strong>Shopify Partner</strong> se non ne hai uno</li>
                    <li>Nel dashboard Partner, crea una nuova app</li>
                    <li>Configura le variabili d'ambiente nel file <code>.env</code></li>
                    <li>Installa le dipendenze: <code>npm install</code></li>
                    <li>Avvia il server di sviluppo: <code>npm run dev</code></li>
                    <li>Esponi il server con ngrok: <code>npm run ngrok</code></li>
                    <li>Configura l'URL dell'app nel dashboard Shopify Partner</li>
                    <li>Installa l'app nel tuo negozio Shopify</li>
                    <li>Configura l'API Key nel dashboard dell'app</li>
                    <li>Copia il codice di installazione nel tuo tema</li>
                </ol>
                
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-yellow-700">
                            <strong>Attenzione:</strong> L'installazione dell'app Shopify richiede conoscenze tecniche avanzate. 
                            È consigliabile rivolgersi a uno sviluppatore esperto.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Keys Section -->
    @if($sites->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Le Tue API Keys</h2>
        <p class="text-sm text-gray-600 mb-4">
            Usa queste API Keys per configurare i plugin nei tuoi siti:
        </p>
        
        <div class="space-y-3">
            @foreach($sites as $site)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="font-medium text-gray-900">{{ $site->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $site->url }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <code class="px-3 py-1 bg-gray-100 rounded text-sm font-mono">{{ $site->api_key }}</code>
                    <button onclick="copyToClipboard('{{ $site->api_key }}')" 
                            class="btn-secondary text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        Copia
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex">
            <svg class="w-5 h-5 text-yellow-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-yellow-800">Nessun sito configurato</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Prima di scaricare i plugin, devi creare almeno un sito nella sezione 
                    <a href="{{ route('site-owner.sites.index') }}" class="font-medium underline">Siti</a>.
                </p>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleWordPressInstructions() {
    const instructions = document.getElementById('wordpress-instructions');
    instructions.classList.toggle('hidden');
}

function toggleShopifyInstructions() {
    const instructions = document.getElementById('shopify-instructions');
    instructions.classList.toggle('hidden');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'API Key copiata negli appunti!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }).catch(function(err) {
        console.error('Errore nel copiare il testo: ', err);
    });
}
</script>
@endsection
