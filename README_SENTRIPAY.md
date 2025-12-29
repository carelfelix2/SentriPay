# SentriPay - Platform Escrow untuk Jual Beli Online

**Platform aman untuk transaksi jual beli online dengan perlindungan pihak ketiga (Escrow)**

![Laravel](https://img.shields.io/badge/Laravel-12-red?style=flat-square)
![Livewire](https://img.shields.io/badge/Livewire-3-blue?style=flat-square)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-green?style=flat-square)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-blue?style=flat-square)

---

## 🎯 Fitur Utama

### Untuk Pembeli
- 🛍️ Browse dan cari produk
- 🛒 Checkout yang mudah
- 💳 Pembayaran aman melalui transfer bank
- 📦 Tracking pesanan real-time
- ✅ Konfirmasi penerimaan barang
- 🚨 Sistem komplain/dispute jika ada masalah
- 💰 Riwayat transaksi lengkap

### Untuk Penjual
- 📦 Manajemen produk (create, update, delete)
- 📊 Dashboard penjualan
- 📬 Notifikasi pesanan masuk
- 📮 Perbarui status pengiriman
- 💵 Monitoring saldo & earnings
- 🏦 Withdrawal ke rekening bank
- 📞 Respond complaint dari pembeli

### Untuk Admin
- 👥 Manajemen user (verifikasi, suspend)
- 💰 Verifikasi pembayaran
- ⚖️ Menangani disputes
- 📊 Statistik platform
- ⚙️ Pengaturan sistem
- 🛡️ Security & compliance

---

## 🏗️ Tech Stack

- **Backend**: Laravel 12 Framework
- **Frontend**: Alpine.js + Tailwind CSS
- **Real-time**: Livewire 3 (No JavaScript needed!)
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum
- **Storage**: Local File Storage
- **Testing**: PHPUnit + Pest

---

## 📦 Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Setup Steps

```bash
# 1. Clone atau navigasi ke folder project
cd c:\laragon\www\rill-store\sentripay

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Buat database
# MySQL Command:
# CREATE DATABASE sentripay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 7. Migrasi database
php artisan migrate

# 8. (Opsional) Seed dummy data
php artisan db:seed

# 9. Build frontend assets
npm run build

# 10. Start development server (dalam 2 terminal)
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

Akses aplikasi di: `http://localhost:8000`

---

## 🗂️ Struktur Project

```
sentripay/
├── app/
│   ├── Http/
│   │   ├── Middleware/
│   │   │   ├── SellerMiddleware.php
│   │   │   └── AdminMiddleware.php
│   │   └── Controllers/
│   ├── Livewire/
│   │   ├── Dashboard.php              # Dashboard user
│   │   ├── ProductBrowser.php         # Browse produk
│   │   ├── CheckoutOrder.php          # Proses checkout
│   │   └── PaymentProcess.php         # Upload bukti pembayaran
│   ├── Models/
│   │   ├── User.php                   # User model
│   │   ├── Product.php                # Produk
│   │   ├── Order.php                  # Pesanan
│   │   ├── Transaction.php            # Transaksi finansial
│   │   └── Dispute.php                # Komplain/dispute
│   └── ...
├── database/
│   ├── migrations/                    # Database schema
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/app.blade.php      # Master layout
│   │   ├── home.blade.php             # Homepage
│   │   ├── livewire/                  # Livewire components
│   │   ├── auth/                      # Auth views (login, register)
│   │   ├── seller/                    # Seller-only views
│   │   ├── admin/                     # Admin-only views
│   │   └── ...
│   ├── css/
│   │   └── app.css                    # Tailwind CSS
│   └── js/
│       └── app.js                     # Alpine.js entry
├── routes/
│   └── web.php                        # Web routes
├── DOKUMENTASI.md                     # Dokumentasi lengkap
└── README.md                          # File ini
```

---

## 🔄 Alur Transaksi Utama

```
┌─────────────────────────────────────────────────────────────────┐
│ 1. PEMESANAN                                                      │
├─────────────────────────────────────────────────────────────────┤
Pembeli melihat produk → Klik Beli → Form Checkout → Order Created
Status: pending_payment
│
├─────────────────────────────────────────────────────────────────┤
│ 2. PEMBAYARAN                                                     │
├─────────────────────────────────────────────────────────────────┤
Pembeli transfer ke rekening SentriPay → Upload bukti → Verifikasi
Status: payment_confirmed
Dana ditahan dalam sistem (escrow)
│
├─────────────────────────────────────────────────────────────────┤
│ 3. PENGIRIMAN                                                     │
├─────────────────────────────────────────────────────────────────┤
Penjual mendapat notifikasi → Kirim barang → Update status
Status: shipped
│
├─────────────────────────────────────────────────────────────────┤
│ 4. PENERIMAAN & KONFIRMASI                                       │
├─────────────────────────────────────────────────────────────────┤
Pembeli terima barang → Cek → Klik "Konfirmasi Terima"
Status: delivered → completed
Dana dilepas ke saldo penjual
│
├─────────────────────────────────────────────────────────────────┤
│ 5. PENCAIRAN (Withdrawal)                                        │
├─────────────────────────────────────────────────────────────────┤
Penjual lihat saldo → Request withdraw → Dana ke rekening bank
└─────────────────────────────────────────────────────────────────┘
```

---

## 🚨 Alur Dispute (Jika Ada Masalah)

```
Pembeli mengajukan komplain
    ↓
Pilih alasan (barang rusak, tidak sampai, dll)
    ↓
Upload bukti (foto, screenshot chat)
    ↓
Admin review bukti dari kedua belah pihak
    ↓
Admin membuat keputusan:
  • Refund ke pembeli
  • Tetap ke penjual
  • Bagi dua
    ↓
Case ditutup atau dibuka appeal
```

---

## 📊 Database Schema

### Users Table
```sql
id, name, email, password, role (buyer/seller/admin),
phone, address, city, province, postal_code,
bank_name, bank_account, account_holder,
balance, status, verified_at, timestamps
```

### Products Table
```sql
id, user_id (seller), name, description, price, stock,
category, image_path, status, sold, timestamps
```

### Orders Table
```sql
id, order_number, buyer_id, seller_id, product_id,
quantity, unit_price, total_amount, status,
payment_date, shipped_date, delivered_date, completed_date, timestamps
```

### Transactions Table
```sql
id, transaction_number, order_id, from_user_id, to_user_id,
amount, type (deposit/hold/release/refund), status,
description, bank_proof, confirmed_at, timestamps
```

### Disputes Table
```sql
id, dispute_number, order_id, complained_by, complained_against,
reason, complaint_description, status, evidence,
admin_notes, resolution, reviewed_by, resolved_at, timestamps
```

---

## 🎨 Component Usage

### Livewire Component di View
```blade
<livewire:dashboard />
<livewire:product-browser />
<livewire:checkout-order :productId="$id" />
<livewire:payment-process :orderId="$id" />
```

### Alpine.js Integration
```blade
<div x-data="{ 
    open: false,
    search: @entangle('search')
}">
    <input x-model="search" />
    <button @click="open = !open">Toggle</button>
</div>
```

---

## 🔐 Security Features

✅ CSRF Protection (laravel middleware)
✅ Password hashing (bcrypt)
✅ Role-based access control
✅ Email verification
✅ Bank transfer proof verification
✅ Audit trail untuk semua transaksi
✅ Rate limiting
✅ SQL injection prevention (Eloquent ORM)
✅ XSS protection

---

## 📝 Environment Configuration

File `.env`:
```env
APP_NAME=SentriPay
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentripay
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@sentripay.com
MAIL_FROM_NAME=SentriPay

# Bank Details (untuk instruksi pembayaran)
BANK_NAME=Bank Mandiri
BANK_ACCOUNT=1234567890
BANK_ACCOUNT_NAME=PT. SentriPay Indonesia

# Commission
PLATFORM_COMMISSION=2
```

---

## 📄 API Endpoints (Future Development)

```
GET    /api/products                  List semua produk
GET    /api/products/{id}             Detail produk
POST   /api/products                  Create produk (seller only)
PUT    /api/products/{id}             Update produk (seller only)
DELETE /api/products/{id}             Delete produk (seller only)

GET    /api/orders                    List order user
GET    /api/orders/{id}               Detail order
POST   /api/orders                    Create order (buyer only)

POST   /api/payment/verify            Verify pembayaran (admin only)
GET    /api/transactions              Riwayat transaksi

GET    /api/disputes                  List dispute
POST   /api/disputes                  Create dispute
PUT    /api/disputes/{id}/resolve     Resolve dispute (admin only)
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/OrderTest.php

# Run with coverage
php artisan test --coverage
```

---

## 📚 Dokumentasi

Lihat file `DOKUMENTASI.md` untuk dokumentasi lengkap tentang:
- Detailed feature explanation
- Database structure
- Workflow explanation
- Component details
- Next steps & roadmap

---

## 🐛 Troubleshooting

### Migrate Error: "No such file or directory"
```bash
# Pastikan file migration ada dan buat database terlebih dahulu
php artisan migrate:fresh --seed
```

### Livewire not updating
```bash
# Clear cache dan rebuild
php artisan livewire:discover
npm run dev
```

### Assets not loading
```bash
# Rebuild frontend
npm run dev
# atau untuk production
npm run build
```

---

## 📞 Support & Contact

- **Email**: support@sentripay.com
- **WhatsApp**: +62 XXX XXX XXX
- **Website**: sentripay.com
- **Documentation**: See `DOKUMENTASI.md`

---

## 📄 License

This project is open source and available under the MIT License.

---

## 🤝 Contributing

Contributions are welcome! Silakan buat:
1. Fork repository
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 🎉 Acknowledgments

- Laravel team untuk framework yang amazing
- Livewire untuk reactive components
- Alpine.js untuk lightweight interactivity
- Tailwind CSS untuk styling
- Community yang supportif

---

**SentriPay - Making Online Commerce Safe & Trustworthy** ❤️

Created with passion for secure online transactions.
