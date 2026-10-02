# 🚀 Quick Setup Guide - Warko E-Commerce

Panduan cepat untuk menjalankan backend yang sudah dibuat.

## Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM

## Setup Steps

### 1. Database
```bash
mysql -u root -p
CREATE DATABASE warko_tembakau;
exit;
```

### 2. Update .env
```bash
DB_DATABASE=warko_tembakau
DB_USERNAME=root
DB_PASSWORD=your_password

# Update Midtrans keys nanti setelah punya account
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
```

### 3. Install & Migrate
```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
```

### 4. Install Auth (Required!)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
php artisan migrate
```

### 5. Register Middleware

Edit `bootstrap/app.php`, tambahkan:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
```

### 6. Link Storage
```bash
php artisan storage:link
```

### 7. Run Server
```bash
php artisan serve
```

## Default Accounts

**Admin:**
- Email: admin@warko.com
- Password: password

**Customer:**
- Email: customer@test.com
- Password: password

## Next Steps
- Buat Blade views sesuai walkthrough.md
- Integrate Midtrans Snap di payment page
- Implement remaining admin controllers
- Test manual flow lengkap

Lihat **walkthrough.md** untuk dokumentasi lengkap!
