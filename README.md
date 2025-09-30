# RayoChat - AI-Powered Customer Care Platform

![Version](https://img.shields.io/badge/version-1.0.0-orange)
![License](https://img.shields.io/badge/license-Proprietary-red)

RayoChat is a comprehensive AI-powered customer care platform that enables businesses to provide intelligent, automated customer support through a sleek web interface and embeddable widgets.

## 🌟 Features

### Platform Capabilities
- **Dual User Roles**: Admin and Site Owner profiles with distinct permissions
- **OTP Authentication**: Secure passwordless login via email
- **Document Management**: Upload and process PDF and Markdown files
- **RAG-Powered AI**: Retrieval-Augmented Generation for accurate, context-aware responses
- **Real-time Analytics**: Track conversations, ratings, and response times
- **Change Request System**: Admins can propose changes that Site Owners approve
- **Multi-tenant Architecture**: Support for multiple companies with isolated data

### Widget Integration
- **React Widget**: Modern, responsive chat widget for React applications
- **WordPress Plugin**: Easy integration for WordPress sites
- **Stripe-Inspired Design**: Clean, minimalist interface with custom branding
- **Rating System**: Collect user feedback on conversation quality
- **Mobile-Responsive**: Optimized for all screen sizes

### Technical Stack
- **Backend**: Laravel 11 (PHP 8.3)
- **RAG Service**: Python 3.11 with LangChain and OpenAI
- **Database**: PostgreSQL 16 with pgvector extension
- **Vector Store**: pgvector for semantic search
- **Queue System**: Redis for asynchronous processing
- **Containerization**: Docker & Docker Compose

## 🏗️ Architecture

```
┌─────────────────┐
│   Laravel API   │
│   (Backend)     │
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
┌───▼────┐  ┌▼──────────┐
│  Redis │  │PostgreSQL │
│ Queues │  │+ pgvector │
└────────┘  └───────────┘
    │            │
    │       ┌────▼────────┐
    │       │ Python RAG  │
    │       │  Service    │
    │       │ (LangChain) │
    │       └─────────────┘
    │
┌───▼──────────────────────┐
│  Widget Clients          │
│  - React Component       │
│  - WordPress Plugin      │
└──────────────────────────┘
```

## 📋 Prerequisites

Before you begin, ensure you have the following installed:
- Docker (v20.10+)
- Docker Compose (v2.0+)
- OpenAI API Key

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd rayochat
```

### 2. Environment Configuration

Copy the environment example file:

```bash
cp env.example .env
```

Edit `.env` and configure:

```bash
# Database
DB_PASSWORD=your_secure_password

# OpenAI API Key
OPENAI_API_KEY=sk-proj-your-openai-api-key

# Mail Configuration (for OTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rayochat.com
```

### 3. Generate Laravel Application Key

```bash
docker-compose run --rm backend php artisan key:generate
```

Update the `APP_KEY` in your `.env` file with the generated key.

### 4. Start the Platform

```bash
docker-compose up -d
```

This will start:
- **Laravel Backend** on `http://localhost:80`
- **Python RAG Service** on `http://localhost:8000`
- **PostgreSQL** on `localhost:5432`
- **Redis** on `localhost:6379`

### 5. Run Database Migrations

```bash
docker-compose exec backend php artisan migrate
```

### 6. Create Admin User

```bash
docker-compose exec backend php artisan tinker
```

Then run:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@rayochat.com',
    'role' => 'admin',
    'email_verified_at' => now(),
]);
```

## 📖 Usage Guide

### Admin Dashboard

1. Navigate to `http://localhost/auth/login`
2. Enter admin email and request OTP
3. Check email for 6-digit code
4. Access admin dashboard

**Admin capabilities:**
- Create and manage Site Owner accounts
- View analytics across all companies
- Monitor system-wide conversations
- Request changes to company information
- Activate/deactivate Site Owners

### Site Owner Dashboard

1. Login with Site Owner credentials
2. Access dashboard at `/site-owner/dashboard`

**Site Owner capabilities:**
- Manage company information
- Upload knowledge base documents (PDF, Markdown)
- View conversation analytics
- Download WordPress plugin
- Get API key for widget integration
- Approve/reject admin change requests

### Document Processing

Documents are processed asynchronously:

1. Site Owner uploads document
2. Document queued for processing
3. RAG service extracts text and creates embeddings
4. Document indexed in vector store
5. Status updates to "processed"

Monitor queue:

```bash
docker-compose logs -f queue
```

### Widget Integration

#### React Integration

```bash
cd widget-react
npm install
npm run build
```

Use in your React app:

```jsx
import RayoChatWidget from '@rayochat/widget';

function App() {
  return (
    <RayoChatWidget 
      apiKey="sk_your_api_key_here"
      apiUrl="http://localhost/api/widget"
      position="bottom-right"
      primaryColor="#FF6B35"
    />
  );
}
```

#### WordPress Integration

1. Navigate to `/site-owner/download-plugin`
2. Download `rayochat-wordpress.zip`
3. Install in WordPress: Plugins → Add New → Upload Plugin
4. Activate plugin
5. Go to Settings → RayoChat
6. Enter API key and configure

