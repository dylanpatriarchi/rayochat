# RayoChat - Quick Setup Guide

This guide will get you up and running with RayoChat in under 10 minutes.

## ⚡ Quick Start

### 1. Prerequisites Check

Ensure you have:
- [x] Docker installed and running
- [x] Docker Compose installed
- [x] OpenAI API key ready
- [x] SMTP credentials for email (Gmail, SendGrid, etc.)

### 2. Initial Setup

```bash
# Navigate to project directory
cd rayochat

# Copy environment file
cp env.example .env

# Edit .env with your credentials
nano .env
```

### 3. Configure Environment Variables

**Required Settings:**

```bash
# Database Password (set a strong password)
DB_PASSWORD=your_super_secure_password_here

# OpenAI Configuration
OPENAI_API_KEY=sk-proj-your_actual_openai_api_key

# Email Configuration (example with Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your.email@gmail.com
MAIL_PASSWORD=your_app_specific_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### 4. Generate Application Key

```bash
# Generate Laravel app key
docker-compose run --rm backend php artisan key:generate
```

Copy the generated key and add it to `.env`:
```bash
APP_KEY=base64:your_generated_key_here
```

### 5. Start All Services

```bash
# Start Docker containers
docker-compose up -d

# Wait for services to be ready (about 30 seconds)
# Check status
docker-compose ps
```

### 6. Initialize Database

```bash
# Run migrations
docker-compose exec backend php artisan migrate

# Verify tables were created
docker-compose exec postgres psql -U rayochat_user -d rayochat -c "\dt"
```

### 7. Create First Admin User

```bash
docker-compose exec backend php artisan tinker
```

In the tinker console:
```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@yourdomain.com',
    'role' => 'admin',
    'email_verified_at' => now(),
]);
exit
```

### 8. Verify Installation

Open your browser and visit:
- **Main Application**: http://localhost
- **Login Page**: http://localhost/auth/login
- **RAG Service Health**: http://localhost:8000/health

### 9. First Login

1. Go to http://localhost/auth/login
2. Enter: `admin@yourdomain.com`
3. Check your email for OTP code
4. Enter the 6-digit code
5. You're in! 🎉

## 🎯 Next Steps

### Create Your First Site Owner

1. In Admin Dashboard, go to "Site Owners"
2. Click "Create New Site Owner"
3. Fill in details:
   - Name
   - Email
   - Company Name
4. Submit

### Configure Company Knowledge Base

1. Login as Site Owner
2. Go to "Documents"
3. Upload PDF or Markdown files
4. Wait for processing (check status)

### Get Widget API Key

1. In Site Owner dashboard
2. Go to "API Key"
3. Copy the key
4. Use in React widget or WordPress plugin

## 🔍 Verify Everything Works

### Test RAG Service

```bash
# Check RAG service is responding
curl http://localhost:8000/health

# Expected response:
{
  "status": "healthy",
  "timestamp": "2025-09-30T...",
  "services": {
    "database": "connected",
    "openai": "configured",
    "rag_engine": "ready"
  }
}
```

### Test Queue Processing

```bash
# Watch queue logs
docker-compose logs -f queue

# Upload a document through the web interface
# You should see processing logs
```

### Test Widget API

```bash
# Replace with actual API key from database
curl -X POST http://localhost/api/widget/chat \
  -H "Content-Type: application/json" \
  -d '{
    "api_key": "sk_your_actual_api_key",
    "question": "Hello, how can you help me?"
  }'
```

## 🛠️ Common Issues

### Issue: "Connection refused" to database

**Solution:**
```bash
docker-compose down
docker-compose up -d postgres
# Wait 10 seconds
docker-compose up -d
```

### Issue: No OTP email received

**Check:**
1. SMTP credentials are correct in `.env`
2. "Less secure app access" enabled (Gmail)
3. Check spam folder
4. View logs: `docker-compose logs backend | grep mail`

### Issue: Documents not processing

**Solution:**
```bash
# Restart queue worker
docker-compose restart queue

# Check for errors
docker-compose logs queue
```

### Issue: RAG service errors

**Check:**
```bash
# View RAG logs
docker-compose logs rag

# Verify OpenAI key is set
docker-compose exec rag env | grep OPENAI_API_KEY

# Restart RAG service
docker-compose restart rag
```

## 📊 Monitoring

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f backend
docker-compose logs -f rag
docker-compose logs -f queue

# Last 100 lines
docker-compose logs --tail=100 backend
```

### Check Resource Usage

```bash
docker stats
```

### Database Shell

```bash
docker-compose exec postgres psql -U rayochat_user rayochat
```

## 🔄 Maintenance Commands

### Restart Everything

```bash
docker-compose restart
```

### Stop Everything

```bash
docker-compose down
```

### Clean Rebuild

```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```

### Backup Database

```bash
docker-compose exec postgres pg_dump -U rayochat_user rayochat > backup_$(date +%Y%m%d).sql
```

### Clear Queue

```bash
docker-compose exec redis redis-cli FLUSHALL
```

## 🎓 Learning Resources

- **Laravel Documentation**: https://laravel.com/docs
- **LangChain Documentation**: https://python.langchain.com
- **FastAPI Documentation**: https://fastapi.tiangolo.com
- **pgvector Documentation**: https://github.com/pgvector/pgvector

## 📞 Getting Help

If you encounter issues:

1. Check logs first: `docker-compose logs [service]`
2. Verify environment variables in `.env`
3. Ensure all ports are available (80, 5432, 6379, 8000)
4. Check Docker resources (RAM, disk space)

---

**You're all set! Happy building with RayoChat! 🚀**
