# ⚡ SentriPay - Getting Started Guide

Panduan lengkap untuk mulai menggunakan aplikasi SentriPay dari awal.

---

## ✅ Prerequisites

Sebelum memulai, pastikan Anda memiliki:

- ✅ PHP 8.2 atau lebih tinggi
- ✅ Composer (PHP Package Manager)
- ✅ Node.js 16+ dan NPM
- ✅ MySQL atau MariaDB
- ✅ Git (optional, untuk version control)
- ✅ VS Code atau editor lain

### Check Version

```bash
# Check PHP
php --version

# Check Composer
composer --version

# Check Node & NPM
node --version
npm --version

# Check MySQL
mysql --version
```

---

## 🎯 Step-by-Step Setup

### Step 1: Navigate ke Project

```bash
cd c:\laragon\www\rill-store\sentripay
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies  
npm install
```

**Waktu yang dibutuhkan**: ~5-10 menit

### Step 3: Setup Environment

```bash
# Copy environment file
cp .env.example .env

# atau di Windows:
copy .env.example .env
```

Edit file `.env`:

```env
APP_NAME=SentriPay
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentripay
DB_USERNAME=root
DB_PASSWORD=
```

### Step 4: Generate Application Key

```bash
php artisan key:generate
```

Output:
```
Application key set successfully.
```

### Step 5: Create Database

**Cara 1: Via MySQL CLI**
```bash
mysql -u root -p

# Di dalam mysql prompt:
CREATE DATABASE sentripay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

**Cara 2: Via Laragon/phpMyAdmin**
- Buka phpMyAdmin
- Klik "New"
- Database name: `sentripay`
- Collation: `utf8mb4_unicode_ci`
- Create

**Cara 3: Via MySQL Workbench**
- New Schema
- Nama: `sentripay`
- Execute

### Step 6: Run Migrations

```bash
php artisan migrate
```

Output:
```
Migration table created successfully.
Migrating: 2025_12_29_044821_create_users_table
Migrated: 2025_12_29_044821_create_users_table
...
```

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

Ini membuat symlink untuk file uploads.

### Step 8: (Optional) Seed Database dengan Dummy Data

```bash
php artisan db:seed
```

Atau fresh dengan seed:
```bash
php artisan migrate:fresh --seed
```

### Step 9: Build Frontend Assets

```bash
# Production build
npm run build

