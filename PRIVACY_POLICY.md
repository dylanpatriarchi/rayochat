# INFORMATIVA SULLA PRIVACY - RAYOCHAT

**Ultimo aggiornamento: 7 ottobre 2025**

## 1. TITOLARE DEL TRATTAMENTO

**Rayo Consulting**  
Email: info@rayo.consulting  
Sito web: rayo.consulting

Il presente documento costituisce l'informativa privacy ai sensi dell'art. 13 del Regolamento UE 2016/679 (GDPR) relativa al trattamento dei dati personali degli utenti della piattaforma RayoChat.

## 2. DESCRIZIONE DEL SERVIZIO

RayoChat è una piattaforma di intelligenza artificiale per il supporto clienti che combina:
- **Backend Laravel**: Dashboard amministrativa per la gestione di siti e utenti
- **Servizio RAG (Retrieval-Augmented Generation)**: Sistema AI basato su OpenAI per risposte intelligenti
- **Plugin WordPress**: Widget di chat per siti web dei clienti
- **App Shopify**: Integrazione per negozi Shopify
- **Infrastruttura Docker**: Deployment containerizzato con PostgreSQL e Redis

## 3. TIPOLOGIE DI DATI TRATTATI

### 3.1 Dati degli Amministratori e Proprietari di Siti

**Dati identificativi:**
- Nome e cognome
- Indirizzo email
- Timestamp ultimo accesso
- Ruolo utente (Admin/Site Owner)

**Dati di autenticazione:**
- Codice OTP temporaneo (6 cifre, scadenza 10 minuti)
- Token di sessione OTP
- Token di sessione Laravel

**Dati di configurazione:**
- Numero massimo di siti consentiti
- Timestamp di creazione e aggiornamento account

### 3.2 Dati dei Siti Web Clienti

**Informazioni sito:**
- Nome del sito web
- URL del sito web
- Chiave API univoca (formato: rc_s_[32 caratteri])
- Informazioni aziendali in formato Markdown
- Contenuto HTML generato automaticamente

**Dati analitici:**
- Messaggi delle conversazioni
- Categoria di classificazione messaggi
- Livello di confidenza AI (0-1)
- Dati di classificazione in formato JSON
- Timestamp delle interazioni

### 3.3 Dati degli Utenti Finali (Visitatori dei Siti)

**Dati di conversazione:**
- Messaggi inviati tramite widget chat
- Risposte generate dall'AI
- ID conversazione
- Session ID temporaneo
- Timestamp delle interazioni

**Dati tecnici:**
- Indirizzo IP (tramite sessioni Laravel)
- User Agent del browser
- URL della pagina di provenienza
- Dati di performance e analytics

**Dati Shopify (se applicabile):**
- Customer ID Shopify
- Dati di interazione con il widget
- Eventi di tracking (apertura/chiusura widget, invio messaggi)

### 3.4 Dati di Sistema e Sicurezza

**Log di sistema:**
- Log applicativi Laravel
- Log del servizio RAG Python
- Log di sicurezza e accessi
- Metriche di performance

**Dati di cache:**
- Risposte AI memorizzate in Redis (temporanee)
- Dati di sessione utente
- Limiti di rate limiting per API

## 4. FINALITÀ E BASE GIURIDICA DEL TRATTAMENTO

### 4.1 Gestione del Servizio (Art. 6.1.b GDPR - Esecuzione contratto)
- Autenticazione utenti tramite sistema OTP
- Gestione account amministratori e proprietari siti
- Generazione e gestione chiavi API
- Erogazione del servizio di chat AI

### 4.2 Funzionalità AI e Chat (Art. 6.1.b GDPR - Esecuzione contratto)
- Elaborazione messaggi tramite OpenAI GPT-3.5-turbo
- Generazione embeddings tramite text-embedding-ada-002
- Memorizzazione vettoriale in ChromaDB per ricerca semantica
- Caching risposte per migliorare performance

