@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Cookie Policy</h1>
        <p class="text-gray">Ultimo aggiornamento: 7 ottobre 2025</p>
    </div>

    <div class="legal-content">
        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">1. INTRODUZIONE</h2>
            <p class="mb-4">La presente Cookie Policy illustra come RayoChat (Rayo Consulting - info@rayo.consulting) utilizza i cookie e tecnologie simili sui propri servizi e sui siti web che implementano il widget RayoChat.</p>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">1.1 Cosa sono i Cookie</h3>
            <p class="mb-4">I cookie sono piccoli file di testo che vengono memorizzati sul dispositivo dell'utente quando visita un sito web. Permettono al sito di riconoscere il dispositivo e memorizzare informazioni sulla visita.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">1.2 Tecnologie Simili</h3>
            <p class="mb-4">Oltre ai cookie, utilizziamo tecnologie simili come:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Local Storage</strong>: Memorizzazione dati nel browser</li>
                <li><strong>Session Storage</strong>: Dati temporanei di sessione</li>
                <li><strong>Web Beacons</strong>: Pixel di tracciamento</li>
                <li><strong>Fingerprinting</strong>: Identificazione dispositivo (limitato)</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">2. COOKIE UTILIZZATI DA RAYOCHAT</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.1 Cookie Tecnici Necessari</h3>
            <p class="mb-4">Questi cookie sono essenziali per il funzionamento del servizio e non richiedono consenso:</p>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Laravel Session Cookie</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat-session</li>
                    <li><strong>Scopo</strong>: Gestione sessioni utente autenticati</li>
                    <li><strong>Durata</strong>: 2 ore (configurabile)</li>
                    <li><strong>Dominio</strong>: Dashboard RayoChat</li>
                    <li><strong>Tipo</strong>: HTTP-Only, Secure, SameSite=Lax</li>
                </ul>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">CSRF Protection Token</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: XSRF-TOKEN</li>
                    <li><strong>Scopo</strong>: Protezione attacchi Cross-Site Request Forgery</li>
                    <li><strong>Durata</strong>: Sessione</li>
                    <li><strong>Dominio</strong>: Dashboard RayoChat</li>
                    <li><strong>Tipo</strong>: Secure, SameSite=Strict</li>
                </ul>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">OTP Session Token</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: otp_session</li>
                    <li><strong>Scopo</strong>: Validazione codici OTP durante autenticazione</li>
                    <li><strong>Durata</strong>: 10 minuti</li>
                    <li><strong>Dominio</strong>: Dashboard RayoChat</li>
                    <li><strong>Tipo</strong>: HTTP-Only, Secure</li>
                </ul>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.2 Cookie di Funzionalità</h3>
            <p class="mb-4">Questi cookie migliorano l'esperienza utente e richiedono consenso:</p>

            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Widget Configuration</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat_widget_config</li>
                    <li><strong>Scopo</strong>: Memorizzazione preferenze widget (posizione, colori)</li>
                    <li><strong>Durata</strong>: 30 giorni</li>
                    <li><strong>Dominio</strong>: Siti clienti</li>
                    <li><strong>Tipo</strong>: Local Storage</li>
                </ul>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Language Preference</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat_lang</li>
                    <li><strong>Scopo</strong>: Memorizzazione lingua interfaccia</li>
                    <li><strong>Durata</strong>: 1 anno</li>
                    <li><strong>Dominio</strong>: Dashboard e widget</li>
                    <li><strong>Tipo</strong>: Local Storage</li>
                </ul>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Chat Session</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat_session_id</li>
                    <li><strong>Scopo</strong>: Continuità conversazione durante la sessione</li>
                    <li><strong>Durata</strong>: Sessione browser</li>
                    <li><strong>Dominio</strong>: Siti clienti</li>
                    <li><strong>Tipo</strong>: Session Storage</li>
                </ul>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.3 Cookie Analytics (Consenso Richiesto)</h3>
            <p class="mb-4">Questi cookie raccolgono dati statistici e richiedono consenso esplicito:</p>

            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Internal Analytics</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat_analytics</li>
                    <li><strong>Scopo</strong>: Metriche utilizzo widget (aperture, messaggi, errori)</li>
                    <li><strong>Durata</strong>: 90 giorni</li>
                    <li><strong>Dominio</strong>: Siti clienti</li>
                    <li><strong>Tipo</strong>: Local Storage</li>
                    <li><strong>Dati raccolti</strong>: Eventi widget, timestamp, errori (anonimizzati)</li>
                </ul>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Performance Monitoring</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Nome</strong>: rayochat_perf</li>
                    <li><strong>Scopo</strong>: Monitoraggio performance widget e API</li>
                    <li><strong>Durata</strong>: 30 giorni</li>
                    <li><strong>Dominio</strong>: Siti clienti</li>
                    <li><strong>Tipo</strong>: Local Storage</li>
                    <li><strong>Dati raccolti</strong>: Tempi di risposta, errori di rete</li>
                </ul>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">3. COOKIE DI TERZE PARTI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.1 Google Analytics (Se Abilitato)</h3>
            <p class="mb-4">I siti clienti possono scegliere di abilitare Google Analytics per tracciare eventi del widget:</p>
            
            <div class="bg-red-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Google Analytics 4</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Cookie</strong>: _ga, _ga_*, _gid</li>
                    <li><strong>Scopo</strong>: Analisi comportamento utenti</li>
                    <li><strong>Durata</strong>: 2 anni (_ga), 24 ore (_gid)</li>
                    <li><strong>Controllo</strong>: Gestito dal sito cliente</li>
                    <li><strong>Privacy Policy</strong>: <a href="https://policies.google.com/privacy" class="text-orange-500 hover:text-orange-600">Google Privacy Policy</a></li>
                    <li><strong>Opt-out</strong>: <a href="https://tools.google.com/dlpage/gaoptout" class="text-orange-500 hover:text-orange-600">Google Analytics Opt-out</a></li>
                </ul>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.2 Shopify Analytics (App Shopify)</h3>
            <p class="mb-4">Per negozi Shopify che utilizzano l'app RayoChat:</p>
            
            <div class="bg-red-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Shopify Analytics</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Cookie</strong>: _shopify_*, _s, _y</li>
                    <li><strong>Scopo</strong>: Analytics e-commerce, customer tracking</li>
                    <li><strong>Durata</strong>: Varia (da sessione a 2 anni)</li>
                    <li><strong>Controllo</strong>: Gestito da Shopify</li>
                    <li><strong>Privacy Policy</strong>: <a href="https://www.shopify.com/legal/privacy" class="text-orange-500 hover:text-orange-600">Shopify Privacy Policy</a></li>
                </ul>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.3 CDN e Servizi Tecnici</h3>
            <p class="mb-4">Servizi tecnici che potrebbero impostare cookie:</p>
            
            <ul class="list-disc pl-6 mb-4">
                <li><strong>CloudFlare</strong>: Cookie di sicurezza e performance (__cf_bm, cf_clearance)</li>
                <li><strong>Font Providers</strong>: Google Fonts (nessun cookie di tracking)</li>
                <li><strong>API Endpoints</strong>: Cookie di sessione per autenticazione API</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">4. BASE GIURIDICA E CONSENSO</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.1 Cookie Tecnici Necessari</h3>
            <p class="mb-4"><strong>Base giuridica</strong>: Interesse legittimo (Art. 6.1.f GDPR) + Esenzione Art. 122 Codice Privacy</p>
            <p class="mb-4">Non richiedono consenso in quanto strettamente necessari per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Autenticazione e sicurezza</li>
                <li>Funzionamento tecnico del servizio</li>
                <li>Prevenzione frodi e attacchi</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.2 Cookie di Funzionalità</h3>
            <p class="mb-4"><strong>Base giuridica</strong>: Consenso (Art. 6.1.a GDPR) + Art. 122 Codice Privacy</p>
            <p class="mb-4">Richiedono consenso specifico per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Memorizzazione preferenze utente</li>
                <li>Miglioramento esperienza d'uso</li>
                <li>Personalizzazione interfaccia</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.3 Cookie Analytics</h3>
            <p class="mb-4"><strong>Base giuridica</strong>: Consenso (Art. 6.1.a GDPR) + Art. 122 Codice Privacy</p>
            <p class="mb-4">Richiedono consenso esplicito e informato per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Profilazione comportamentale</li>
                <li>Analisi statistiche dettagliate</li>
                <li>Condivisione dati con terze parti</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">5. GESTIONE DEL CONSENSO</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.1 Banner Cookie</h3>
            <p class="mb-4">I siti che implementano il widget RayoChat devono mostrare un banner cookie che:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Informa sull'uso di cookie</li>
                <li>Permette consenso granulare per categoria</li>
                <li>Fornisce link a questa Cookie Policy</li>
                <li>Consente revoca consenso in qualsiasi momento</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.2 Categorie di Consenso</h3>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Necessari</h4>
                <p class="text-sm mb-2">Cookie tecnici essenziali - sempre attivi</p>
                <p class="text-xs text-gray-600">Non è possibile disabilitare questi cookie</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Funzionalità</h4>
                <p class="text-sm mb-2">Migliorano l'esperienza utente - consenso opzionale</p>
                <p class="text-xs text-gray-600">Disabilitandoli alcune funzioni potrebbero non funzionare</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Analytics</h4>
                <p class="text-sm mb-2">Raccolgono statistiche anonime - consenso opzionale</p>
                <p class="text-xs text-gray-600">Aiutano a migliorare il servizio</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Marketing</h4>
                <p class="text-sm mb-2">Personalizzazione e pubblicità - consenso opzionale</p>
                <p class="text-xs text-gray-600">Attualmente non utilizzati da RayoChat</p>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.3 Revoca del Consenso</h3>
            <p class="mb-4">È possibile revocare il consenso in qualsiasi momento tramite:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Impostazioni browser</strong>: Cancellazione cookie</li>
                <li><strong>Banner cookie</strong>: Modifica preferenze (se disponibile)</li>
                <li><strong>Contatto diretto</strong>: Email a info@rayo.consulting</li>
                <li><strong>Opt-out specifici</strong>: Link forniti per servizi terzi</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">6. CONTROLLO DEI COOKIE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.1 Impostazioni Browser</h3>
            <p class="mb-4">Tutti i browser permettono di gestire i cookie. Guide per i browser principali:</p>
            
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Chrome</strong>: Impostazioni > Privacy e sicurezza > Cookie e altri dati dei siti</li>
                <li><strong>Firefox</strong>: Impostazioni > Privacy e sicurezza > Cookie e dati dei siti web</li>
                <li><strong>Safari</strong>: Preferenze > Privacy > Gestisci dati siti web</li>
                <li><strong>Edge</strong>: Impostazioni > Cookie e autorizzazioni sito</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.2 Modalità di Controllo</h3>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Blocco Totale</h4>
                <p class="text-sm mb-2">Blocca tutti i cookie</p>
                <p class="text-xs text-gray-600">⚠️ Può compromettere il funzionamento del sito</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Blocco Terze Parti</h4>
                <p class="text-sm mb-2">Blocca solo cookie di terze parti</p>
                <p class="text-xs text-gray-600">✅ Raccomandato per privacy</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Cancellazione Periodica</h4>
                <p class="text-sm mb-2">Cancella cookie alla chiusura browser</p>
                <p class="text-xs text-gray-600">⚠️ Richiederà nuovi consensi</p>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.3 Strumenti di Opt-out</h3>
            <p class="mb-4">Strumenti specifici per opt-out da servizi analytics:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Google Analytics</strong>: <a href="https://tools.google.com/dlpage/gaoptout" class="text-orange-500 hover:text-orange-600">Browser Add-on</a></li>
                <li><strong>Your Online Choices</strong>: <a href="http://www.youronlinechoices.com/" class="text-orange-500 hover:text-orange-600">Opt-out Advertising</a></li>
                <li><strong>Network Advertising Initiative</strong>: <a href="https://www.networkadvertising.org/choices/" class="text-orange-500 hover:text-orange-600">Consumer Opt-out</a></li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">7. COOKIE E DISPOSITIVI MOBILI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.1 App Mobile (Future)</h3>
            <p class="mb-4">Se svilupperemo app mobile, utilizzeremo tecnologie equivalenti:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Device ID</strong>: Identificatori dispositivo</li>
                <li><strong>Local Storage</strong>: Memorizzazione locale dati</li>
                <li><strong>Push Notifications</strong>: Token per notifiche</li>
                <li><strong>Analytics SDK</strong>: Librerie di analisi</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.2 Browser Mobile</h3>
            <p class="mb-4">Su dispositivi mobili, il widget funziona tramite browser e utilizza gli stessi cookie descritti, con alcune limitazioni:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Safari iOS: Limitazioni Intelligent Tracking Prevention</li>
                <li>Chrome Android: Supporto completo cookie</li>
                <li>Firefox Mobile: Controlli privacy avanzati</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">8. TRASFERIMENTI INTERNAZIONALI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.1 Dati Cookie in Paesi Terzi</h3>
            <p class="mb-4">Alcuni cookie possono trasferire dati verso paesi terzi:</p>
            
            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Google Analytics (USA)</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Garanzie</strong>: Google Analytics 4 con anonimizzazione IP</li>
                    <li><strong>Base legale</strong>: Standard Contractual Clauses</li>
                    <li><strong>Controlli</strong>: Data Retention configurabile</li>
                </ul>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Shopify (Canada)</h4>
                <ul class="list-disc pl-6 text-sm">
                    <li><strong>Garanzie</strong>: Decisione di adeguatezza UE-Canada</li>
                    <li><strong>Base legale</strong>: Adeguatezza + Standard Contractual Clauses</li>
                    <li><strong>Controlli</strong>: Privacy settings Shopify</li>
                </ul>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.2 Misure di Protezione</h3>
            <p class="mb-4">Per tutti i trasferimenti internazionali implementiamo:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Crittografia in transito (TLS 1.3)</li>
                <li>Pseudonimizzazione dati quando possibile</li>
                <li>Minimizzazione dati trasferiti</li>
                <li>Controlli accesso rigorosi</li>
                <li>Audit periodici fornitori</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">9. AGGIORNAMENTI E MODIFICHE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.1 Modifiche alla Policy</h3>
            <p class="mb-4">Questa Cookie Policy può essere aggiornata per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Modifiche normative (ePrivacy Regulation, etc.)</li>
                <li>Nuove funzionalità del servizio</li>
                <li>Cambi di fornitori terzi</li>
                <li>Miglioramenti privacy e sicurezza</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.2 Notifica Modifiche</h3>
            <p class="mb-4">Le modifiche sostanziali saranno comunicate tramite:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Email agli utenti registrati</li>
                <li>Banner informativo nella dashboard</li>
                <li>Aggiornamento data "ultimo aggiornamento"</li>
                <li>Richiesta nuovo consenso se necessario</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.3 Cronologia Modifiche</h3>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold mb-2">Versione 1.0 - 7 ottobre 2025</h4>
                <p class="text-sm">Prima versione della Cookie Policy</p>
                <ul class="list-disc pl-6 text-xs mt-2">
                    <li>Definizione cookie tecnici, funzionalità e analytics</li>
                    <li>Implementazione gestione consenso</li>
                    <li>Conformità GDPR e Codice Privacy italiano</li>
                </ul>
            </div>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">10. CONTATTI E DIRITTI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.1 Contatti Cookie</h3>
            <p class="mb-4">Per domande sui cookie o per esercitare i propri diritti:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Email</strong>: info@rayo.consulting</li>
                <li><strong>Oggetto</strong>: "Cookie Policy - [Richiesta]"</li>
                <li><strong>Risposta</strong>: Entro 30 giorni</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.2 Diritti dell'Interessato</h3>
            <p class="mb-4">Relativamente ai dati raccolti tramite cookie, è possibile:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Accesso</strong>: Ottenere informazioni sui cookie utilizzati</li>
                <li><strong>Rettifica</strong>: Correggere dati inesatti</li>
                <li><strong>Cancellazione</strong>: Richiedere cancellazione dati cookie</li>
                <li><strong>Opposizione</strong>: Opporsi al trattamento per marketing</li>
                <li><strong>Limitazione</strong>: Limitare l'uso di specifici cookie</li>
                <li><strong>Portabilità</strong>: Ottenere dati in formato strutturato</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.3 Reclami</h3>
            <p class="mb-4">In caso di violazioni privacy relative ai cookie, è possibile presentare reclamo a:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Garante Privacy Italiano</strong>: www.garanteprivacy.it</li>
                <li><strong>Autorità Garante del paese di residenza</strong></li>
                <li><strong>European Data Protection Board</strong>: edpb.europa.eu</li>
            </ul>
        </section>

        <div class="text-center mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray">
                <strong>Cookie Policy redatta in conformità al GDPR, Direttiva ePrivacy e Codice Privacy italiano.</strong>
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

.legal-content h4 {
    color: #2d3748;
    font-size: 1rem;
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

.legal-content a {
    color: #ff6b35;
    text-decoration: underline;
}

.legal-content a:hover {
    color: #e55a2b;
}
</style>
@endsection
