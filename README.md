# RayoChat Backend

A Laravel 12 application with Docker setup, featuring OTP-based authentication and role-based access control.

## Features

- **Laravel 12** with PHP 8.3
- **PostgreSQL** database
- **Redis** for caching
- **Docker Compose** setup
- **OTP Authentication** (no passwords)
- **Role-based Access Control** using Spatie Laravel Permission
- **Stripe-inspired Design** with Poppins font
- **SMTP Zoho** integration for email

## Quick Start

1. **Clone and navigate to the project:**
   ```bash
   cd rayochat
   ```

2. **Start the Docker containers:**
   ```bash
   docker compose up -d
   ```

3. **Access the application:**
   - Open http://localhost:8001 in your browser
   - You'll be redirected to the login page

## Default Users

The application comes with two pre-configured users:

- **Admin User:**
  - Email: `info@rayo.consulting`
  - Role: `admin`
  - Permissions: All permissions

- **Site Owner:**
  - Email: `owner@rayo.consulting`
  - Role: `site-owner`
  - Permissions: View reports only

## Authentication Flow

1. Enter your email address on the login page
2. Click "Send OTP Code"
3. Check your email for the 6-digit OTP code
4. Enter the OTP code on the verification page
5. You'll be logged in and redirected to the dashboard

## SMTP Configuration

To enable email sending, update the following in `backend/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=your-email@zoho.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS="your-email@zoho.com"
```

## Docker Services

- **App:** Laravel application (PHP 8.3-FPM)
- **Nginx:** Web server (Port 8001)
- **PostgreSQL:** Database
- **Redis:** Cache and sessions

## Project Structure

```
rayochat/
├── backend/                 # Laravel application
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── Auth/OtpController.php
│   │   │   └── DashboardController.php
│   │   └── Models/User.php
│   ├── resources/views/
│   │   ├── layouts/app.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   └── verify-otp.blade.php
│   │   └── dashboard.blade.php
│   └── routes/web.php
├── docker/
│   ├── nginx/default.conf
│   └── php/local.ini
├── docker-compose.yml
└── Dockerfile
```

## Development

### Running Commands

To run Laravel commands inside the Docker container:

```bash
docker exec rayochat_app php artisan [command]
```

### Database Migrations

```bash
docker exec rayochat_app php artisan migrate
```

### Seeding

```bash
docker exec rayochat_app php artisan db:seed --class=RoleSeeder
```

## Design System

The application uses a Stripe-inspired design with:

- **Colors:** White background with orange accents (#ff6b35)
- **Typography:** Poppins font family
- **Components:** Clean cards, gradients, and modern UI elements
- **Responsive:** Mobile-first design approach

## Security Features

- OTP-based authentication (no password storage)
- CSRF protection
- Role-based access control
- Secure session management
- Input validation and sanitization

## Environment Variables

Key environment variables in `backend/.env`:

```env
APP_NAME=RayoChat
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8001

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=rayochat
DB_USERNAME=rayochat
DB_PASSWORD=rayochat_password

REDIS_HOST=redis
REDIS_PORT=6379
```

## Troubleshooting

### Database Connection Issues
If you encounter database connection issues:
```bash
docker compose down -v
docker compose up -d
```

### Port Conflicts
If port 8001 is already in use, modify `docker-compose.yml`:
```yaml
ports:
  - "8002:80"  # Change 8001 to 8002
```

### Email Not Sending
- Verify SMTP credentials in `.env`
- Check Zoho app password settings
- For testing, OTP codes are logged in Laravel logs

## License

This project is proprietary software.
