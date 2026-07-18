# YeaBneh Store

Premium fitness e-commerce platform built with Laravel 12. Features a full storefront, courses, personal training booking, Ethiopian payment methods (CBE & Telebirr), OpenID authentication, and a zero-trust security dashboard.

## Features

- **Shop** — Product catalog with categories, search, filtering, and cart/checkout
- **Courses** — Online fitness courses with lessons and progress tracking
- **Personal Training** — Book 1-on-1, group, or online coaching sessions
- **Payments** — CBE bank transfer and Telebirr mobile money with confirmation flow
- **Authentication** — Email/password + Google, GitHub, Microsoft OpenID login
- **Security Dashboard** — Real-time activity logging, security events, IP blocking, user management
- **Mobile-first** — Responsive design with slide-in sidebar, rounded UI, Inter font

## Tech Stack

- Laravel 12 / PHP 8.2
- MySQL
- Tailwind CSS (CDN)
- Chart.js (security dashboard)
- Laravel Socialite (OpenID)

## Setup

```bash
git clone https://github.com/raeeynega/yeabnehs-store.git
cd yeabnehs-store
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your MySQL credentials, then:

```bash
mysql -u root -p -e "CREATE DATABASE yeabnehs_store;"
php artisan migrate --seed
php artisan serve
```

Visit `http://localhost:8000`

### Admin Login

- **Email:** admin@yeabnehsstore.com
- **Password:** password
- **Dashboard:** http://localhost:8000/admin

## OAuth Setup (Optional)

Add your credentials to `.env`:

```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
MICROSOFT_CLIENT_ID=
MICROSOFT_CLIENT_SECRET=
```

## License

MIT
