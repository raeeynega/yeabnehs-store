#!/bin/bash
set -e

cd /var/www/yeabnehs-store

# Parse DATABASE_URL into individual DB_* vars if it's set and individual vars aren't
if [ -n "$DATABASE_URL" ] && [ -z "$DB_HOST" ]; then
  eval "$(php -r "
    \$url = parse_url(getenv('DATABASE_URL'));
    if (\$url) {
      echo 'export DB_HOST=' . (\$url['host'] ?? '127.0.0.1') . PHP_EOL;
      echo 'export DB_PORT=' . (\$url['port'] ?? '5432') . PHP_EOL;
      echo 'export DB_DATABASE=' . ltrim(\$url['path'] ?? '/laravel', '/') . PHP_EOL;
      echo 'export DB_USERNAME=' . (\$url['user'] ?? 'postgres') . PHP_EOL;
      echo 'export DB_PASSWORD=' . (\$url['pass'] ?? '') . PHP_EOL;
    }
  ")"
fi

# Create .env from environment variables
cat > .env <<EOF
APP_NAME="${APP_NAME:-YeaBneh Store}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=${LOG_CHANNEL:-stack}
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error
DB_CONNECTION=${DB_CONNECTION:-pgsql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-5432}
DB_DATABASE=${DB_DATABASE:-laravel}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}
SESSION_DRIVER=${SESSION_DRIVER:-database}
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
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

# Obfuscate translations (two-step: gzip+base64, xor+base64)
php artisan translations:obfuscate 2>&1 || echo "Obfuscation failed, continuing..."

# Cache config
php artisan config:cache 2>&1 || true
php artisan route:cache 2>&1 || true
php artisan view:cache 2>&1 || true

# Verify audit chain integrity on startup (ISO 27001 A.12.4.1)
php artisan audit:verify --stats 2>&1 || echo "Audit chain verification skipped"

# Create log directories for supervisor
mkdir -p /var/log/supervisor

# Start PHP-FPM + Nginx via supervisor (entrypoint.sh is no longer the CMD)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
