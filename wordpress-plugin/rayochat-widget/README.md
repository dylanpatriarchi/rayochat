# RayoChat Widget - WordPress Plugin

An intelligent AI chat widget for WordPress with modern iMessage-style design and integration with the RayoChat RAG system.

## 🚀 Features

### 💬 Intelligent Chat
- **Conversational AI**: Smart responses based on business information
- **Real-time**: Smooth chat with typing indicators
- **Conversation Memory**: Maintains conversation context

### 🎨 Modern Design
- **iMessage Style**: Familiar and intuitive interface
- **Responsive**: Optimized for desktop and mobile
- **Customizable**: Configurable colors and position
- **WhatsApp Icon**: Recognizable green button

### 🛡️ Security & Performance
- **Input Validation**: XSS protection and sanitization
- **Rate Limiting**: Automatic API limit management
- **Error Handling**: User-friendly error messages
- **Offline Support**: Internet connection management

### ⚙️ Easy Configuration
- **Admin Panel**: Simple configuration from WordPress
- **API Key**: Secure integration with RayoChat
- **Customization**: Configurable messages and appearance

## 📦 Installation

### 1. Plugin Upload
```bash
# Copy the plugin folder
cp -r rayochat-widget /path/to/wordpress/wp-content/plugins/

# Or create a ZIP and upload via WordPress Admin
zip -r rayochat-widget.zip rayochat-widget/
```

### 2. Activation
1. Go to **Plugins > Installed Plugins** in your WordPress Admin
2. Find "RayoChat Widget" and click **Activate**

### 3. Configuration
1. Go to **Settings > RayoChat Widget**
2. Enter your **API Key** (format: `rc_s_...`)
3. Customize title, welcome message and appearance
4. Save settings

## 🔧 Configuration

### API Key
- Get your API Key from the RayoChat dashboard
- Required format: `rc_s_` followed by 32 alphanumeric characters
- API Key is securely stored in WordPress database

### Customization

#### Appearance
- **Widget Title**: Name shown in chat header
- **Welcome Message**: First message shown to user
- **Position**: Bottom right or left
- **Color**: Button color (default: WhatsApp green #25D366)

#### Behavior
- **Enable Widget**: Activate/deactivate widget on site
- **Timeout**: Automatic request timeout handling (30 seconds)

## 🎯 Usage

### For Visitors
1. **Opening**: Click the chat icon in bottom right
2. **Conversation**: Write questions in natural language
3. **Responses**: Receive intelligent responses based on business info
4. **Closing**: Click X or click outside chat

### Keyboard Shortcuts
- **Enter**: Send message
- **Escape**: Close chat
- **Tab**: Accessible navigation

## 🔌 API Integration

### Endpoint RAG
```javascript
// Il plugin comunica con:
POST http://localhost:8002/ask

// Payload:
{
    "message": "Domanda dell'utente",
    "api_key": "rc_s_...",
    "conversation_id": "conv_..."
}
```

### Error Handling
- **Timeout**: 30 seconds per request
- **Retry Logic**: Automatic temporary error handling
- **Fallback**: User-friendly error messages

## 🎨 Advanced Customization

### Custom CSS
```css
/* Customize widget color */
.rayochat-toggle {
    background: linear-gradient(135deg, #your-color 0%, #your-color-2 100%);
}

/* Customize messages */
.rayochat-message-bot .rayochat-message-content {
    background: #your-background;
    color: #your-text-color;
}
```

### JavaScript Hooks
```javascript
// Access widget API
window.RayoChat.open();           // Open chat
window.RayoChat.close();          // Close chat
window.RayoChat.sendMessage(msg); // Send message
window.RayoChat.isOpen();         // Chat state
```

## 📱 Responsive Design

### Desktop
- Widget 380x500px
- Posizionamento fisso
- Animazioni fluide

### Mobile
- Full-width responsive
- Altezza adattiva (70vh)
- Touch-friendly

### Tablet
- Dimensioni ottimizzate
- Orientamento supportato

## ♿ Accessibilità

### WCAG 2.1 Compliance
- **Screen Reader**: Supporto completo
- **Keyboard Navigation**: Navigazione da tastiera
- **High Contrast**: Supporto modalità alto contrasto
- **Reduced Motion**: Rispetta preferenze animazioni

### ARIA Labels
- Pulsanti etichettati correttamente
- Live regions per messaggi
- Ruoli semantici appropriati

## 🔍 Debug e Troubleshooting

### Console Logs
```javascript
// Abilita debug mode
localStorage.setItem('rayochat_debug', 'true');

// Controlla eventi
console.log('RayoChat Events enabled');
```

### Common Errors

#### Invalid API Key
```
Error: "Invalid API key format"
Solution: Check format rc_s_[32 chars]
```

#### Request Timeout
```
Error: "Request took too long"
Solution: Check connection and RAG service status
```

#### Widget Not Appearing
```
Problem: Widget not visible
Solution: 
1. Verify API Key is configured
2. Check that widget is enabled
3. Check for CSS/JS conflicts
```

## 🚀 Performance

### Optimizations
- **Lazy Loading**: Scripts loaded only when needed
- **Caching**: Server-side cached responses
- **Minification**: CSS/JS optimized for production
- **CDN Ready**: Compatible with WordPress CDN

### Metrics
- **First Load**: < 100KB total
- **Response Time**: < 5 seconds for AI response
- **Memory Usage**: < 2MB footprint

## 🔒 Sicurezza

### Implemented Protections
- **XSS Prevention**: Input/output sanitization
- **CSRF Protection**: WordPress nonce
- **SQL Injection**: Prepared statements
- **Rate Limiting**: Handled server-side RAG

### Best Practices
- API Key encrypted in database
- Strict user input validation
- Secure error handling (no sensitive info)
- Appropriate security headers

## 📊 Analytics

### Tracked Events
- `chat_opened`: Widget opened
- `chat_closed`: Widget closed  
- `message_sent`: Message sent
- `message_received`: Response received
- `message_error`: Message error
- `api_error`: API error

### Google Analytics
```javascript
// Auto-integrazione se gtag presente
gtag('event', 'chat_opened', {
    'event_category': 'RayoChat'
});
```

## 🔄 Aggiornamenti

### Versioning
- **Semantic Versioning**: MAJOR.MINOR.PATCH
- **Backward Compatibility**: Garantita per minor updates
- **Migration Scripts**: Automatici per major updates

### Changelog
- **v1.0.0**: Release iniziale con tutte le funzionalità base

## 🤝 Supporto

### Documentazione
- README completo
- Inline code comments
- WordPress Codex compliance

### Contatti
- **Email**: support@rayo.consulting
- **Website**: https://rayo.consulting
- **Documentation**: Disponibile nel plugin

## 📄 Licenza

**Proprietaria** - Tutti i diritti riservati a Dylan Patriarchi / Rayo Consulting.

È vietata la copia, distribuzione o modifica senza autorizzazione esplicita.

---

**RayoChat Widget v1.0.0** - Powered by Rayo Consulting 🚀
