# SentriPay - Platform Escrow (Rekber) untuk Jual Beli Online

## 📋 Deskripsi Project

**SentriPay** adalah platform escrow (pihak ketiga) yang aman untuk transaksi jual beli online. Platform ini memastikan tidak ada penipuan dengan menahan dana pembeli sampai barang diterima dan dikonfirmasi.

### Stack Technology:
- **Backend:** Laravel 12 + Livewire 3
- **Frontend:** Alpine.js + Tailwind CSS
- **Database:** MySQL/MariaDB
- **File Storage:** Local Storage

---

## 🏗️ Struktur Database

### Tabel Users
Menyimpan data pengguna dengan role (buyer, seller, admin)
```sql
- id: Primary Key
- name, email, password
- role: enum(buyer, seller, admin)
- phone, address, city, province, postal_code
- bank_name, bank_account, account_holder (untuk pencairan)
- balance: decimal (saldo di sistem)
- status: enum(active, suspended, blocked)
- verified_at: timestamp (verifikasi KTP/dokumen)
```

### Tabel Products
Data produk yang dijual oleh seller
```sql
- id: Primary Key
- user_id: FK ke Users (penjual)
- name, description
- price: decimal
- stock: integer
- category: string
- image_path: string
- status: enum(available, sold_out, inactive)
- sold: integer (jumlah terjual)
```

### Tabel Orders
Catatan pesanan dari pembeli
```sql
- id: Primary Key
- order_number: unique (ORD-YYYYMMDD-XXXX)
- buyer_id, seller_id: FK
- product_id: FK
- quantity, unit_price, total_amount
- status: enum(pending_payment, payment_confirmed, shipped, delivered, completed, cancelled, disputed)
- payment_date, shipped_date, delivered_date, completed_date
```

### Tabel Transactions
Catatan transaksi finansial
```sql
- id: Primary Key
- transaction_number: unique (TXN-YYYYMMDD-XXXX)
- order_id: FK
- from_user_id, to_user_id: FK
- amount: decimal
- type: enum(deposit, hold, release, refund, commission)
- status: enum(pending, processing, completed, failed, cancelled)
- bank_proof: path (bukti transfer)
```

### Tabel Disputes
Sistem komplain/dispute
```sql
- id: Primary Key
- dispute_number: unique (DSP-YYYYMMDD-XXXX)
- order_id: FK
- complained_by, complained_against: FK (user)
- reason: enum (barang tidak sampai, tidak sesuai, rusak, dll)
- status: enum(open, in_review, resolved, appeal, closed)
- resolution: enum(refund_to_buyer, keep_with_seller, split, pending)
- reviewed_by: FK (admin yang handle)
```

---

## 🔄 Alur Kerja Utama

### 1. Kesepakatan & Pemesanan
```
Pembeli melihat produk → Klik Beli → Buat Order
Status: pending_payment
```

### 2. Pembayaran
```
Pembeli transfer ke rekening SentriPay → Upload bukti transfer
Order Status: payment_confirmed
Dana ditahan di sistem (dalam transit)
```

### 3. Konfirmasi Pembayaran (Admin)
```
Admin verifikasi bukti transfer → Konfirmasi penerimaan dana
Penjual mendapat notifikasi untuk mulai pengiriman
```

### 4. Pengiriman
```
Penjual mengirim barang → Update status "shipped"
Order Status: shipped
Pembeli mendapat tracking (opsional)
```

### 5. Penerimaan & Konfirmasi
```
Pembeli terima barang → Klik "Terima Pesanan"
Order Status: delivered → completed
Dana dilepas ke penjual
```

### 6. Pencairan Saldo
```
Penjual lihat saldo di akun → Request Withdraw
Dana masuk ke rekening bank penjual
```

---

## ⚠️ Alur Dispute (Jika Ada Masalah)

### Pembeli Mengajukan Komplain:
```
1. Buka pesanan → Klik "Ajukan Komplain"
2. Pilih alasan (barang tidak sampai, rusak, tidak sesuai)
3. Upload bukti (foto, screenshot chat)
4. Submit komplain
```

### Status Dispute:
- **open**: Komplain baru masuk
- **in_review**: Admin sedang meninjau
- **resolved**: Admin sudah memberikan keputusan
- **appeal**: Pihak yang dirugikan banding
- **closed**: Case ditutup

### Resolusi:
- **refund_to_buyer**: Dana kembali ke pembeli
- **keep_with_seller**: Dana tetap ke penjual
- **split**: Dana dibagi dua
- **pending**: Menunggu keputusan

---

## 📁 Struktur Folder Project

