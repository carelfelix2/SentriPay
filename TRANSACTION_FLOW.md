# 📋 Dokumentasi Sistem Transaksi SentriPay (Perbaikan)

## ✅ Masalah yang Sudah Diperbaiki

### 1. **ID Pesanan Konsisten (SAMA untuk Buyer & Seller)**
```
✓ Order ID = Primary key dari table `orders`
✓ Sama untuk buyer_id dan seller_id karena record yang sama
✓ Baik buyer maupun seller lihat order dengan ID yang identik
```

### 2. **Status Order Auto-Update Setelah Pembayaran**
**Sebelumnya:**
- Status tetap `pending_payment` setelah upload bukti

**Sekarang:**
- Upload bukti → Status berubah ke `pending` (Siap Kirim)
- Payment date otomatis terisi dengan `now()`
- Seller wallet/balance otomatis terupdate dengan total_amount

### 3. **Seller Bisa Melihat Order Mereka**
Routes baru ditambahkan:
```php
/seller/orders          # List semua order (sebagai penjual)
/seller/order/{orderId} # Detail order spesifik (sebagai penjual)
```

---

## 🔄 FLOW TRANSAKSI LENGKAP

```
┌─────────────────────────────────────────────────────────────┐
│                    BUYER (Pembeli)                           │
└─────────────────────────────────────────────────────────────┘

1️⃣ CHECKOUT PRODUK
   └─ Buyer membuka produk → Klik "Beli"
   └─ Masuk halaman checkout (CheckoutOrder component)
   └─ Input quantity → Total amount dihitung
   └─ Klik "Lanjutkan Pembayaran"

2️⃣ ORDER DIBUAT (Status: pending_payment)
   ├─ Order::create([
   │   'order_number' => 'ORD-...',
   │   'buyer_id' => current user,
   │   'seller_id' => product owner,
   │   'product_id' => product id,
   │   'quantity' => input quantity,
   │   'total_amount' => calculated,
   │   'status' => 'pending_payment'  ← INITIAL
   │ ])
   └─ Redirect ke halaman pembayaran

3️⃣ PEMBAYARAN (PaymentProcess)
   ├─ Step 1: Tampilkan instruksi transfer
   │  └─ Nomor rekening SentriPay
   │  └─ Jumlah yang harus ditransfer
   │  └─ Catatan penting
   │
   ├─ Step 2: Upload bukti pembayaran
   │  ├─ Pilih bank pengirim
   │  ├─ Input nomor rekening pembeli
   │  ├─ Pilih tanggal transfer
   │  └─ Upload screenshot bukti
   │
   └─ Submit → submitPaymentProof() dipanggil

4️⃣ PEMBAYARAN DIKONFIRMASI
   ├─ Simpan bukti transfer ke storage/proofs/
   ├─ Buat Transaction record:
   │  └─ status: 'confirmed' (tidak pending!)
   │  └─ type: 'hold'
   │  └─ confirmed_at: now()
   │
   ├─ UPDATE Order:
   │  ├─ status: 'pending' (Siap Kirim) ← AUTO CHANGED!
   │  └─ payment_date: now()
   │
   ├─ UPDATE Seller balance:
   │  └─ balance += total_amount
   │
   └─ Redirect ke order.detail dengan flash message

5️⃣ BUYER LIHAT STATUS DI /orders
   ├─ Status berubah menjadi "Menunggu Pengiriman"
   ├─ Payment date terisi
   └─ Timeline menunjukkan pembayaran sudah diterima ✓

┌─────────────────────────────────────────────────────────────┐
│                    SELLER (Penjual)                          │
└─────────────────────────────────────────────────────────────┘

1️⃣ LIHAT PESANAN MASUK (/seller/orders)
   ├─ Seller login
   ├─ Klik "Pesanan Masuk" di sidebar
   ├─ Lihat list order dengan seller_id = current user
   ├─ Lihat informasi:
   │  ├─ No. Pesanan: #3 (SAMA dengan buyer)
   │  ├─ Status: Menunggu Pengiriman / Menunggu Pembayaran
   │  ├─ Pembeli name & contact
   │  ├─ Produk yang dipesan
   │  └─ Total harga
   └─ Klik "Lihat" untuk detail

2️⃣ LIHAT DETAIL PESANAN (/seller/order/{orderId})
   ├─ Order number: #3 (SAMA dengan buyer view!)
   ├─ Informasi pembeli lengkap
   ├─ Rincian produk & harga
   ├─ Timeline status pengiriman
   ├─ Informasi transaksi
   └─ Status pembayaran

3️⃣ KELOLA PENGIRIMAN
   ├─ Seller siapkan barang
   ├─ Update status order ke 'shipped' (akan ditambah fitur)
   ├─ Masukkan no resi kurir
   └─ Buyer notifikasi barang dikirim

4️⃣ SELLER LIHAT BALANCE/SALDO
   ├─ Dashboard: Saldo Akun bertambah setelah pembayaran
   ├─ Dapat di-withdraw ke rekening bank (fitur)
   └─ Lihat laporan earnings

```

