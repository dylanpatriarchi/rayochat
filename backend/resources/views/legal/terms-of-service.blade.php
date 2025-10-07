@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Termini di Servizio</h1>
        <p class="text-gray">Ultimo aggiornamento: 7 ottobre 2025</p>
    </div>

    <div class="legal-content">
        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">1. DEFINIZIONI E AMBITO DI APPLICAZIONE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">1.1 Definizioni</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>"Servizio"</strong>: La piattaforma RayoChat e tutti i suoi componenti</li>
                <li><strong>"Fornitore"</strong>: Rayo Consulting (info@rayo.consulting)</li>
                <li><strong>"Utente"</strong>: Qualsiasi persona che utilizza il Servizio</li>
                <li><strong>"Cliente"</strong>: Utente che ha sottoscritto un piano a pagamento</li>
                <li><strong>"Sito Cliente"</strong>: Sito web che implementa il widget RayoChat</li>
                <li><strong>"Visitatore"</strong>: Utente finale che interagisce con il widget</li>
                <li><strong>"AI"</strong>: Sistema di intelligenza artificiale basato su OpenAI</li>
                <li><strong>"API"</strong>: Application Programming Interface del servizio</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">1.2 Ambito di Applicazione</h3>
            <p class="mb-4">I presenti Termini di Servizio regolano l'utilizzo della piattaforma RayoChat, inclusi:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Dashboard amministrativa Laravel</li>
                <li>Servizio RAG (Retrieval-Augmented Generation)</li>
                <li>Plugin WordPress</li>
                <li>App Shopify</li>
                <li>API e integrazioni</li>
                <li>Servizi di supporto e consulenza</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">2. ACCETTAZIONE DEI TERMINI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.1 Accettazione</h3>
            <p class="mb-4">L'utilizzo del Servizio implica l'accettazione integrale dei presenti Termini. Se non si accettano questi termini, non è possibile utilizzare il Servizio.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.2 Capacità Giuridica</h3>
            <p class="mb-4">L'Utente dichiara di:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Avere almeno 18 anni o la maggiore età nel proprio paese</li>
                <li>Possedere la capacità giuridica per stipulare contratti</li>
                <li>Agire per conto di un'organizzazione legalmente costituita (se applicabile)</li>
                <li>Avere l'autorità per vincolare tale organizzazione</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">2.3 Modifiche ai Termini</h3>
            <p class="mb-4">Il Fornitore si riserva il diritto di modificare questi Termini in qualsiasi momento. Le modifiche saranno comunicate tramite:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Email agli utenti registrati</li>
                <li>Notifica nella dashboard</li>
                <li>Pubblicazione sul sito web</li>
            </ul>
            <p class="mb-4">L'uso continuato del Servizio dopo le modifiche costituisce accettazione dei nuovi termini.</p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">3. DESCRIZIONE DEL SERVIZIO</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.1 Funzionalità Principali</h3>
            <p class="mb-2"><strong>Dashboard Amministrativa:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Gestione account utenti con autenticazione OTP</li>
                <li>Controllo accessi basato su ruoli (Admin/Site Owner)</li>
                <li>Gestione siti web e generazione chiavi API</li>
                <li>Editor Markdown per informazioni aziendali</li>
                <li>Analytics e monitoraggio conversazioni</li>
            </ul>

            <p class="mb-2"><strong>Servizio AI (RAG):</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Elaborazione linguaggio naturale tramite OpenAI GPT-3.5-turbo</li>
                <li>Generazione embeddings per ricerca semantica</li>
                <li>Sistema Guardrails per sicurezza contenuti</li>
                <li>Rate limiting: 30 richieste/minuto, 500/ora per API key</li>
                <li>Caching intelligente per performance ottimali</li>
            </ul>

            <p class="mb-2"><strong>Widget Chat:</strong></p>
            <ul class="list-disc pl-6 mb-4">
                <li>Interfaccia iMessage-style responsive</li>
                <li>Integrazione WordPress e Shopify</li>
                <li>Personalizzazione colori e posizionamento</li>
                <li>Analytics eventi e interazioni</li>
                <li>Supporto multilingua</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">3.2 Disponibilità del Servizio</h3>
            <p class="mb-4">Il Fornitore si impegna a mantenere il Servizio disponibile 24/7 con un uptime target del 99.5%. Sono esclusi da questo impegno:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Manutenzioni programmate (notificate con 48h di anticipo)</li>
                <li>Interruzioni dovute a cause di forza maggiore</li>
                <li>Problemi di connettività dell'Utente</li>
                <li>Interruzioni di servizi terzi (OpenAI, AWS, etc.)</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">4. REGISTRAZIONE E ACCOUNT</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.1 Creazione Account</h3>
            <p class="mb-4">Per utilizzare il Servizio è necessario:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Fornire informazioni accurate e complete</li>
                <li>Mantenere aggiornati i dati di contatto</li>
                <li>Utilizzare un indirizzo email valido e accessibile</li>
                <li>Accettare l'autenticazione OTP senza password</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.2 Sicurezza Account</h3>
            <p class="mb-4">L'Utente è responsabile di:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Mantenere riservati i codici OTP ricevuti</li>
                <li>Non condividere l'accesso al proprio account</li>
                <li>Notificare immediatamente accessi non autorizzati</li>
                <li>Utilizzare un indirizzo email sicuro e protetto</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">4.3 Limitazioni Account</h3>
            <p class="mb-4">Ogni account ha limitazioni basate sul piano sottoscritto:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Site Owner</strong>: Massimo 3 siti (configurabile)</li>
                <li><strong>Rate Limiting</strong>: 30 richieste/minuto per API key</li>
                <li><strong>Storage</strong>: Limitazioni su dimensioni contenuti aziendali</li>
                <li><strong>Analytics</strong>: Retention dati secondo piano sottoscritto</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">5. USO ACCETTABILE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.1 Usi Consentiti</h3>
            <p class="mb-4">Il Servizio può essere utilizzato per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Supporto clienti automatizzato</li>
                <li>Assistenza pre-vendita e informazioni prodotti</li>
                <li>FAQ dinamiche e knowledge base</li>
                <li>Lead generation e qualificazione</li>
                <li>Supporto multilingua per e-commerce</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.2 Usi Vietati</h3>
            <p class="mb-4">È espressamente vietato utilizzare il Servizio per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Contenuti illegali</strong>: Attività illegali, frodi, spam</li>
                <li><strong>Contenuti dannosi</strong>: Malware, virus, phishing</li>
                <li><strong>Violazione diritti</strong>: Copyright, marchi, brevetti</li>
                <li><strong>Contenuti inappropriati</strong>: Pornografia, violenza, discriminazione</li>
                <li><strong>Manipolazione AI</strong>: Prompt injection, jailbreaking</li>
                <li><strong>Reverse engineering</strong>: Tentativi di replicare il servizio</li>
                <li><strong>Sovraccarico sistema</strong>: Attacchi DDoS, scraping massivo</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">5.3 Sistema Guardrails</h3>
            <p class="mb-4">Il Servizio implementa controlli automatici che:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Filtrano contenuti inappropriati in input e output</li>
                <li>Prevengono attacchi di prompt injection</li>
                <li>Limitano lunghezza e frequenza messaggi</li>
                <li>Monitorano pattern sospetti</li>
                <li>Generano risposte di fallback sicure</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">6. PROPRIETÀ INTELLETTUALE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.1 Proprietà del Fornitore</h3>
            <p class="mb-4">Il Fornitore mantiene tutti i diritti su:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Codice sorgente della piattaforma</li>
                <li>Algoritmi e logiche di business</li>
                <li>Design e interfacce utente</li>
                <li>Documentazione e materiali formativi</li>
                <li>Marchi e loghi RayoChat</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.2 Contenuti dell'Utente</h3>
            <p class="mb-4">L'Utente mantiene la proprietà dei propri contenuti ma concede al Fornitore:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Licenza d'uso</strong>: Per erogare il servizio</li>
                <li><strong>Diritto di elaborazione</strong>: Per generare embeddings e risposte AI</li>
                <li><strong>Diritto di memorizzazione</strong>: Per caching e performance</li>
                <li><strong>Diritto di analisi</strong>: Per miglioramenti del servizio (dati anonimizzati)</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">6.3 Contenuti Generati dall'AI</h3>
            <p class="mb-4">I contenuti generati dall'AI sono soggetti a:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Licenza d'uso</strong>: L'Utente può utilizzarli liberamente</li>
                <li><strong>Nessuna garanzia</strong>: Su accuratezza o appropriatezza</li>
                <li><strong>Responsabilità Utente</strong>: Per l'uso e la pubblicazione</li>
                <li><strong>Limitazioni OpenAI</strong>: Secondo termini OpenAI applicabili</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">7. PRIVACY E PROTEZIONE DATI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.1 Conformità GDPR</h3>
            <p class="mb-4">Il Servizio è conforme al GDPR e garantisce:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Trattamento dati secondo principi di liceità, correttezza, trasparenza</li>
                <li>Minimizzazione dati raccolti</li>
                <li>Sicurezza mediante misure tecniche e organizzative</li>
                <li>Rispetto diritti degli interessati</li>
                <li>Notifica data breach entro 72 ore</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.2 Trasferimenti Internazionali</h3>
            <p class="mb-4">I dati possono essere trasferiti a:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>OpenAI (USA)</strong>: Standard Contractual Clauses</li>
                <li><strong>Shopify (Canada)</strong>: Decisione di adeguatezza</li>
                <li><strong>Zoho (EU)</strong>: Server europei</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">7.3 Diritti degli Interessati</h3>
            <p class="mb-4">Gli utenti possono esercitare i diritti GDPR contattando info@rayo.consulting per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Accesso ai dati personali</li>
                <li>Rettifica dati inesatti</li>
                <li>Cancellazione (diritto all'oblio)</li>
                <li>Limitazione del trattamento</li>
                <li>Portabilità dei dati</li>
                <li>Opposizione al trattamento</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">8. PIANI E PAGAMENTI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.1 Piani di Servizio</h3>
            <p class="mb-4">Il Servizio offre diversi piani con caratteristiche e limitazioni specifiche. I dettagli sono disponibili sul sito web e possono essere modificati con preavviso di 30 giorni.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.2 Fatturazione</h3>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Frequenza</strong>: Mensile o annuale secondo piano scelto</li>
                <li><strong>Valuta</strong>: Euro (EUR)</li>
                <li><strong>Metodi di pagamento</strong>: Carta di credito, bonifico bancario</li>
                <li><strong>Fatturazione automatica</strong>: Rinnovo automatico salvo disdetta</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">8.3 Modifiche Prezzi</h3>
            <p class="mb-4">Il Fornitore può modificare i prezzi con preavviso di 60 giorni. Gli utenti possono:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Accettare i nuovi prezzi</li>
                <li>Disdire il servizio entro la data di applicazione</li>
                <li>Mantenere il prezzo corrente fino alla scadenza del piano annuale</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">9. SOSPENSIONE E RISOLUZIONE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.1 Sospensione del Servizio</h3>
            <p class="mb-4">Il Fornitore può sospendere l'accesso in caso di:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Violazione dei Termini di Servizio</li>
                <li>Mancato pagamento oltre 15 giorni</li>
                <li>Attività sospette o dannose</li>
                <li>Richiesta delle autorità competenti</li>
                <li>Manutenzioni di emergenza</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.2 Risoluzione da parte dell'Utente</h3>
            <p class="mb-4">L'Utente può risolvere il contratto:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Piani mensili</strong>: Con preavviso di 30 giorni</li>
                <li><strong>Piani annuali</strong>: Alla scadenza naturale</li>
                <li><strong>Giusta causa</strong>: Immediatamente per violazioni gravi del Fornitore</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.3 Risoluzione da parte del Fornitore</h3>
            <p class="mb-4">Il Fornitore può risolvere il contratto per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Violazioni gravi o ripetute dei Termini</li>
                <li>Mancato pagamento oltre 30 giorni</li>
                <li>Cessazione del servizio (con preavviso 90 giorni)</li>
                <li>Impossibilità tecnica di erogazione</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">9.4 Effetti della Risoluzione</h3>
            <p class="mb-4">Alla risoluzione del contratto:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>L'accesso al Servizio viene immediatamente sospeso</li>
                <li>I dati vengono conservati per 30 giorni per eventuale recupero</li>
                <li>Dopo 30 giorni i dati vengono cancellati definitivamente</li>
                <li>L'Utente può richiedere export dei dati entro 30 giorni</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">10. LIMITAZIONE DI RESPONSABILITÀ</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.1 Esclusioni di Garanzia</h3>
            <p class="mb-4">Il Servizio è fornito "as is" senza garanzie di:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Accuratezza delle risposte AI</li>
                <li>Disponibilità ininterrotta del servizio</li>
                <li>Compatibilità con tutti i sistemi</li>
                <li>Risultati commerciali specifici</li>
                <li>Assenza di errori o bug</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.2 Limitazioni di Responsabilità</h3>
            <p class="mb-4">La responsabilità del Fornitore è limitata a:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Importo massimo</strong>: Canoni pagati negli ultimi 12 mesi</li>
                <li><strong>Danni diretti</strong>: Esclusione danni indiretti, consequenziali, punitivi</li>
                <li><strong>Perdita di dati</strong>: Limitata al costo di ripristino da backup</li>
                <li><strong>Interruzioni</strong>: Crediti di servizio secondo SLA</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">10.3 Responsabilità dell'Utente</h3>
            <p class="mb-4">L'Utente è responsabile per:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Contenuti caricati e pubblicati</li>
                <li>Uso appropriato delle risposte AI</li>
                <li>Conformità alle normative applicabili</li>
                <li>Sicurezza del proprio account</li>
                <li>Backup dei propri dati</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">11. FORZA MAGGIORE</h2>
            <p class="mb-4">Nessuna delle parti sarà responsabile per inadempimenti dovuti a eventi di forza maggiore, inclusi:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Disastri naturali (terremoti, alluvioni, incendi)</li>
                <li>Eventi bellici, terrorismo, disordini civili</li>
                <li>Interruzioni di servizi pubblici essenziali</li>
                <li>Pandemie e emergenze sanitarie</li>
                <li>Interruzioni di servizi internet o cloud</li>
                <li>Modifiche normative che rendano impossibile l'erogazione</li>
            </ul>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">12. RISOLUZIONE CONTROVERSIE</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">12.1 Tentativo di Conciliazione</h3>
            <p class="mb-4">Prima di intraprendere azioni legali, le parti si impegnano a tentare una risoluzione amichevole tramite:</p>
            <ul class="list-disc pl-6 mb-4">
                <li>Comunicazione diretta con il supporto tecnico</li>
                <li>Escalation al management per controversie complesse</li>
                <li>Mediazione tramite organismo accreditato</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">12.2 Giurisdizione</h3>
            <p class="mb-4">Per controversie non risolte amichevolmente:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Consumatori</strong>: Foro del consumatore secondo normativa applicabile</li>
                <li><strong>Professionisti</strong>: Tribunale di Milano, Italia</li>
                <li><strong>Controversie internazionali</strong>: Arbitrato secondo regolamento ICC</li>
            </ul>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">12.3 Legge Applicabile</h3>
            <p class="mb-4">I presenti Termini sono regolati dalla legge italiana, in conformità alle normative europee applicabili (GDPR, Direttiva e-Commerce, etc.).</p>
        </section>

        <section class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">13. DISPOSIZIONI FINALI</h2>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-3">13.1 Integralità dell'Accordo</h3>
            <p class="mb-4">I presenti Termini, insieme alla Privacy Policy e alla Cookie Policy, costituiscono l'accordo completo tra le parti e sostituiscono qualsiasi accordo precedente.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">13.2 Nullità Parziale</h3>
            <p class="mb-4">Se una clausola dovesse essere dichiarata nulla o inapplicabile, le restanti clausole rimangono in vigore. La clausola nulla sarà sostituita con una valida di effetto economico equivalente.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">13.3 Cessione</h3>
            <p class="mb-4">L'Utente non può cedere i propri diritti senza consenso scritto. Il Fornitore può cedere il contratto in caso di fusione, acquisizione o cessione d'azienda.</p>

            <h3 class="text-lg font-semibold text-gray-900 mb-3">13.4 Comunicazioni</h3>
            <p class="mb-4">Tutte le comunicazioni devono essere inviate a:</p>
            <ul class="list-disc pl-6 mb-4">
                <li><strong>Email</strong>: info@rayo.consulting</li>
                <li><strong>Oggetto</strong>: "Termini di Servizio - [Oggetto]"</li>
                <li><strong>Lingua</strong>: Italiano o inglese</li>
            </ul>
        </section>

        <div class="text-center mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray">
                <strong>Termini di Servizio redatti in conformità alla normativa italiana ed europea applicabile.</strong>
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
