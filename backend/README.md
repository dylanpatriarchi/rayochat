# RayoChat Backend - Laravel Application

A modern Laravel 11 application that serves as the administrative backend for the RayoChat AI-powered business chat platform.

## 🎯 Overview

The Laravel backend provides a comprehensive management interface for administrators and site owners to manage AI-powered chat services. It handles user authentication, site management, business information storage, and API key generation.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Laravel Backend                          │
├─────────────────────────────────────────────────────────────┤
│  Controllers                                                │
│  ├── Auth/OtpController.php        # OTP Authentication     │
│  ├── Auth/LogoutController.php     # Session Management     │
│  ├── DashboardController.php       # Main Dashboard         │
│  ├── Admin/AdminDashboardController.php  # Admin Panel     │
│  └── SiteOwner/SiteOwnerDashboardController.php # Site Mgmt│
├─────────────────────────────────────────────────────────────┤
│  Models                                                     │
│  ├── User.php                      # User Management        │
│  ├── Site.php                      # Site Information       │
│  └── SiteInfoMD.php               # Business Info (Markdown)│
├─────────────────────────────────────────────────────────────┤
│  Views (Blade Templates)                                   │
│  ├── layouts/                      # Base Templates         │
│  ├── auth/                         # Authentication Views   │
│  ├── admin/                        # Admin Interface        │
│  └── site-owner/                   # Site Owner Interface   │
└─────────────────────────────────────────────────────────────┘
```

## ✨ Features

### Authentication System
- **OTP-based Login** - No passwords, secure 6-digit codes
- **Email Integration** - SMTP with Zoho support
- **Session Management** - Redis-backed sessions
- **Role-based Access** - Admin and Site Owner roles

### Site Management
- **Site Creation** - Add new client sites
- **API Key Generation** - Automatic unique key creation (`rc_s_xxxxx`)
- **Business Information** - Rich markdown editor for company details
- **Site Statistics** - Usage tracking and analytics

### User Interface
- **Stripe-inspired Design** - Modern, clean interface
- **Responsive Layout** - Mobile-first approach
- **Poppins Typography** - Professional font styling
- **Interactive Components** - Dynamic forms and modals

### Security Features
- **CSRF Protection** - Laravel's built-in security
- **Input Validation** - Server-side validation rules
- **SQL Injection Prevention** - Eloquent ORM protection
- **XSS Protection** - Blade template escaping

## 🚀 Installation

### Prerequisites
- Docker and Docker Compose
- Git

### Setup Steps

1. **Environment Configuration:**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

2. **Database Setup:**
   ```bash
   docker compose exec app php artisan migrate
   docker compose exec app php artisan db:seed --class=RoleSeeder
   ```

3. **Generate Application Key:**
   ```bash
   docker compose exec app php artisan key:generate
   ```

## 🔧 Configuration

### Environment Variables

```env
# Application
APP_NAME=RayoChat
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=rayochat
DB_USERNAME=rayochat
DB_PASSWORD=rayochat_password

# Redis
REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