## 🔧 Configuration

### RAG Service Configuration

Edit `rag-service/config.py`:

```python
OPENAI_MODEL = "gpt-4-turbo-preview"
OPENAI_EMBEDDING_MODEL = "text-embedding-3-small"
CHUNK_SIZE = 1000
CHUNK_OVERLAP = 200
TOP_K_RESULTS = 5
```

### Laravel Configuration

Backend configuration in `backend/config/`:
- `services.php` - RAG service URL
- Database settings in `.env`

## 📊 Analytics

The platform tracks:
- **Conversations per hour**: Real-time conversation volume
- **Average rating**: Customer satisfaction (1-5 stars)
- **Response time**: Average AI response time in milliseconds
- **Document count**: Knowledge base size per company

Access analytics:
- **Admin**: `/admin/site-owners/{id}/analytics`
- **Site Owner**: `/site-owner/analytics`

## 🛠️ Development

### Running Tests

```bash
# Laravel tests
docker-compose exec backend php artisan test

# Python tests
docker-compose exec rag pytest
```

### Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f rag
docker-compose logs -f queue
```

### Rebuilding Containers

```bash
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

## 🔒 Security Considerations

- **API Keys**: Stored securely, never exposed in frontend
- **Email Hashing**: User emails hashed (SHA-256) for privacy
- **Company Isolation**: Each company has unique hash for routing
- **OTP Expiration**: Codes expire after 10 minutes
- **Rate Limiting**: Implement rate limiting for production
- **HTTPS**: Use SSL/TLS in production (configure reverse proxy)

## 🚀 Production Deployment

### VPS Deployment

1. **Server Requirements**:
   - 4GB RAM minimum
   - 2 CPU cores
   - 50GB storage
   - Ubuntu 22.04 LTS

2. **Install Docker**:
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
```

3. **Configure Domain**:
   - Point domain to server IP
   - Configure nginx reverse proxy
   - Install SSL with Let's Encrypt

4. **Update Environment**:
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

5. **Start Services**:
```bash
docker-compose -f docker-compose.prod.yml up -d
```

### Database Backups

```bash
# Backup
docker-compose exec postgres pg_dump -U rayochat_user rayochat > backup.sql

# Restore
docker-compose exec -T postgres psql -U rayochat_user rayochat < backup.sql
```

## 📁 Project Structure

```
rayochat/
├── backend/                  # Laravel application
│   ├── app/
│   │   ├── Http/Controllers/ # API & web controllers
│   │   ├── Models/          # Eloquent models
│   │   ├── Jobs/            # Queue jobs
│   │   └── Mail/            # Email templates
│   ├── database/migrations/ # Database migrations
│   ├── resources/views/     # Blade templates
│   └── routes/              # Route definitions
├── rag-service/             # Python RAG service
│   ├── services/            # Core services
│   │   ├── rag_engine.py   # LangChain implementation
│   │   └── database.py     # Database operations
│   ├── main.py             # FastAPI application
│   └── config.py           # Configuration
├── widget-react/            # React widget
│   └── src/
│       ├── RayoChatWidget.tsx
│       └── styles.css
├── wordpress-plugin/        # WordPress plugin
│   └── rayochat/
│       ├── admin/          # Admin interface
│       └── public/         # Public widget
├── docker-compose.yml       # Docker orchestration
└── README.md               # This file
```

## 🎨 Design System

**Color Palette:**
- Primary: `#FF6B35` (Orange)
- Background: `#FFFFFF` (White)
- Text: `#0A0A0A` (Black)
- Gray Scale: `#F9FAFB` to `#111827`

**Typography:**
- Font Family: Poppins
- Weights: 300, 400, 500, 600, 700

## 🐛 Troubleshooting

### Common Issues

**1. Database Connection Failed**
```bash
docker-compose down -v
docker-compose up -d
docker-compose exec backend php artisan migrate
```

**2. Queue Not Processing**
```bash
docker-compose restart queue
docker-compose logs queue
```

**3. RAG Service Not Responding**
```bash
docker-compose logs rag
# Check OpenAI API key in .env
```

**4. Embeddings Not Created**
```bash
# Ensure pgvector extension is enabled
docker-compose exec postgres psql -U rayochat_user -d rayochat -c "CREATE EXTENSION IF NOT EXISTS vector;"
```

## 🔄 Updates & Maintenance

### Updating Dependencies

```bash
# Laravel
docker-compose exec backend composer update

# Python
docker-compose exec rag pip install -r requirements.txt --upgrade

# React Widget
cd widget-react && npm update
```

### Database Maintenance

```bash
# Optimize tables
docker-compose exec postgres vacuumdb -U rayochat_user -d rayochat -z

# Check size
docker-compose exec postgres psql -U rayochat_user -d rayochat -c "SELECT pg_size_pretty(pg_database_size('rayochat'));"
```

## 📞 Support

For issues or questions related to this private installation, contact the project owner directly.

## ⚖️ License

This software is proprietary and confidential. See [LICENSE](LICENSE) for full terms.

**Copyright © 2025 RayoChat. All Rights Reserved.**

---

**Built with ❤️ using Laravel, Python, React, and AI**