### 4.3 Sicurezza e Prevenzione Frodi (Art. 6.1.f GDPR - Interesse legittimo)
- Sistema Guardrails per validazione input/output
- Rate limiting (30 richieste/minuto, 500/ora)
- Prevenzione attacchi di prompt injection
- Monitoraggio pattern pericolosi e contenuti inappropriati
- Protezione XSS e SQL injection

### 4.4 Analytics e Miglioramento Servizio (Art. 6.1.f GDPR - Interesse legittimo)
- Analisi conversazioni per migliorare risposte AI
- Metriche di utilizzo e performance
- Classificazione automatica messaggi
- Statistiche di violazione Guardrails

### 4.5 Comunicazioni di Servizio (Art. 6.1.b GDPR - Esecuzione contratto)
- Invio codici OTP via email (SMTP Zoho)
- Notifiche di benvenuto nuovi utenti
- Comunicazioni tecniche e di sicurezza

## 5. MODALITÀ DI TRATTAMENTO

### 5.1 Trattamento Automatizzato
- **Database PostgreSQL**: Archiviazione dati strutturati con crittografia
- **Redis**: Cache temporanea e gestione sessioni (TTL configurabile)
- **ChromaDB**: Archiviazione vettoriale per ricerca semantica
- **Sistema Guardrails**: Validazione automatica contenuti

### 5.2 Sicurezza Tecnica
- **Crittografia**: Connessioni SSL/TLS, dati sensibili crittografati
- **Autenticazione**: Sistema OTP senza password, token di sessione sicuri
- **Autorizzazione**: Controllo accessi basato su ruoli (RBAC)
- **Validazione Input**: Sanitizzazione XSS, prevenzione SQL injection
- **Headers di Sicurezza**: X-Frame-Options, X-XSS-Protection, CSP

### 5.3 Containerizzazione e Isolamento
- **Docker**: Isolamento servizi in container separati
- **Network Isolation**: Comunicazione interna protetta
- **Resource Limits**: Limitazione risorse per prevenire DoS

## 6. CONDIVISIONE DATI CON TERZE PARTI

### 6.1 OpenAI (Stati Uniti)
**Tipologia dati condivisi:**
- Messaggi utenti per generazione risposte
- Testi aziendali per creazione embeddings
- Metadati conversazioni per context awareness

**Base giuridica:** Esecuzione contratto (Art. 6.1.b GDPR)  
**Garanzie:** OpenAI è certificato Privacy Shield e aderisce a Standard Contractual Clauses  
**Retention:** Secondo policy OpenAI (30 giorni per API calls)  
**Finalità:** Generazione risposte AI e elaborazione linguaggio naturale

### 6.2 Zoho Mail (Irlanda/Germania)
**Tipologia dati condivisi:**
- Indirizzi email utenti
- Codici OTP temporanei
- Messaggi di benvenuto

**Base giuridica:** Esecuzione contratto (Art. 6.1.b GDPR)  
**Garanzie:** Zoho è conforme GDPR, server EU  
**Finalità:** Invio comunicazioni di servizio e autenticazione

### 6.3 Shopify (Canada)
**Tipologia dati condivisi (solo per app Shopify):**
- Configurazioni widget
- Eventi analytics
- Customer ID (se disponibile)

**Base giuridica:** Esecuzione contratto (Art. 6.1.b GDPR)  
**Garanzie:** Shopify aderisce a Privacy Shield e Standard Contractual Clauses  
**Finalità:** Integrazione e-commerce e analytics

### 6.4 Google Analytics (se abilitato sui siti clienti)
**Tipologia dati condivisi:**
- Eventi widget (apertura, chiusura, messaggi)
- Dati di sessione anonimizzati
- Metriche di utilizzo

**Base giuridica:** Consenso (Art. 6.1.a GDPR)  
**Controllo:** Gestito dai singoli siti web clienti  
**Finalità:** Analytics e miglioramento UX

## 7. TRASFERIMENTI INTERNAZIONALI

