#!/bin/bash
# ============================================
# YeaBneh Store - Oracle Cloud Free Tier Setup
# Run this on a fresh Ubuntu 22.04/24.04 ARM instance
# ============================================

set -e

echo "=========================================="
echo "  YeaBneh Store - Oracle Cloud Setup"
echo "=========================================="

# --- 1. System Update ---
echo "[1/10] Updating system..."
sudo apt update && sudo apt upgrade -y

# --- 2. Install PHP 8.2 + Extensions ---
echo "[2/10] Installing PHP 8.2..."
sudo apt install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml \
    php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath \
    php8.2-fileinfo php8.2-tokenizer php8.2-dom

# --- 3. Install MySQL ---
echo "[3/10] Installing MySQL..."
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

# Create database and user
echo "Setting up database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS yeabnehs_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'yeabneh'@'localhost' IDENTIFIED BY 'YeaBneh2026!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON yeabnehs_store.* TO 'yeabneh'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# --- 4. Install Node.js 20 ---
echo "[4/10] Installing Node.js 20..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# --- 5. Install Composer ---
echo "[5/10] Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# --- 6. Install Nginx ---
echo "[6/10] Installing Nginx..."
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# --- 7. Clone and Setup App ---
echo "[7/10] Cloning YeaBneh Store..."
cd /var/www
sudo git clone https://github.com/raeeynega/yeabnehs-store.git yeabnehs-store
sudo chown -R www-data:www-data yeabnehs-store
cd yeabnehs-store

# --- 8. Configure Laravel ---
echo "[8/10] Configuring Laravel..."

# Create .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Update .env for production
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
sed -i 's|APP_URL=http://localhost:8000|APP_URL=http://YOUR_IP_OR_DOMAIN|' .env
sed -i 's/DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
sed -i 's/DB_DATABASE=yeabnehs_store/DB_DATABASE=yeabnehs_store/' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=yeabneh/' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=YeaBneh2026!/' .env

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# --- 9. Configure Nginx ---
echo "[9/10] Configuring Nginx..."
sudo tee /etc/nginx/sites-available/yeabnehs-store > /dev/null << 'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/yeabnehs-store/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realroot$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/yeabnehs-store /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx

# --- 10. Setup Firewall ---
echo "[10/10] Configuring firewall..."
sudo apt install -y ufw
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw --force enable

echo ""
echo "=========================================="
echo "  Setup Complete!"
echo "=========================================="
echo ""
echo "Your YeaBneh Store is now live at:"
echo "  http://YOUR_SERVER_IP"
echo ""
echo "Database:"
echo "  Host: 127.0.0.1"
echo "  Name: yeabnehs_store"
echo "  User: yeabneh"
echo "  Pass: YeaBneh2026!"
echo ""
echo "Admin Panel:"
echo "  URL: http://YOUR_SERVER_IP/admin"
echo "  Email: admin@yeabnehsstore.com"
echo "  Password: password"
echo ""
echo "Next steps:"
echo "  1. Update APP_URL in /var/www/yeabnehs-store/.env"
echo "  2. Change the database password"
echo "  3. Set up SSL with: sudo certbot --nginx -d yourdomain.com"
echo "  4. Update OAuth redirect URLs in .env"
echo "=========================================="
