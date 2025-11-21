# PIKOY - Deployment Guide for Dokploy

## 📋 Prerequisites
- Repository sudah di-push ke GitHub: https://github.com/ChaisarAbi/pikoy.git
- VPS dengan IP: 45.127.32.237
- Domain: pikoy.aventra.my.id (configured in Cloudflare)

## 🚀 Deployment Steps in Dokploy

### 1. Create New Project in Dokploy
- **Repository URL**: `https://github.com/ChaisarAbi/pikoy.git`
- **Branch**: `main`
- **Dockerfile Path**: `Dockerfile`

### 2. Environment Variables Setup
**IMPORTANT**: Set these environment variables in Dokploy:

```
APP_NAME=Diabetes Prediction System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://pikoy.aventra.my.id

# Database Configuration (Required)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pikoy
DB_USERNAME=pikoy_user
DB_PASSWORD=your_secure_password_here

# Optional: Use SQLite if MySQL not available
# DB_CONNECTION=sqlite
# DB_DATABASE=/var/www/database/database.sqlite

# Other settings
LOG_CHANNEL=stack
LOG_LEVEL=info
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

### 3. Database Setup (Required)
You need to setup MySQL database on your VPS:

```bash
# SSH to your VPS (45.127.32.237)
ssh root@45.127.32.237

# Install MySQL if not installed
sudo apt update
sudo apt install mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p

# In MySQL console:
CREATE DATABASE pikoy;
CREATE USER 'pikoy_user'@'localhost' IDENTIFIED BY 'your_secure_password_here';
GRANT ALL PRIVILEGES ON pikoy.* TO 'pikoy_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Manual Database Migration (After Deployment)
After the container is running, execute migrations manually:

```bash
# Get into the running container
docker exec -it <container_name> bash

# Run migrations
php artisan migrate --force
php artisan db:seed --force
```

### 5. Domain Configuration in Cloudflare
- **Type**: A
- **Name**: pikoy.aventra.my.id
- **IPv4 address**: 45.127.32.237
- **Proxy status**: DNS only (disable Cloudflare proxy for now)

### 6. SSL Certificate (Optional)
After deployment, you can enable SSL:
- Use Let's Encrypt on your VPS
- Or enable Cloudflare SSL

## 🔧 Troubleshooting

### Common Issues:

1. **502 Bad Gateway**
   - Check if Nginx and PHP-FPM are running
   - Verify environment variables are set correctly
   - Check container logs in Dokploy

2. **Database Connection Refused**
   - Ensure MySQL is running on VPS
   - Verify database credentials in environment variables
   - Check if database exists

3. **Migration Errors**
   - Run migrations manually after deployment
   - Ensure database user has proper permissions

### Container Logs Check:
```bash
# Check container logs in Dokploy
docker logs <container_name>

# Expected logs:
# - Supervisor started
# - PHP-FPM running
# - Nginx running
# - Migration attempts (may fail initially)
```

## 📞 Support
If you encounter issues:
1. Check container logs in Dokploy
2. Verify environment variables
3. Ensure database is accessible
4. Check domain DNS configuration

## ✅ Success Indicators
- Application accessible at: https://pikoy.aventra.my.id
- API endpoints responding
- Dashboard loading without errors
- Database migrations completed successfully