# Mail (Zoho SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.eu
MAIL_PORT=587
MAIL_USERNAME=your-email@zoho.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@zoho.com"
MAIL_FROM_NAME="${APP_NAME}"

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120
```

### SMTP Configuration (Zoho)

1. **Create App Password:**
   - Login to Zoho Mail
   - Go to Security Settings
   - Generate App Password

2. **Update .env:**
   ```env
   MAIL_USERNAME=your-email@zoho.com
   MAIL_PASSWORD=your-app-password
   ```

## 👥 User Roles & Permissions

### Admin Role
- **Full System Access**
- **User Management** - Create/edit users
- **Site Management** - All sites across all users
- **System Configuration** - Global settings
- **Analytics** - System-wide reports

### Site Owner Role
- **Limited Access**
- **Own Sites Only** - Manage personal sites
- **Business Information** - Edit company details
- **API Key Management** - View and regenerate keys
- **Usage Statistics** - Site-specific analytics

## 🗄️ Database Schema

### Users Table
```sql
- id (Primary Key)
- name (String)
- email (String, Unique)
- email_verified_at (Timestamp)
- otp_code (String, Nullable)
- otp_expires_at (Timestamp, Nullable)
- otp_session_token (String, Nullable)
- last_login_at (Timestamp, Nullable)
- max_number_sites (Integer, Default: 3)
- created_at, updated_at (Timestamps)
```

### Sites Table
```sql
- id (Primary Key)
- user_id (Foreign Key → users.id)
- name (String)
- url (String)
- api_key (String, Unique) # Format: rc_s_xxxxx
- created_at, updated_at (Timestamps)
```

### Site Info MD Table
```sql
- id (Primary Key)
- site_id (Foreign Key → sites.id, Unique)
- markdown_content (Text)
- html_content (Text) # Auto-generated from markdown
- created_at, updated_at (Timestamps)
```

## 🎨 UI Components

### Design System
- **Primary Color:** Orange (#ff6b35)
- **Typography:** Poppins font family
- **Layout:** Card-based design
- **Spacing:** Consistent 8px grid system

### Key Components
- **Dashboard Cards** - Statistics and quick actions
- **Data Tables** - Sortable, paginated lists
- **Forms** - Validated input fields
- **Modals** - Confirmation dialogs
- **Notifications** - Success/error messages

## 🔄 API Integration

### Internal APIs
The Laravel backend doesn't expose public APIs but communicates internally with:

- **RAG Service** - Site validation via database
- **PostgreSQL** - Direct database access
- **Redis** - Caching and sessions

### Site API Keys
Generated API keys follow the format: `rc_s_` + 32 random characters
- Used by client sites to authenticate with RAG service
- Automatically generated on site creation
- Can be regenerated by site owners

## 🧪 Testing

### Running Tests
```bash
# Unit tests
docker compose exec app php artisan test

# Feature tests
docker compose exec app php artisan test --testsuite=Feature

# Specific test
docker compose exec app php artisan test tests/Feature/AuthTest.php
```

### Test Database
Tests use a separate SQLite database for isolation.

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper SMTP credentials
- [ ] Set up SSL certificates
- [ ] Configure Redis password
- [ ] Set secure session cookies
- [ ] Enable CSRF protection
- [ ] Configure proper CORS settings

### Optimization
```bash
# Cache configuration
docker compose exec app php artisan config:cache

# Cache routes
docker compose exec app php artisan route:cache

# Cache views
docker compose exec app php artisan view:cache

# Optimize autoloader
docker compose exec app composer install --optimize-autoloader --no-dev
```

## 🔧 Development

### Useful Commands
```bash
# Generate controller
docker compose exec app php artisan make:controller ExampleController

# Generate model
docker compose exec app php artisan make:model Example -m

# Generate migration
docker compose exec app php artisan make:migration create_examples_table

# Clear caches
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear

# Database operations
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan tinker
```

### Debugging
- **Laravel Telescope** - Available in development
- **Log Files** - `storage/logs/laravel.log`
- **Debug Bar** - Enabled in debug mode
- **Tinker** - Interactive PHP shell

## 🔍 Monitoring

### Logs
```bash
# Application logs
docker compose exec app tail -f storage/logs/laravel.log

# Web server logs
docker compose logs -f nginx

# Database logs
docker compose logs -f db
```

### Performance
- **Query Optimization** - Eloquent eager loading
- **Caching** - Redis for sessions and cache
- **Asset Optimization** - Vite for frontend assets

## 🛠️ Troubleshooting

### Common Issues

**Database Connection Failed:**
```bash
docker compose down -v
docker compose up -d
docker compose exec app php artisan migrate
```

**Email Not Sending:**
- Check SMTP credentials in `.env`
- Verify Zoho app password
- Check Laravel logs for SMTP errors

**Permission Denied:**
```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
docker compose exec app chmod -R 775 storage bootstrap/cache
```

**Session Issues:**
```bash
docker compose exec app php artisan session:table
docker compose exec app php artisan migrate
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Laravel Blade Templates](https://laravel.com/docs/blade)
- [PostgreSQL Documentation](https://www.postgresql.org/docs/)

## 🤝 Contributing

1. Follow PSR-12 coding standards
2. Write tests for new features
3. Update documentation
4. Use meaningful commit messages

## 📄 License

This Laravel backend is part of the proprietary RayoChat platform. See LICENSE.md for details.