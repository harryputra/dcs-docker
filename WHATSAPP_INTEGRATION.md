# 📱 Integrasi WhatsApp dengan Fonnte

## 🎯 Fitur

Sistem ini terintegrasi dengan Fonnte API untuk mengirim notifikasi WhatsApp otomatis kepada user saat:

-   📄 Dokumen baru dibuat
-   🔔 Perlu approval dokumen
-   🔄 Status dokumen berubah
-   👤 User baru dibuat
-   📤 Dokumen lama diupload

## ⚙️ Setup

### 1. Daftar Fonnte

1. Kunjungi [https://fonnte.com](https://fonnte.com)
2. Daftar akun gratis (Free tier: 100 pesan/hari)
3. Hubungkan nomor WhatsApp Anda
4. Dapatkan API Token dari dashboard

### 2. Konfigurasi Laravel

Tambahkan ke file `.env`:

```env
# Fonnte WhatsApp Configuration
FONNTE_TOKEN=your_fonnte_token_here
FONNTE_API_URL=https://api.fonnte.com/send
```

### 3. Migrasi Database

Jalankan migration untuk menambah kolom `phone` dan `receive_all_notifications` ke tabel users:

```bash
php artisan migrate
```

### 4. Update Data User

Setelah setup, pastikan setiap user sudah mengisi nomor WhatsApp mereka di:

-   **Profile Edit** - User bisa update sendiri
-   **User Management** - Admin bisa set saat create user baru

Format nomor yang didukung:

-   `08123456789` (akan otomatis diubah ke 628123456789)
-   `628123456789` (format WhatsApp internasional)
-   `+628123456789` (dengan tanda plus)

## 📋 Penggunaan

### Untuk Admin

Saat membuat user baru, centang opsi **"Terima semua notifikasi dokumen"** jika admin ingin menerima notifikasi untuk semua dokumen (tidak hanya dokumen yang relevan dengan role mereka).

### Untuk User

1. Login ke sistem
2. Buka **Profile** → **Edit Profile**
3. Masukkan nomor WhatsApp di field **Phone (WhatsApp)**
4. Klik **Save Changes**

## 🔧 Struktur Kode

### Service Class

`app/Services/WhatsAppService.php` - Service utama untuk handle semua pengiriman WhatsApp

### Listeners yang Terintegrasi

-   `SendCreatedDocumentNotification` - Kirim notif dokumen baru
-   `SendApprovalDocumentNotification` - Kirim notif perlu approval
-   `SendStatusUpdateNotification` - Kirim notif status berubah
-   `SendNewUserNotification` - Kirim notif user baru
-   `SendOldDocumentNotification` - Kirim notif dokumen lama

### Config

`config/services.php` - Konfigurasi Fonnte API

## 🧪 Testing

Untuk test pengiriman WhatsApp, buat dokumen baru atau ubah status dokumen. Notifikasi akan dikirim otomatis ke nomor WhatsApp yang terdaftar.

## ⚠️ Catatan Penting

1. **Free Tier Limitation**: Fonnte free tier terbatas 100 pesan/hari
2. **Format Nomor**: Sistem otomatis format nomor ke 62xxx
3. **WhatsApp Status**: Pastikan nomor WhatsApp aktif dan terhubung
4. **Logging**: Semua aktivitas WhatsApp di-log di `storage/logs/laravel.log`

## 🐛 Troubleshooting

### Pesan tidak terkirim?

1. Cek `storage/logs/laravel.log` untuk error details
2. Pastikan `FONNTE_TOKEN` sudah benar di `.env`
3. Pastikan nomor WhatsApp user sudah terisi
4. Cek quota harian Fonnte (max 100 pesan/hari untuk free)

### Format nomor tidak valid?

Service otomatis format nomor, tapi pastikan nomor:

-   Hanya berisi angka (tanpa spasi/dash)
-   Dimulai dengan 0 atau 62
-   Minimal 10 digit

## 📊 Monitoring

Cek log untuk monitoring:

```bash
tail -f storage/logs/laravel.log | grep WhatsApp
```

## 🚀 Upgrade ke Premium

Jika butuh lebih dari 100 pesan/hari, upgrade paket Fonnte:

-   Basic: Rp 75.000/bulan
-   Pro: Rp 150.000/bulan
-   Business: Rp 300.000/bulan

Lihat detail di [https://fonnte.com/pricing](https://fonnte.com/pricing)

## 📞 Support

Jika ada masalah:

-   Email: support@fonnte.com
-   WhatsApp: 6281234567890
-   Dokumentasi: [https://fonnte.com/docs](https://fonnte.com/docs)
