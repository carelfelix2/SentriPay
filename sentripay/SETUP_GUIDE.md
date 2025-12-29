# SentriPay Configuration & Setup Guide

## 🎯 Quick Start

Panduan cepat untuk setup dan menjalankan aplikasi SentriPay.

---

## 📋 Persiapan Awal

### System Requirements
- PHP 8.2+
- Composer 2.0+
- Node.js 16+ & NPM
- MySQL 5.7+ atau MariaDB 10.3+
- Git (opsional)

### Tools Recommended
- VS Code (Editor)
- Postman (API Testing)
- MySQL Workbench (Database Management)
- DBeaver (Alternative Database Tool)

---

## 🚀 Setup Instructions

### Step 1: Database Setup

```sql
-- Login ke MySQL
mysql -u root -p

-- Buat database
CREATE DATABASE sentripay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Buat user (opsional)
CREATE USER 'sentripay'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON sentripay.* TO 'sentripay'@'localhost';
FLUSH PRIVILEGES;
```

### Step 2: Environment Configuration

Buat/edit file `.env`:

```env
# APP
APP_NAME=SentriPay
APP_ENV=local
APP_KEY=base64:... # akan di-generate
APP_DEBUG=true
APP_URL=http://localhost:8000

# DATABASE
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentripay
DB_USERNAME=root
DB_PASSWORD=

# MAIL (untuk email notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@sentripay.com
MAIL_FROM_NAME=SentriPay

# SESSION
SESSION_DRIVER=cookie
SESSION_LIFETIME=120

# CACHE
CACHE_DRIVER=file

# QUEUE
QUEUE_CONNECTION=sync

# STORAGE
FILESYSTEM_DISK=public

# BROADCAST
BROADCAST_DRIVER=log

# BANK CONFIGURATION (untuk instruksi pembayaran)
BANK_NAME=Bank Mandiri
BANK_ACCOUNT=1234567890
BANK_ACCOUNT_NAME=PT. SentriPay Indonesia

# PLATFORM SETTINGS
PLATFORM_COMMISSION=2
MIN_WITHDRAW=50000
DISPUTE_RESOLUTION_DAYS=14
```

### Step 3: Installation

```bash
# 1. Navigate ke folder project
cd c:\laragon\www\rill-store\sentripay

# 2. Install Composer dependencies
composer install

# 3. Generate APP_KEY
php artisan key:generate

# 4. Install NPM dependencies
npm install

# 5. Create storage link
php artisan storage:link

# 6. Run migrations
php artisan migrate

# 7. (Opsional) Seed data
php artisan db:seed

# 8. Build assets
npm run build
```

### Step 4: Running Development Server

```bash
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite Dev Server (hot reload)
npm run dev
```

Akses aplikasi: `http://localhost:8000`

---

## 📚 File Structure

### Key Files

```
app/
├── Models/
│   ├── User.php              # User model dengan relationships
│   ├── Product.php           # Produk model
│   ├── Order.php             # Order model
│   ├── Transaction.php       # Transaksi finansial
│   └── Dispute.php           # Komplain/dispute
│
├── Livewire/
│   ├── Dashboard.php         # Dashboard component
│   ├── ProductBrowser.php    # Browse produk
│   ├── CheckoutOrder.php     # Proses checkout
│   ├── PaymentProcess.php    # Payment verification
│   ├── DisputeManager.php    # Manage disputes
│   └── WalletManager.php     # Manage balance
│
└── Http/Middleware/
    ├── SellerMiddleware.php  # Role check untuk seller
    └── AdminMiddleware.php   # Role check untuk admin

database/
├── migrations/               # Schema definitions
├── seeders/                  # Dummy data
└── factories/                # Model factories untuk testing

resources/
├── views/
│   ├── layouts/app.blade.php       # Main layout
│   ├── home.blade.php              # Homepage
│   ├── livewire/                   # Livewire components
│   ├── auth/                       # Login/Register
│   ├── seller/                     # Seller pages
│   └── admin/                      # Admin pages
└── js/
    └── alpine-utils.js             # Alpine.js helpers
```

---