### 7.1 Trasferimenti verso Paesi Terzi
- **OpenAI (USA)**: Standard Contractual Clauses + misure tecniche supplementari
- **Shopify (Canada)**: Decisione di adeguatezza + Standard Contractual Clauses

### 7.2 Garanzie Implementate
- Crittografia end-to-end per tutti i trasferimenti
- Minimizzazione dati trasferiti
- Pseudonimizzazione quando possibile
- Monitoraggio accessi e audit trail

## 8. TEMPI DI CONSERVAZIONE

### 8.1 Dati Account Utenti
- **Dati identificativi**: Conservati fino a cancellazione account
- **Codici OTP**: 10 minuti (scadenza automatica)
- **Token sessione**: 120 minuti (configurabile)
- **Log accessi**: 12 mesi per sicurezza

### 8.2 Dati Conversazioni
- **Messaggi chat**: 24 mesi per miglioramento AI
- **Analytics**: 36 mesi per analisi trend
- **Cache Redis**: 24 ore (TTL automatico)
- **Log sistema**: 12 mesi

### 8.3 Dati Tecnici
- **Log applicativi**: 6 mesi
- **Metriche performance**: 12 mesi
- **Dati sicurezza**: 24 mesi
- **Backup**: 30 giorni (rotazione automatica)

## 9. DIRITTI DELL'INTERESSATO

Ai sensi degli artt. 15-22 GDPR, l'interessato ha diritto di:

### 9.1 Diritto di Accesso (Art. 15)
Ottenere conferma del trattamento e copia dei dati personali tramite richiesta a info@rayo.consulting

### 9.2 Diritto di Rettifica (Art. 16)
Correggere dati inesatti o incompleti tramite dashboard utente o richiesta email

### 9.3 Diritto di Cancellazione (Art. 17)
Ottenere cancellazione dati quando non più necessari o in caso di revoca consenso

### 9.4 Diritto di Limitazione (Art. 18)
Limitare il trattamento in caso di contestazione accuratezza o opposizione

### 9.5 Diritto di Portabilità (Art. 20)
Ricevere dati in formato strutturato e trasmetterli ad altro titolare

### 9.6 Diritto di Opposizione (Art. 21)
Opporsi al trattamento basato su interesse legittimo o per finalità di marketing

### 9.7 Diritto di Revoca Consenso (Art. 7.3)
Revocare consenso in qualsiasi momento senza pregiudicare liceità trattamento pregresso

### 9.8 Modalità di Esercizio
- **Email**: info@rayo.consulting
- **Tempi di risposta**: 30 giorni (prorogabili di 60 giorni per complessità)
- **Gratuità**: Prima richiesta gratuita, successive a pagamento se manifestamente infondate

## 10. MISURE DI SICUREZZA TECNICHE E ORGANIZZATIVE

### 10.1 Sicurezza Tecnica
- **Crittografia**: AES-256 per dati a riposo, TLS 1.3 per dati in transito
- **Autenticazione**: OTP multi-fattore, token JWT sicuri
- **Autorizzazione**: RBAC con principio least privilege
- **Network Security**: Firewall, VPN, network segmentation
- **Monitoring**: SIEM, log analysis, anomaly detection

### 10.2 Sicurezza Applicativa
- **Input Validation**: Sanitizzazione completa input utente
- **Output Encoding**: Prevenzione XSS e injection attacks
- **Rate Limiting**: Protezione DDoS e brute force
- **Guardrails AI**: Validazione contenuti AI per sicurezza

### 10.3 Sicurezza Organizzativa
- **Access Control**: Accesso limitato al personale autorizzato
- **Training**: Formazione continua su privacy e sicurezza
- **Incident Response**: Procedure definite per data breach
- **Audit**: Revisioni periodiche sicurezza e compliance

### 10.4 Backup e Disaster Recovery
- **Backup**: Backup automatici giornalieri crittografati
- **Retention**: 30 giorni con rotazione automatica
- **Testing**: Test recovery mensili
- **Geographic Distribution**: Backup in multiple location EU

