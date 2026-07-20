#!/bin/bash
set -e

cd /var/www/yeabnehs-store

# Create .env from environment variables
cat > .env <<EOF
APP_NAME="${APP_NAME:-YeaBneh Store}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost:8000}
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=${DB_CONNECTION:-pgsql}
DATABASE_URL=${DATABASE_URL}
SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}
CACHE_STORE=${CACHE_STORE:-database}
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@yeabnehsstore.com"
MAIL_FROM_NAME="YeaBneh Store"
EOF

# Fix permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Run migrations
php artisan migrate --force 2>&1 || echo "Migration failed, continuing..."

# Seed database
php artisan db:seed --force 2>&1 || echo "Seed failed, continuing..."

# Cache config
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
