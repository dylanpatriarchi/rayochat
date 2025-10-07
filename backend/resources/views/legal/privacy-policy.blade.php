@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Informativa sulla Privacy</h1>
        <p class="text-gray">Ultimo aggiornamento: 7 ottobre 2025</p>
    </div>

    <div class="legal-content">
        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">1. TITOLARE DEL TRATTAMENTO</h2>
            <p class="mb-4"><strong>Rayo Consulting</strong><br>
            Email: info@rayo.consulting<br>
            Sito web: rayo.consulting</p>
            <p class="mb-4">Il presente documento costituisce l'informativa privacy ai sensi dell'art. 13 del Regolamento UE 2016/679 (GDPR) relativa al trattamento dei dati personali degli utenti della piattaforma RayoChat.</p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">2. DESCRIZIONE DEL SERVIZIO</h2>
            <p class="mb-4">RayoChat è una piattaforma di intelligenza artificiale per il supporto clienti che combina:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Backend Laravel</strong>: Dashboard amministrativa per la gestione di siti e utenti</li>
                <li><strong>Servizio RAG (Retrieval-Augmented Generation)</strong>: Sistema AI basato su OpenAI per risposte intelligenti</li>
                <li><strong>Plugin WordPress</strong>: Widget di chat per siti web dei clienti</li>
                <li><strong>App Shopify</strong>: Integrazione per negozi Shopify</li>
                <li><strong>Infrastruttura Docker</strong>: Deployment containerizzato con PostgreSQL e Redis</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">3. TIPOLOGIE DI DATI TRATTATI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.1 Dati degli Amministratori e Proprietari di Siti</h3>
            <p class="mb-2"><strong>Dati identificativi:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Nome e cognome</li>
                <li>Indirizzo email</li>
                <li>Timestamp ultimo accesso</li>
                <li>Ruolo utente (Admin/Site Owner)</li>
            </ul>

            <p class="mb-2"><strong>Dati di autenticazione:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Codice OTP temporaneo (6 cifre, scadenza 10 minuti)</li>
                <li>Token di sessione OTP</li>
                <li>Token di sessione Laravel</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.2 Dati dei Siti Web Clienti</h3>
            <p class="mb-2"><strong>Informazioni sito:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Nome del sito web</li>
                <li>URL del sito web</li>
                <li>Chiave API univoca (formato: rc_s_[32 caratteri])</li>
                <li>Informazioni aziendali in formato Markdown</li>
                <li>Contenuto HTML generato automaticamente</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.3 Dati degli Utenti Finali (Visitatori dei Siti)</h3>
            <p class="mb-2"><strong>Dati di conversazione:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Messaggi inviati tramite widget chat</li>
                <li>Risposte generate dall'AI</li>
                <li>ID conversazione</li>
                <li>Session ID temporaneo</li>
                <li>Timestamp delle interazioni</li>
            </ul>

            <p class="mb-2"><strong>Dati tecnici:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Indirizzo IP (tramite sessioni Laravel)</li>
                <li>User Agent del browser</li>
                <li>URL della pagina di provenienza</li>
                <li>Dati di performance e analytics</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">4. FINALITÀ E BASE GIURIDICA DEL TRATTAMENTO</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.1 Gestione del Servizio (Art. 6.1.b GDPR - Esecuzione contratto)</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Autenticazione utenti tramite sistema OTP</li>
                <li>Gestione account amministratori e proprietari siti</li>
                <li>Generazione e gestione chiavi API</li>
                <li>Erogazione del servizio di chat AI</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.2 Funzionalità AI e Chat (Art. 6.1.b GDPR - Esecuzione contratto)</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Elaborazione messaggi tramite OpenAI GPT-3.5-turbo</li>
                <li>Generazione embeddings tramite text-embedding-ada-002</li>
                <li>Memorizzazione vettoriale in ChromaDB per ricerca semantica</li>
                <li>Caching risposte per migliorare performance</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.3 Sicurezza e Prevenzione Frodi (Art. 6.1.f GDPR - Interesse legittimo)</h3>
            <ul class="list-disc pl-6 mb-4">
                <li>Sistema Guardrails per validazione input/output</li>
                <li>Rate limiting (30 richieste/minuto, 500/ora)</li>
                <li>Prevenzione attacchi di prompt injection</li>
                <li>Monitoraggio pattern pericolosi e contenuti inappropriati</li>
                <li>Protezione XSS e SQL injection</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">5. CONDIVISIONE DATI CON TERZE PARTI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.1 OpenAI (Stati Uniti)</h3>
            <p class="mb-2"><strong>Tipologia dati condivisi:</strong></p>
            <ul class="list-disc pl-6 mb-2">
                <li>Messaggi utenti per generazione risposte</li>
                <li>Testi aziendali per creazione embeddings</li>
                <li>Metadati conversazioni per context awareness</li>
            </ul>
            <p class="mb-2"><strong>Base giuridica:</strong> Esecuzione contratto (Art. 6.1.b GDPR)</p>
            <p class="mb-2"><strong>Garanzie:</strong> OpenAI è certificato Privacy Shield e aderisce a Standard Contractual Clauses</p>
            <p class="mb-4"><strong>Finalità:</strong> Generazione risposte AI e elaborazione linguaggio naturale</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.2 Zoho Mail (Irlanda/Germania)</h3>
            <p class="mb-2"><strong>Tipologia dati condivisi:</strong></p>
            <ul class="list-disc pl-6 mb-2">
                <li>Indirizzi email utenti</li>
                <li>Codici OTP temporanei</li>
                <li>Messaggi di benvenuto</li>
            </ul>
            <p class="mb-2"><strong>Base giuridica:</strong> Esecuzione contratto (Art. 6.1.b GDPR)</p>
            <p class="mb-4"><strong>Finalità:</strong> Invio comunicazioni di servizio e autenticazione</p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">6. TEMPI DI CONSERVAZIONE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.1 Dati Account Utenti</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Dati identificativi</strong>: Conservati fino a cancellazione account</li>
                <li><strong>Codici OTP</strong>: 10 minuti (scadenza automatica)</li>
                <li><strong>Token sessione</strong>: 120 minuti (configurabile)</li>
                <li><strong>Log accessi</strong>: 12 mesi per sicurezza</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.2 Dati Conversazioni</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Messaggi chat</strong>: 24 mesi per miglioramento AI</li>
                <li><strong>Analytics</strong>: 36 mesi per analisi trend</li>
                <li><strong>Cache Redis</strong>: 24 ore (TTL automatico)</li>
                <li><strong>Log sistema</strong>: 12 mesi</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">7. DIRITTI DELL'INTERESSATO</h2>
            <p class="mb-4">Ai sensi degli artt. 15-22 GDPR, l'interessato ha diritto di:</p>
            
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Diritto di Accesso (Art. 15)</strong>: Ottenere conferma del trattamento e copia dei dati personali</li>
                <li><strong>Diritto di Rettifica (Art. 16)</strong>: Correggere dati inesatti o incompleti</li>
                <li><strong>Diritto di Cancellazione (Art. 17)</strong>: Ottenere cancellazione dati quando non più necessari</li>
                <li><strong>Diritto di Limitazione (Art. 18)</strong>: Limitare il trattamento in caso di contestazione</li>
                <li><strong>Diritto di Portabilità (Art. 20)</strong>: Ricevere dati in formato strutturato</li>
                <li><strong>Diritto di Opposizione (Art. 21)</strong>: Opporsi al trattamento basato su interesse legittimo</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.1 Modalità di Esercizio</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Email</strong>: info@rayo.consulting</li>
                <li><strong>Tempi di risposta</strong>: 30 giorni (prorogabili di 60 giorni per complessità)</li>
                <li><strong>Gratuità</strong>: Prima richiesta gratuita</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">8. MISURE DI SICUREZZA</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.1 Sicurezza Tecnica</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Crittografia</strong>: AES-256 per dati a riposo, TLS 1.3 per dati in transito</li>
                <li><strong>Autenticazione</strong>: OTP multi-fattore, token JWT sicuri</li>
                <li><strong>Autorizzazione</strong>: RBAC con principio least privilege</li>
                <li><strong>Network Security</strong>: Firewall, VPN, network segmentation</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.2 Sicurezza Applicativa</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Input Validation</strong>: Sanitizzazione completa input utente</li>
                <li><strong>Output Encoding</strong>: Prevenzione XSS e injection attacks</li>
                <li><strong>Rate Limiting</strong>: Protezione DDoS e brute force</li>
                <li><strong>Guardrails AI</strong>: Validazione contenuti AI per sicurezza</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">9. COOKIE E TECNOLOGIE SIMILI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.1 Cookie Tecnici (Necessari)</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Laravel Session</strong>: Gestione sessioni utente (2 ore)</li>
                <li><strong>CSRF Token</strong>: Protezione attacchi cross-site (sessione)</li>
                <li><strong>OTP Session</strong>: Validazione codici temporanei (10 minuti)</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.2 Cookie di Preferenze</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Widget Settings</strong>: Configurazioni widget chat (30 giorni)</li>
                <li><strong>Language Preference</strong>: Lingua interfaccia (1 anno)</li>
                <li><strong>Theme Settings</strong>: Preferenze tema UI (1 anno)</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">10. MINORI</h2>
            <p class="mb-4">Il servizio RayoChat non è destinato a minori di 16 anni. Non raccogliamo consapevolmente dati personali di minori. Se veniamo a conoscenza di aver raccolto dati di un minore, procederemo alla cancellazione immediata.</p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">11. CONTATTI E RECLAMI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">11.1 Contatti Privacy</h3>
            <p class="mb-4">
                <strong>Email</strong>: info@rayo.consulting<br>
                <strong>Oggetto</strong>: "Privacy - [Tipo Richiesta]"<br>
                <strong>Risposta</strong>: Entro 30 giorni lavorativi
            </p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">11.2 Autorità di Controllo</h3>
            <p class="mb-4">In caso di reclami relativi al trattamento dei dati personali, è possibile rivolgersi all'Autorità Garante per la Protezione dei Dati Personali del proprio paese di residenza.</p>
            
            <p class="mb-4">
                <strong>Italia - Garante Privacy</strong><br>
                Piazza di Monte Citorio, 121 - 00186 Roma<br>
                Tel: +39 06 69677 1<br>
                Email: garante@gpdp.it<br>
                Web: www.garanteprivacy.it
            </p>
        </section>

        <div class="text-center mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray">
                <strong>Documento redatto in conformità al Regolamento UE 2016/679 (GDPR) e normative nazionali applicabili.</strong>
            </p>
            <p class="text-sm text-gray mt-2">
                <strong>Rayo Consulting - info@rayo.consulting</strong><br>
                Ultimo aggiornamento: 7 ottobre 2025
            </p>
        </div>
    </div>
</div>

<style>
.legal-content {
    line-height: 1.6;
    color: #4a5568;
}

.legal-content h2 {
    color: #2d3748;
    border-bottom: 2px solid #ff6b35;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.legal-content h3 {
    color: #2d3748;
    margin-top: 1.5rem;
}

.legal-content ul {
    margin-bottom: 1rem;
}

.legal-content li {
    margin-bottom: 0.5rem;
}

.legal-content strong {
    color: #2d3748;
    font-weight: 600;
}

.legal-content section {
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.legal-content section:last-child {
    border-bottom: none;
}
</style>
@endsection