---

## 📊 Database Schema - Order & Transaction

### Table: `orders`
```sql
CREATE TABLE orders (
  id                  BIGINT PRIMARY KEY
  order_number        VARCHAR UNIQUE (ORD-YYYYMMDD-XXXXX)
  buyer_id            BIGINT FOREIGN KEY (users)
  seller_id           BIGINT FOREIGN KEY (users)
  product_id          BIGINT FOREIGN KEY (products)
  quantity            INT
  unit_price          DECIMAL(15,2)
  total_amount        DECIMAL(15,2)
  status              ENUM (pending_payment, pending, shipped, delivered, completed, cancelled, disputed)
  payment_date        TIMESTAMP NULL
  shipped_date        TIMESTAMP NULL
  delivered_date      TIMESTAMP NULL
  completed_date      TIMESTAMP NULL
  notes               TEXT NULL
  created_at          TIMESTAMP
  updated_at          TIMESTAMP
)
```

### Table: `transactions`
```sql
CREATE TABLE transactions (
  id                  BIGINT PRIMARY KEY
  transaction_number  VARCHAR UNIQUE (TXN-YYYYMMDD-XXXXX)
  order_id            BIGINT FOREIGN KEY (orders) ← LINKER
  from_user_id        BIGINT FOREIGN KEY (users)
  to_user_id          BIGINT FOREIGN KEY (users)
  amount              DECIMAL(15,2)
  type                ENUM (deposit, hold, release, refund, commission)
  status              ENUM (pending, processing, completed, failed, cancelled)
  description         TEXT NULL
  bank_proof          VARCHAR (path ke storage/proofs/)
  confirmed_at        TIMESTAMP NULL
  created_at          TIMESTAMP
  updated_at          TIMESTAMP
)
```

---

## 🔐 Status Flow & Validasi

### Order Status Progression
```
pending_payment
    ↓
    Buyer upload bukti pembayaran
    ↓
pending ← (Siap Kirim untuk Seller)
    ↓
    Seller kirim barang dengan resi
    ↓
shipped
    ↓
    Buyer konfirmasi barang diterima
    ↓
delivered
    ↓
    (Auto atau manual) selesai = dana ke wallet
    ↓
completed
```

### Transaction Status
```
confirmed ← Saat pembayaran dikonfirmasi
```

---

## 📝 File yang Dimodifikasi

### Backend
1. `app/Livewire/PaymentProcess.php`
   - submitPaymentProof() → Auto update order status ke pending
   - Auto update seller balance
   - Transaction status dibuat 'confirmed' langsung

2. `routes/web.php`
   - Added `/seller/orders` route
   - Added `/seller/order/{orderId}` route

### Views
1. `resources/views/orders/index.blade.php`
   - Handle status `pending_payment` dan `pending`

2. `resources/views/orders/detail.blade.php`
   - Display `pending_payment` status dengan benar

3. `resources/views/seller/orders/index.blade.php` ✨ NEW
   - List pesanan untuk seller
   - Tampilkan order dengan ID yang SAMA

4. `resources/views/seller/orders/detail.blade.php` ✨ NEW
   - Detail pesanan dari perspektif seller
   - Tampilkan informasi pembeli & barang
   - Status timeline pengiriman

---

## ✅ TESTING CHECKLIST

- [ ] Buyer checkout → Pesanan dibuat dengan status `pending_payment`
- [ ] Buyer upload bukti → Status berubah ke `pending`
- [ ] Buyer lihat /orders → Status "Menunggu Pengiriman"
- [ ] Seller lihat /seller/orders → Lihat pesanan masuk
- [ ] Seller order id = Buyer order id (SAMA!)
- [ ] Seller saldo bertambah setelah pembayaran
- [ ] Transaction record dibuat dengan status 'confirmed'
- [ ] Payment date terisi di order

---

## 🎯 Fitur Selanjutnya

1. **Admin Approval** - Admin verifikasi bukti transfer sebelum konfirmasi
2. **Shipping Management** - Seller input nomor resi kurir
3. **Order Tracking** - Buyer track posisi barang real-time
4. **Rating & Review** - Buyer beri rating setelah barang diterima
5. **Withdrawal** - Seller tarik uang dari balance ke bank
6. **Dispute Resolution** - Handling komplain buyer vs seller

---

**Last Updated:** Dec 29, 2025  
**Version:** 1.1 (Fixed Order ID Consistency)