```
sentripay/
├── app/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Transaction.php
│   │   └── Dispute.php
│   ├── Livewire/
│   │   ├── Dashboard.php
│   │   ├── ProductBrowser.php
│   │   ├── CheckoutOrder.php
│   │   ├── PaymentProcess.php
│   │   └── ... (komponen lainnya)
│   ├── Http/
│   │   └── Middleware/
│   │       ├── Seller.php
│   │       └── Admin.php
│   └── ...
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_products_table.php
│       ├── create_orders_table.php
│       ├── create_transactions_table.php
│       └── create_disputes_table.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── livewire/
│   │   │   ├── dashboard.blade.php
│   │   │   ├── product-browser.blade.php
│   │   │   ├── checkout-order.blade.php
│   │   │   └── payment-process.blade.php
│   │   ├── auth/
│   │   ├── seller/
│   │   ├── admin/
│   │   └── ...
│   └── ...
├── routes/
│   └── web.php
└── ...
```

---

## 🚀 Fitur Utama

### Untuk Pembeli:
- ✅ Browsing produk dengan filter & search
- ✅ Membuat pesanan
- ✅ Membayar melalui transfer bank
- ✅ Melacak pesanan (order status)
- ✅ Menerima pesanan
- ✅ Mengajukan komplain jika ada masalah
- ✅ Melihat riwayat transaksi

### Untuk Penjual:
- ✅ Membuat & mengelola produk
- ✅ Menerima notifikasi pesanan
- ✅ Mengupdate status pengiriman
- ✅ Melihat pendapatan
- ✅ Merespons komplain
- ✅ Withdraw saldo ke rekening bank

### Untuk Admin:
- ✅ Memverifikasi pembayaran
- ✅ Mengelola disputes
- ✅ Melihat statistik platform
- ✅ Mengelola user
- ✅ Mengatur komisi

---

## 🔐 Security Features

1. **Enkripsi Sandi**: Password di-hash menggunakan bcrypt
2. **CSRF Protection**: Laravel CSRF token di setiap form
3. **Role-Based Access Control**: Middleware untuk seller & admin
4. **Bukti Transfer**: Screenshot/foto sebagai bukti pembayaran
5. **Audit Trail**: Semua transaksi tercatat dengan timestamp
6. **Verification**: Admin verify pembayaran sebelum order diproses

---

## 📊 Komponen Livewire (Backend Interaktif)

### Dashboard
- Menampilkan overview statistik
- Recent orders
- Balance
- Status account

### ProductBrowser
- Search & filter produk
- Pagination
- Real-time filtering dengan Alpine.js

### CheckoutOrder
- Konfirmasi pesanan
- Quantity selector
- Order summary
- Submit order

### PaymentProcess
- Instruksi pembayaran
- Upload bukti transfer
- Step-by-step process

---

## 🎨 Alpine.js Integration

Alpine.js digunakan untuk interaksi frontend tanpa reload halaman:

```javascript
// Contoh di ProductBrowser
<div x-data="{ 
    open: false,
    search: @entangle('search'),
    category: @entangle('category')
}">
    <input x-model="search" @change="$wire.updatedSearch()">
</div>
```

**Keuntungan**:
- Lightweight (tidak perlu Vue/React)
- Reactive dengan Livewire
- Smooth UX tanpa full page reload

---

## 📝 Environment Configuration

File `.env`:
```env
APP_NAME=SentriPay
APP_ENV=local
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sentripay
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=noreply@sentripay.com
```

---

## 🛠️ Instalasi & Setup

```bash
# 1. Clone atau setup project
cd c:\laragon\www\rill-store\sentripay

# 2. Install dependencies
composer install
npm install

# 3. Generate APP_KEY
php artisan key:generate

# 4. Migrasi database
php artisan migrate

# 5. Seeding (opsional)
php artisan db:seed

# 6. Start development
php artisan serve
npm run dev
```

---

## 📱 API Endpoints (Untuk Future Mobile App)

```
GET  /api/products              - List products
GET  /api/products/{id}         - Product detail
GET  /api/orders                - User orders
POST /api/orders                - Create order
GET  /api/order/{id}            - Order detail
POST /api/payment/verify        - Verify payment
GET  /api/disputes              - User disputes
POST /api/disputes              - Create dispute
```

---

## 🎯 Next Steps

- [ ] Implementasi Authentication (Login/Register)
- [ ] Setup Email notifications
- [ ] Payment verification automation
- [ ] Invoice generator
- [ ] Rating & review system
- [ ] Chat/messaging antara buyer-seller
- [ ] Mobile responsive improvement
- [ ] API development untuk mobile app
- [ ] Testing & QA

---

## 📞 Kontak & Support

- Email: support@sentripay.com
- WhatsApp: +62 XXX XXX XXX
- Website: sentripay.com

---

**Created with ❤️ for Safe Online Commerce**
