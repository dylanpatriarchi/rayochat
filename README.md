# RayoChat - AI-Powered Business Chat Platform

A comprehensive platform that combines Laravel backend management with an intelligent RAG (Retrieval-Augmented Generation) service for AI-powered customer support.

## 🏗️ System Architecture

RayoChat consists of four main components:

1. **Laravel Backend** - Admin dashboard and site management
2. **RAG Service** - AI-powered chat service using OpenAI and LangChain
3. **WordPress Plugin** - Chat widget for client websites
4. **Docker Infrastructure** - Containerized deployment with PostgreSQL and Redis

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Laravel App   │    │   RAG Service   │    │ WordPress Plugin│
│   (Port 8001)   │    │   (Port 8002)   │    │                 │
│                 │    │                 │    │                 │
│ • Admin Panel   │    │ • OpenAI API    │◄───┤ • Chat Widget   │
│ • Site Mgmt     │    │ • LangChain     │    │ • iMessage UI   │
│ • User Roles    │    │ • Vector Store  │    │ • API Client    │
│ • API Keys      │    │ • Rate Limiting │    │ • WhatsApp Icon │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         └───────────────────────┼───────────────────────┘
                                 │
                    ┌─────────────────┐              ┌─────────────────┐
                    │   PostgreSQL    │              │     Redis       │
                    │                 │              │                 │
                    │ • Sites Data    │              │ • Caching       │
                    │ • Users         │              │ • Rate Limits   │
                    │ • Business Info │              │ • Sessions      │
                    └─────────────────┘              └─────────────────┘
```

## 🚀 Quick Start

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd rayochat
   ```

2. **Start all services:**
   ```bash
   docker compose up -d
   ```

3. **Configure OpenAI API key:**
   ```bash
   # Edit rag/.env
   OPENAI_API_KEY=sk-your-openai-key-here
   docker compose restart rag
   ```

4. **Access the services:**
   - Laravel Admin: http://localhost:8001
   - RAG Service: http://localhost:8002/health

## 📋 Features

### Laravel Backend
- **OTP Authentication** - Passwordless login system
- **Role-based Access Control** - Admin and Site Owner roles
- **Site Management** - Create and manage client sites
- **Business Information Editor** - Markdown editor for company info
- **API Key Generation** - Automatic API key creation for sites

### RAG Service
- **AI-Powered Responses** - OpenAI GPT integration
- **Vector Search** - ChromaDB for semantic search
- **Rate Limiting** - 30 requests/minute, 500/hour
- **Caching** - Redis-based response caching
- **Security** - Input validation, SQL injection protection
- **Monitoring** - Health checks and usage tracking

## 🔧 Configuration

### Environment Files

**Backend (.env):**
```env
APP_NAME=RayoChat
DB_CONNECTION=pgsql
DB_HOST=db
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.eu
```

**RAG Service (.env):**
```env
DATABASE_URL=postgresql://rayochat:rayochat_password@db:5432/rayochat
OPENAI_API_KEY=sk-your-key-here
REDIS_URL=redis://redis:6379/1
```

## 🔑 Default Users

- **Admin:** `info@rayo.consulting` (Full access)
- **Site Owner:** `owner@rayo.consulting` (Site management)

## 🛡️ Security Features

- **Input Sanitization** - XSS and injection prevention
- **API Key Validation** - Secure site authentication
- **Rate Limiting** - DDoS protection
- **CORS Configuration** - Cross-origin security
- **Security Headers** - XSS, clickjacking protection
- **SSL/TLS Support** - Encrypted connections

## 📊 Usage Example

### Creating a Site
1. Login to Laravel admin panel
2. Navigate to "Site Management"
3. Create new site with business information
4. Copy the generated API key

### Using the Chat API
```javascript
const response = await fetch('http://localhost:8002/ask', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    message: "What services do you offer?",
    api_key: "rc_s_xxxxxxxxxxxxxxxxxxxxx"
  })
});
```

## 🐳 Docker Services

- **app** - Laravel PHP-FPM application
- **nginx** - Web server (Port 8001)
- **db** - PostgreSQL database
- **redis** - Cache and session storage
- **rag** - Python RAG service (Port 8002)

## 📁 Project Structure

```
rayochat/
├── backend/                 # Laravel application
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── Services/
│   ├── resources/views/
│   └── routes/
├── rag/                     # Python RAG service
│   ├── app/
│   │   ├── models/
│   │   ├── services/
│   │   └── main.py
│   ├── requirements.txt
│   └── Dockerfile
├── wordpress-plugin/        # WordPress integration
│   └── rayochat-widget/     # Chat widget plugin
│       ├── assets/          # CSS and JavaScript
│       ├── rayochat-widget.php  # Main plugin file
│       └── README.md        # Plugin documentation
├── docker/                  # Docker configuration
│   ├── nginx/
│   └── php/
├── docker-compose.yml
└── README.md
```

## 🔌 WordPress Plugin

### Chat Widget Features
- **iMessage-style UI** - Modern, familiar interface
- **WhatsApp-style button** - Green floating action button
- **Real-time chat** - Instant AI responses
- **Mobile responsive** - Optimized for all devices
- **Customizable** - Colors, position, messages
- **Secure** - XSS protection, input validation

### Installation
1. Copy plugin to WordPress: `wp-content/plugins/rayochat-widget/`
2. Activate plugin in WordPress Admin
3. Configure API key in Settings > RayoChat Widget
4. Customize appearance and messages

### Configuration
- **API Key**: Get from RayoChat admin panel (format: `rc_s_...`)
- **Widget Title**: Displayed in chat header
- **Welcome Message**: First message shown to users
- **Position**: Bottom right or left
- **Color**: Button color (default: WhatsApp green)

## 🔧 Development

### Running Commands
```bash
# Laravel commands
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker

# RAG service logs
docker logs rayochat_rag

# Database access
docker compose exec db psql -U rayochat -d rayochat

# WordPress plugin development
cd wordpress-plugin/rayochat-widget/
# Edit files and test on WordPress site
```

### Testing the RAG Service
```bash
curl -X POST http://localhost:8002/ask \
  -H "Content-Type: application/json" \
  -d '{"message": "Hello", "api_key": "rc_s_xxxxx"}'
```

## 🚨 Troubleshooting

### Common Issues
- **Port conflicts:** Modify ports in docker-compose.yml
- **Database connection:** Run `docker compose down -v && docker compose up -d`
- **RAG service errors:** Check OpenAI API key configuration
- **Email issues:** Verify SMTP settings in backend/.env

### Health Checks
- Laravel: http://localhost:8001
- RAG Service: http://localhost:8002/health
- Database: `docker compose exec db pg_isready`

## 📈 Monitoring

- **Application Logs:** `docker compose logs -f`
- **RAG Usage:** Check Redis for usage statistics
- **Database Performance:** Monitor PostgreSQL logs
- **API Response Times:** Built-in FastAPI metrics

## 🔄 Updates

To update the system:
```bash
git pull
docker compose build
docker compose up -d
```

## 📞 Support

For technical support or questions about the RayoChat platform, please refer to the individual service documentation in their respective directories.

## 📄 License

This project is proprietary software. See LICENSE.md for details.