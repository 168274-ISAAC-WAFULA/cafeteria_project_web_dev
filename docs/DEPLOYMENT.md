# Deployment Guide

## Production Setup

### Server Requirements
- PHP 8.1+ with extensions: mbstring, pdo_mysql, curl, json
- MySQL 5.7 or MariaDB 10.3+
- Nginx or Apache
- SSL certificate

### Deployment Steps

1. **Clone repository**
   ```bash
   git clone https://github.com/company/taskflow.git
   cd taskflow
   ```

2. **Install dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install && npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   # Edit .env with production values
   ```

4. **Database migration**
   ```bash
   php artisan migrate --force
   ```

5. **Set permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name taskflow.example.com;
    root /var/www/taskflow/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