## 11. DATA BREACH E NOTIFICHE

### 11.1 Procedure di Notifica
- **Autorità Garante**: Entro 72 ore dalla scoperta (Art. 33 GDPR)
- **Interessati**: Senza ritardo se alto rischio (Art. 34 GDPR)
- **Documentazione**: Registro completo di tutti i data breach

### 11.2 Misure Preventive
- **Monitoring**: Rilevamento automatico anomalie
- **Encryption**: Dati crittografati per ridurre impatto breach
- **Incident Response Team**: Team dedicato per gestione emergenze
- **Communication Plan**: Procedure comunicazione stakeholder

## 12. COOKIE E TECNOLOGIE SIMILI

### 12.1 Cookie Tecnici (Necessari)
- **Laravel Session**: Gestione sessioni utente (2 ore)
- **CSRF Token**: Protezione attacchi cross-site (sessione)
- **OTP Session**: Validazione codici temporanei (10 minuti)

### 12.2 Cookie di Preferenze
- **Widget Settings**: Configurazioni widget chat (30 giorni)
- **Language Preference**: Lingua interfaccia (1 anno)
- **Theme Settings**: Preferenze tema UI (1 anno)

### 12.3 Cookie Analytics (Consenso richiesto)
- **Google Analytics**: Se abilitato dai siti clienti
- **Internal Analytics**: Metriche utilizzo anonimizzate
- **Performance Monitoring**: Dati performance applicazione

### 12.4 Gestione Cookie
- **Banner Cookie**: Implementato sui siti che utilizzano il widget
- **Granular Consent**: Consenso specifico per categoria
- **Opt-out**: Possibilità di rifiutare cookie non necessari

## 13. MINORI

Il servizio RayoChat non è destinato a minori di 16 anni. Non raccogliamo consapevolmente dati personali di minori. Se veniamo a conoscenza di aver raccolto dati di un minore, procederemo alla cancellazione immediata.

I siti web che utilizzano il widget RayoChat sono responsabili di:
- Implementare verifiche età appropriate
- Ottenere consenso genitoriale quando richiesto
- Configurare il widget per conformità normative locali

## 14. MODIFICHE ALLA PRIVACY POLICY

### 14.1 Aggiornamenti
- Questa privacy policy può essere aggiornata per riflettere modifiche normative o del servizio
- Gli utenti saranno notificati via email delle modifiche sostanziali
- La versione aggiornata sarà pubblicata sul sito con data di ultimo aggiornamento

### 14.2 Accettazione Modifiche
- L'uso continuato del servizio dopo le modifiche costituisce accettazione
- Per modifiche sostanziali, potrebbe essere richiesto consenso esplicito
- Gli utenti possono sempre cessare l'utilizzo del servizio

## 15. CONTATTI E RECLAMI

### 15.1 Contatti Privacy
**Email**: info@rayo.consulting  
**Oggetto**: "Privacy - [Tipo Richiesta]"  
**Risposta**: Entro 30 giorni lavorativi

### 15.2 Autorità di Controllo
In caso di reclami relativi al trattamento dei dati personali, è possibile rivolgersi all'Autorità Garante per la Protezione dei Dati Personali del proprio paese di residenza.

**Italia - Garante Privacy**  
Piazza di Monte Citorio, 121 - 00186 Roma  
Tel: +39 06 69677 1  
Email: garante@gpdp.it  
Web: www.garanteprivacy.it

### 15.3 Risoluzione Controversie
- **Mediazione**: Disponibile per controversie non risolte
- **Arbitrato**: Secondo normative EU per dispute transfrontaliere
- **Giurisdizione**: Tribunali italiani per utenti residenti in Italia

---

**Documento redatto in conformità al Regolamento UE 2016/679 (GDPR) e normative nazionali applicabili.**

**Rayo Consulting - info@rayo.consulting**  
**Ultimo aggiornamento: 7 ottobre 2025**
