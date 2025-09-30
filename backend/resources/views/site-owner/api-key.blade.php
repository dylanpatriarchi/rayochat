@extends('layouts.app')

@section('title', 'API Key - RayoChat')

@section('content')
<div class="container">
    <div class="page-header">
        <h1 class="page-title">API Key</h1>
        <p class="page-subtitle">Usa questa API key per integrare il widget RayoChat</p>
    </div>

    <div class="card">
        <div class="card-header">La Tua API Key</div>
        <div style="display: flex; gap: 1rem; align-items: center; background: var(--color-gray-50); padding: 1rem; border-radius: 6px; font-family: monospace;">
            <code id="api-key" style="flex: 1; font-size: 0.95rem;">{{ $company->api_key }}</code>
            <button onclick="copyApiKey()" class="btn btn-secondary">Copia</button>
        </div>
        <p style="margin-top: 1rem; color: var(--color-gray-600); font-size: 0.9rem;">
            ⚠️ Non condividere questa key pubblicamente. Usala solo nel tuo widget o plugin.
        </p>
    </div>

    <div class="card">
        <div class="card-header">Integrazione React</div>
        <pre style="background: var(--color-gray-900); color: #00ff00; padding: 1.5rem; border-radius: 6px; overflow-x: auto;"><code>import RayoChatWidget from './RayoChatWidget';

function App() {
  return (
    &lt;RayoChatWidget apiKey="{{ $company->api_key }}" /&gt;
  );
}</code></pre>
    </div>

    <div class="card">
        <div class="card-header">Integrazione WordPress</div>
        <p style="margin-bottom: 1rem;">
            1. Scarica il plugin WordPress<br>
            2. Attivalo nella tua installazione<br>
            3. Vai in Impostazioni → RayoChat<br>
            4. Incolla la tua API key
        </p>
        <a href="{{ route('site-owner.download-plugin') }}" class="btn btn-primary">Scarica Plugin</a>
    </div>
</div>

<script>
function copyApiKey() {
    const apiKey = document.getElementById('api-key').textContent;
    navigator.clipboard.writeText(apiKey).then(() => {
        alert('API Key copiata negli appunti!');
    });
}
</script>
@endsection