## 🔑 Authentication System

### Default User Roles

```php
1. buyer    - Pembeli (default role)
2. seller   - Penjual
3. admin    - Administrator
```

### Creating Admin User

```php
// Via Artisan Tinker
php artisan tinker

// Then in tinker:
User::create([
    'name' => 'Admin User',
    'email' => 'admin@sentripay.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'status' => 'active',
    'verified_at' => now(),
]);
```

---

## 🎨 Customization

### Change App Name

1. Edit `.env`:
   ```env
   APP_NAME=YourAppName
   ```

2. Edit `config/app.php`:
   ```php
   'name' => env('APP_NAME', 'SentriPay'),
   ```

### Change Commission Rate

1. Edit `.env`:
   ```env
   PLATFORM_COMMISSION=3  # 3% dari penjualan
   ```

2. Update di database/seeders jika ada

### Change Bank Details

1. Edit `.env`:
   ```env
   BANK_NAME=BCA
   BANK_ACCOUNT=1234567890
   BANK_ACCOUNT_NAME=PT. SentriPay Indonesia
   ```

2. View akan mengambil dari `.env` secara otomatis

---

## 🐛 Debugging

### Enable Debug Mode

File `.env`:
```env
APP_DEBUG=true
```

### View Database Queries

Gunakan `DB::listen()` di `AppServiceProvider`:

```php
DB::listen(function ($query) {
    \Log::info($query->sql, $query->bindings);
});
```

### Livewire Debugging

Tambahkan ke `livewire.php` config:
```php
'debug' => true,
```

---

## 📊 Database Management

### View Database via CLI

```bash
php artisan tinker

# Examples:
User::all();
Product::where('status', 'available')->get();
Order::with('buyer', 'seller', 'product')->get();
```

### Reset Database

```bash
# Fresh migrate (hapus semua data)
php artisan migrate:fresh

# Fresh + seed
php artisan migrate:fresh --seed
```

### Backup Database

```bash
# Export
mysqldump -u root sentripay > sentripay_backup.sql

# Import
mysql -u root sentripay < sentripay_backup.sql
```

---

## 📦 Adding Packages

### Install via Composer

```bash
# Laravel package
composer require vendor/package

# Update dependencies
composer update
```

### Install via NPM

```bash
# Frontend package
npm install package-name

# Save as dev dependency
npm install --save-dev package-name
```

---

## 🧪 Testing

### Run Tests

```bash
# All tests
php artisan test

# Specific file
php artisan test tests/Feature/OrderTest.php

# With coverage
php artisan test --coverage
```

### Create Test File

```bash
php artisan make:test OrderTest
```

---

## 📝 Logging

### Log Locations

```
storage/logs/laravel.log
```

### Enable File Logging

`.env`:
```env
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```

### Using Logger

```php
\Log::info('Message here');
\Log::error('Error message');
\Log::debug('Debug info');
```

---

## 🚀 Deployment

### Build for Production

```bash
# Build assets
npm run build

# Set environment
.env: APP_ENV=production, APP_DEBUG=false

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Deploy via Hosting

1. Upload files via FTP/SFTP
2. Set database credentials
3. Run migrations: `php artisan migrate`
4. Set proper permissions:
   ```bash
   chmod -R 775 storage/
   chmod -R 775 bootstrap/cache/
   ```

---

## 🤝 Contributing

Want to improve SentriPay? Follow these steps:

1. Create a new branch
2. Make your changes
3. Test thoroughly
4. Create pull request

---

## ❓ FAQ

### Q: How to reset password?
A: In tinker: `User::find(1)->update(['password' => bcrypt('newpass')])`

### Q: How to change database?
A: Edit `.env` file DB settings and run `php artisan migrate`

### Q: Portfolio mode (no real payment)?
A: Set `PAYMENT_MODE=demo` in `.env` to bypass real payment

### Q: How to access uploaded files?
A: Run `php artisan storage:link` to create public link

---

## 📞 Support

- **Issues**: Create issue on GitHub/GitLab
- **Discussions**: Use discussion forum
- **Email**: support@sentripay.com

---

**Happy Coding! 🎉**
