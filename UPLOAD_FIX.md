# 📤 Troubleshooting: Upload Bukti Pembayaran

## ✅ Perbaikan yang Sudah Dilakukan

### 1. **File Upload Form Enhancement**
- ✓ Added file preview setelah file dipilih
- ✓ Display file name & size ketika file terpilih
- ✓ Added "Ganti" button untuk mengganti file
- ✓ Improved error message display
- ✓ Added loading state pada submit button

### 2. **Livewire Configuration**
- ✓ Created `config/livewire.php` untuk konfigurasi upload
- ✓ Set `temp_file_upload_disk` ke 'local'
- ✓ Set `temp_file_upload_path` ke 'livewire-tmp'
- ✓ Set `max_file_upload_size` ke 12MB

### 3. **Directory Setup**
- ✓ Created `storage/app/livewire-tmp/` directory
- ✓ Added `.gitignore` untuk temp files

### 4. **Form Improvements**
- ✓ Changed `wire:model` ke `wire:model.live` untuk real-time updates
- ✓ Added image preview using `$bankProof->temporaryUrl()`
- ✓ Added Alpine.js `:disabled` state untuk form controls
- ✓ Added error session flash handling
- ✓ Changed form submission ke `wire:submit.prevent`

### 5. **Error Handling**
- ✓ Added try-catch block di `submitPaymentProof()`
- ✓ Added error logging untuk debugging
- ✓ Added session error flash message

---

## 🔍 Checklist Testing

Untuk test upload file, ikuti langkah ini:

1. **Login sebagai buyer**
   ```
   URL: /dashboard
   ```

2. **Buat order / beli produk**
   ```
   Klik "Beli" pada produk
   → Checkout halaman
   → Klik "Lanjutkan Pembayaran"
   ```

3. **Halaman pembayaran**
   ```
   URL: /order/{orderId}/payment
   ```

4. **Step 1: Lihat instruksi pembayaran**
   ```
   ✓ Nomor rekening ditampilkan
   ✓ Jumlah transfer jelas
   ```

5. **Step 2: Upload bukti pembayaran**
   ```
   ✓ Klik "Sudah Transfer? Lanjutkan ke Langkah 2"
   ✓ Isi bank yang digunakan
   ✓ Isi nomor rekening pengirim
   ✓ Pilih tanggal transfer
   ✓ UPLOAD FILE GAMBAR
      - Drag & drop atau klik input
      - Pilih image (JPG, PNG, GIF)
      - Max 2MB
   ✓ File preview muncul dengan nama & size
   ✓ Isi catatan (opsional)
   ✓ Check confirmation checkbox
   ✓ Klik "Konfirmasi Pembayaran"
   ```

6. **Verify hasil**
   ```
   ✓ Button berubah menjadi "Memproses..." dengan loading spinner
   ✓ Redirect ke /order/{orderId} setelah selesai
   ✓ Order status berubah ke "Menunggu Pengiriman"
   ✓ Payment date terisi
   ✓ File tersimpan di storage/app/public/proofs/
   ✓ Transaction record terbuat
   ✓ Seller balance terupdate
   ```

---

## 📁 File Structure yang Dimodifikasi

```
laragon/www/sentripay/
├── app/
│   └── Livewire/
│       └── PaymentProcess.php ← Updated with try-catch & logging
│
├── config/
│   ├── filesystems.php (unchanged - already correct)
│   └── livewire.php ← NEW - Livewire configuration
│
├── resources/views/
│   └── livewire/
│       └── payment-process.blade.php ← Updated with preview & UX improvements
│
└── storage/
    ├── app/
    │   ├── livewire-tmp/ ← NEW - Temp uploads directory
    │   │   └── .gitignore
    │   └── public/
    │       └── proofs/ ← File storage after submission
    └── logs/ ← Check here for errors if issue persists
```

---

## 🐛 Debugging Steps (Jika Masih Ada Masalah)

### 1. Check Storage Symlink
```bash
php artisan storage:link
```

### 2. Check Permissions
```bash
# Windows (laragon sudah handle ini)
# Pastikan folder writable:
storage/app/livewire-tmp/
storage/app/public/
storage/logs/
```

### 3. Check Laravel Logs
```
storage/logs/laravel.log
```
Cari error messages terkait file upload.

### 4. Check Browser Console
```
F12 → Console
F12 → Network tab
```
Lihat apakah ada network error saat upload.

### 5. Check Livewire Debug
```php
// Di PaymentProcess.php, tambahkan:
\Log::info('File received:', [
    'name' => $this->bankProof?->getClientOriginalName(),
    'size' => $this->bankProof?->getSize(),
    'mime' => $this->bankProof?->getMimeType(),
]);
```

---

## 💡 Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| File input tidak muncul | CSS hidden | Cek browser dev tools |
| File tidak bisa dipilih | File size terlalu besar | Max 2MB, resize image |
| Upload stuck/loading terus | Server timeout | Check `php.ini` upload_max_filesize |
| Preview tidak muncul | temporaryUrl() tidak valid | Ensure Livewire configured correctly |
| File tersimpan tapi tidak terbuka | Symlink tidak aktif | Run `php artisan storage:link` |
| Validation error | Form data incomplete | Isi semua field yang required |

---

## 🔐 Security Notes

- ✓ Only accept image files (`accept="image/*"`)
- ✓ Max 2MB file size
- ✓ Validate MIME type di backend (`image` validation)
- ✓ Store files di `/storage/app/public/proofs/` (accessible)
- ✓ Only buyer yang upload untuk order mereka (auth check di PaymentProcess)

---

## ✨ Next Improvements (Optional)

1. **Image Compression** - Compress sebelum save
2. **Multiple Upload** - Allow multiple bukti
3. **Auto Zoom** - Allow zoom on image preview
4. **EXIF Data** - Extract metadata untuk security
5. **Admin Dashboard** - View all payment proofs
6. **Email Notification** - Notify admin saat upload

---

**Last Updated:** Dec 29, 2025  
**Status:** Working ✓