# atau untuk development dengan hot reload:
npm run dev
```

---

## 🚀 Running the Application

### Terminal 1: Start PHP Server

```bash
php artisan serve
```

Output:
```
INFO  Server running on [http://127.0.0.1:8000].
```

### Terminal 2: Start Vite Dev Server (Hot Reload)

```bash
npm run dev
```

Output:
```
VITE v5.0.0  ready in 234 ms

➜  Local:   http://localhost:5173/
```

### Akses Aplikasi

Buka browser dan kunjungi: **http://localhost:8000**

---

## 👤 Test User Accounts

Jika Anda menjalankan `php artisan migrate:fresh --seed`, berikut adalah test accounts:

### Buyer Account
- **Email**: buyer@example.com
- **Password**: password

### Seller Account
- **Email**: seller@example.com
- **Password**: password

### Admin Account
- **Email**: admin@example.com
- **Password**: password

### Manual User Creation

```bash
php artisan tinker

# Create buyer
User::create([
    'name' => 'John Buyer',
    'email' => 'buyer@example.com',
    'password' => bcrypt('password'),
    'role' => 'buyer',
    'status' => 'active',
    'verified_at' => now(),
]);

# Create seller
User::create([
    'name' => 'Jane Seller',
    'email' => 'seller@example.com',
    'password' => bcrypt('password'),
    'role' => 'seller',
    'status' => 'active',
    'verified_at' => now(),
    'bank_name' => 'Bank Mandiri',
    'bank_account' => '9876543210',
    'account_holder' => 'Jane Seller',
]);

exit
```

---

## 📁 Project Files You'll Use Most

| File/Folder | Purpose | Edit When... |
|-------------|---------|--------------|
| `.env` | Configuration | Setup database, SMTP |
| `routes/web.php` | Routes definition | Add new routes |
| `app/Livewire/` | Components | Create new features |
| `resources/views/` | Templates | Modify UI |
| `app/Models/` | Database models | Add new entities |
| `database/migrations/` | Schema | Change database |

---

## 🧪 Quick Testing

### Test Homepage
```
http://localhost:8000/
```
Anda seharusnya melihat SentriPay homepage dengan features.

### Test Product Browsing
```
http://localhost:8000/products
```
Jika sudah login sebagai buyer, Anda bisa lihat products.

### Test Dashboard
```
http://localhost:8000/dashboard
```
Hanya untuk authenticated users.

### Test Admin Area
```
http://localhost:8000/admin/dashboard
```
Hanya untuk admin role.

---

## 🐛 Common Issues & Solutions

### Issue 1: "SQLSTATE[HY000] [2002] No such file or directory"
**Penyebab**: Database tidak exist atau MySQL tidak running

**Solusi**:
```bash
# Buat database
mysql -u root -p
CREATE DATABASE sentripay;

# Verify di .env
DB_HOST=127.0.0.1
DB_DATABASE=sentripay

# Run migrations
php artisan migrate
```

### Issue 2: "No application encryption key has been specified"
**Penyebab**: APP_KEY belum di-generate

**Solusi**:
```bash
php artisan key:generate
```

### Issue 3: "The storage path does not exist"
**Penyebab**: Storage link belum dibuat

**Solusi**:
```bash
php artisan storage:link
```

### Issue 4: Assets tidak loading (CSS/JS)
**Penyebab**: Vite dev server belum running atau build belum di-run

**Solusi**:
```bash
# Terminal 2
npm run dev

# atau production build:
npm run build
```

### Issue 5: "Livewire component not found"
**Penyebab**: Component belum di-discover

**Solusi**:
```bash
php artisan livewire:discover
php artisan cache:clear
```

### Issue 6: "Permission denied" errors
**Penyebab**: Folder permissions tidak tepat

**Solusi**:
```bash
# Linux/Mac
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/app/public

# Windows (dalam administrator command prompt)
icacls storage /grant Everyone:(OI)(CI)M /T
icacls bootstrap\cache /grant Everyone:(OI)(CI)M /T
```

---

## 📊 Project Structure Understanding

```
sentripay/
├── app/                     # Business logic
│   ├── Models/             # Database models
│   └── Livewire/           # Real-time components
│
├── database/               # Database stuff
│   ├── migrations/         # Schema files
│   └── seeders/            # Dummy data
│
├── resources/              # Frontend assets
│   ├── views/              # HTML templates (Blade)
│   ├── js/                 # JavaScript (Alpine.js)
│   └── css/                # Styles (Tailwind)
│
├── routes/
│   └── web.php             # All routes
│
├── storage/
│   └── app/public/         # Uploaded files
│
└── .env                    # Configuration
```

---

## 🎓 What Each Technology Does

### Laravel (Backend Framework)
- Handles database, authentication, routing
- Processes form submissions
- Manages file uploads
- Provides security features

### Livewire (Real-time Components)
- Updates parts of page without full reload
- Handles form interactions
- No JavaScript code needed
- Example: Product search, quantity selector

### Alpine.js (Frontend Interactivity)
- Shows/hides elements (dropdowns, modals)
- Format currency, dates
- Global utilities
- Lightweight (~16KB)

### Tailwind CSS (Styling)
- Pre-built utility classes
- Responsive design
- Mobile-first approach
- Easy to customize

---

## 🔐 Security Reminders

**NEVER commit these files to Git:**
- `.env` (contains passwords!)
- `.env.local`
- `storage/logs/`

**Always use:**
- Strong passwords
- HTTPS in production
- Keep dependencies updated: `composer update`

---

## 📚 Next Learning Steps

1. **Read dokumentasi**
   - See `DOKUMENTASI.md` for detailed info
   - See `QUICK_REFERENCE.md` for commands

2. **Explore the code**
   - Look at `app/Models/` untuk understand database structure
   - Look at `app/Livewire/` untuk understand components

3. **Try modifications**
   - Edit homepage text in `resources/views/home.blade.php`
   - Change colors in `tailwind.config.js`
   - Add new route in `routes/web.php`

4. **Create new feature**
   - Make migration: `php artisan make:migration add_column_to_table`
   - Make model: `php artisan make:model NewModel`
   - Make Livewire component: `php artisan make:livewire NewComponent`

---

## 💾 Backup & Recovery

### Before Making Big Changes

```bash
# Backup database
mysqldump -u root sentripay > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup database files
# Copy folder: storage/app/public/
```

### Restore Database

```bash
# Restore from backup
mysql -u root sentripay < backup_20251229_120000.sql

# atau jika sudah ada data baru:
php artisan migrate:fresh --seed
```

---

## ✨ Quick Wins to Try

1. **Change app name**
   - Edit `.env`: `APP_NAME=MyApp`
   - Edit `resources/views/layouts/app.blade.php`: Change logo

2. **Change colors**
   - Edit `tailwind.config.js`
   - Change primary color dari blue to purple

3. **Add new menu item**
   - Edit `resources/views/layouts/app.blade.php`
   - Add link in navbar

4. **Create test product**
   - Login as seller
   - Navigate to seller products
   - Add new product

5. **Test workflow**
   - Login as buyer
   - Browse products
   - Add to cart
   - Checkout

---

## 🎯 Your First Week Plan

### Day 1
- [ ] Setup project (follow steps above)
- [ ] Run migrations
- [ ] Access http://localhost:8000
- [ ] Create test users

### Day 2-3
- [ ] Login with different roles (buyer, seller)
- [ ] Explore database structure
- [ ] Read DOKUMENTASI.md
- [ ] Browse source code

### Day 4-5
- [ ] Make small modifications
- [ ] Change UI colors/text
- [ ] Add new route
- [ ] Test Livewire interaction

### Day 6-7
- [ ] Create new feature
- [ ] Test payment flow
- [ ] Test dispute system
- [ ] Test withdrawal

---

## 🆘 Need Help?

### Check These Files First
1. `DOKUMENTASI.md` - Detailed documentation
2. `QUICK_REFERENCE.md` - Common commands
3. `SETUP_GUIDE.md` - Setup instructions

### Online Resources
- Laravel Docs: https://laravel.com/docs
- Livewire: https://livewire.laravel.com
- Alpine.js: https://alpinejs.dev
- Stack Overflow: Search your error message

### Common Commands Reminder

```bash
# View all available commands
php artisan list

# Get help for specific command
php artisan help migrate

# Run tinker (interactive shell)
php artisan tinker
```

---

## 🎉 Congratulations!

Anda sekarang siap untuk:
✅ Menjalankan aplikasi SentriPay
✅ Memahami struktur project
✅ Membuat modifikasi
✅ Develop features baru
✅ Deploy ke production

**Selamat coding! 🚀**

---

**Created**: December 29, 2025
**Last Updated**: December 29, 2025
**Status**: Ready for Development & Learning
