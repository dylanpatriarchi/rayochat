# Docker Configuration

This directory contains Docker configuration files for the RayoChat platform services.

## 📁 Structure

```
docker/
├── nginx/
│   └── default.conf        # Nginx web server configuration
└── php/
    └── local.ini          # PHP-FPM configuration overrides
```

## 🌐 Nginx Configuration

### File: `nginx/default.conf`

The Nginx configuration serves as a reverse proxy for the Laravel application, handling:

- **Static Asset Serving** - Direct serving of CSS, JS, images
- **PHP-FPM Integration** - Proxying PHP requests to the app container
- **Security Headers** - Basic security configurations
- **Gzip Compression** - Asset compression for better performance

### Key Features:

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static assets
    location ~* \.(css|js|gif|ico|jpeg|jpg|png|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### Configuration Details:

- **Document Root:** `/var/www/public` (Laravel public directory)
- **PHP-FPM Backend:** `app:9000` (Docker service communication)
- **Asset Caching:** 1 year expiration for static files
- **Security:** XSS protection, frame options, content type sniffing prevention

## 🐘 PHP Configuration

### File: `php/local.ini`

Custom PHP configuration overrides for the Laravel application:

```ini
# File upload limits
upload_max_filesize = 40M
post_max_size = 40M

# Memory and execution limits
memory_limit = 256M
max_execution_time = 300
max_input_time = 300

# Session configuration
session.gc_maxlifetime = 7200
session.cookie_lifetime = 7200

# Error reporting (development)
display_errors = On
display_startup_errors = On
log_errors = On
error_log = /var/log/php_errors.log

# Timezone
date.timezone = Europe/Rome

# OPcache optimization
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 2
opcache.fast_shutdown = 1
```

### Configuration Highlights:

- **File Uploads:** 40MB limit for business document uploads
- **Memory:** 256MB for handling large datasets
- **Sessions:** 2-hour lifetime for user sessions
- **OPcache:** Enabled for better PHP performance
- **Timezone:** Set to Europe/Rome for Italian localization

## 🔧 Customization

### Nginx Customization

To modify Nginx settings:

1. **Edit Configuration:**
   ```bash
   vim docker/nginx/default.conf
   ```

2. **Restart Service:**
   ```bash
   docker compose restart nginx
   ```

### Common Nginx Modifications:

**Enable SSL/HTTPS:**
```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
}
```

**Add Rate Limiting:**
```nginx
http {
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    
    server {
        location /api/ {
            limit_req zone=api burst=20 nodelay;
        }
    }
}
```

### PHP Customization

To modify PHP settings:

1. **Edit Configuration:**
   ```bash
   vim docker/php/local.ini
   ```

2. **Restart Service:**
   ```bash
   docker compose restart app
   ```

### Common PHP Modifications:

**Production Settings:**
```ini
# Disable error display
display_errors = Off
display_startup_errors = Off

# Increase performance
opcache.validate_timestamps = 0
opcache.max_accelerated_files = 10000

# Security
expose_php = Off
allow_url_fopen = Off
```

**Development Settings:**
```ini
# Enable debugging
xdebug.mode = debug
xdebug.start_with_request = yes
xdebug.client_host = host.docker.internal
xdebug.client_port = 9003

# Verbose error reporting
error_reporting = E_ALL
display_errors = On
```

## 🚀 Performance Optimization

### Nginx Optimizations

**Enable Gzip Compression:**
```nginx
gzip on;
gzip_vary on;
gzip_min_length 1024;
gzip_types
    text/plain
    text/css
    text/xml
    text/javascript
    application/javascript
    application/xml+rss
    application/json;
```

**Browser Caching:**
```nginx
location ~* \.(css|js|gif|ico|jpeg|jpg|png|svg)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    add_header Vary Accept-Encoding;
}
```

### PHP Optimizations

**OPcache Tuning:**
```ini
opcache.memory_consumption = 256
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0  # Production only
opcache.save_comments = 0
opcache.fast_shutdown = 1
```

## 🛡️ Security Considerations

### Nginx Security

**Hide Server Information:**
```nginx
server_tokens off;
```

**Security Headers:**
```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Content-Security-Policy "default-src 'self'" always;
```

**Block Sensitive Files:**
```nginx
location ~ /\.(ht|git|env) {
    deny all;
    return 404;
}

location ~ /(vendor|storage|bootstrap/cache) {
    deny all;
    return 404;
}
```

### PHP Security

**Disable Dangerous Functions:**
```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

**File Upload Security:**
```ini
file_uploads = On
upload_max_filesize = 40M
max_file_uploads = 20
upload_tmp_dir = /tmp
```

## 🔍 Monitoring

### Nginx Monitoring

**Access Logs:**
```bash
docker compose logs nginx
```

**Status Module:**
```nginx
location /nginx_status {
    stub_status on;
    allow 127.0.0.1;
    deny all;
}
```

### PHP Monitoring

**FPM Status:**
```nginx
location ~ ^/(status|ping)$ {
    fastcgi_pass app:9000;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
    allow 127.0.0.1;
    deny all;
}
```

**Error Logs:**
```bash
docker compose exec app tail -f /var/log/php_errors.log
```

## 🔧 Troubleshooting

### Common Issues

**502 Bad Gateway:**
- Check if PHP-FPM is running: `docker compose ps app`
- Verify PHP-FPM socket: `docker compose exec app netstat -ln | grep 9000`

**File Permission Issues:**
```bash
docker compose exec app chown -R www-data:www-data /var/www/storage
docker compose exec app chmod -R 775 /var/www/storage
```

**Upload Size Limits:**
- Check `upload_max_filesize` in PHP config
- Verify `client_max_body_size` in Nginx config

### Debug Mode

**Enable Nginx Debug:**
```nginx
error_log /var/log/nginx/error.log debug;
```

**Enable PHP Debug:**
```ini
log_errors = On
error_log = /var/log/php_errors.log
error_reporting = E_ALL
```

## 📄 License

These Docker configurations are part of the proprietary RayoChat platform. See LICENSE.md for details.
