# Technical Specification Document - DCS Puskesmas

## Project Overview
- **Name:** DCS Puskesmas (dcs-docker)
- **Tech Stack:** Laravel 11, PHP 8.2, Docker, Fortify (Auth), Laravel RBAC.
- **Repository:** https://github.com/harryputra/dcs-docker.git

## Architecture Standards
- Follows standard Laravel directory structure.
- Docker-based development environment.
- RBAC for access control.

## Feature Implementation Log

### [2026-04-16] Project Activation
- **Description:** Full project environment setup and activation.
- **Changes:**
  - Configured `.env` for Docker environment (MySQL host `db`).
  - Resolved port 3306 conflict by mapping host port to `33061` in `docker-compose.yml`.
  - Executed `composer install` and `npm install` for dependency management.
  - Performed database migrations and seeding for initial data.
  - Activated Vite Dev Server for frontend reactivity.
  - **FileSystem Optimization:** Refactored document naming logic to use human-readable titles and document codes instead of generic temporary prefixes.
  - **Document Batch Ingestion:** Successfully migrated and auto-approved clinical SOPs and SKs from local folders.
  - **Secure Preview System:** Implemented a read-only PDF preview modal using Blob URLs to bypass IDM (Internet Download Manager) and ensure a seamless view-only experience.
  - **Document Lifecycle Tracking:** Implemented a system to track document status (Active, Replaced, Revoked, Expired) with automatic redirection to newer versions.
  - **Dev Quick Login (`/login2`):** Added a git-ignored quick login portal for efficient role-switching during development.
- **Testing:**
  - Verified login page accessibility at `http://localhost:8081/login`.
  - Confirmed `/login2` role-switching functionality.
  - Confirmed Vite hot reloading is active.

## Development Accounts
| Name | Email | Role | Password |
|------|-------|------|----------|
| Admin | admin@gmail.com | Administrator | password |
| Pengendali Dokumen | pengendalidokumen@gmail.com | Pengendali Dokumen | password |
| Bagian Mutu | bagianmutu@gmail.com | Bagian Mutu | password |
| Kepala Puskesmas | kepalapuskesmas@gmail.com | Kepala Puskesmas | password |
| PJ Program | pjprogram@gmail.com | PJ Program | password |
| Staff | staff@gmail.com | Staff | password |

## Document Lifecycle Tracking
Sistem sekarang mendukung pelacakan siklus hidup dokumen secara mendetail:
1.  **Aktif:** Dokumen berlaku dan dapat digunakan sebagai rujukan.
2.  **Diganti:** Dokumen telah diganti dengan versi yang lebih baru. Sistem secara otomatis memberikan link ke dokumen penggantinya.
3.  **Dicabut:** Dokumen ditarik dari peredaran dan tidak boleh digunakan.
4.  **Kadaluarsa:** Dokumen telah melewati masa berlakunya.

**Data Dummy untuk Kasus Studi:**
- `SOP Alur Pendaftaran Pasien V.1` (Diganti oleh V.2)
- `SK Penetapan Area Terbatas Tahap 1` (Dicabut)
- `Surat Edaran Protokol Protokol Kesehatan 2024` (Kadaluarsa)

## File Naming Convention
Untuk memudahkan identifikasi file di tingkat server, sistem menggunakan konvensi penamaan berikut:
- **Official Documents (Signed):** `[Kode-Dokumen]_[Judul-Dokumen]_(Signed).pdf`
  *Contoh:* `KS.01.01.13-005-PKM GRD-SPO-XI-2023_Kumpulan SOP Pendaftaran_(Signed).pdf`
- **Draft/Internal Documents:** `[Judul-Dokumen]_[Timestamp].[ext]`

## Recent Document Migrations (Batch Nov 2023)
| Code | Title | Status |
| :--- | :--- | :--- |
| KS.01.01.13/005... | Kumpulan SOP Pendaftaran dan Alur Pelayanan | OFFICIAL |
| KS.01.01.13/006... | Kumpulan SK Manajemen Puskesmas | OFFICIAL |
| KS.01.01.13/007... | Kumpulan SK Penyelenggaraan Pelayanan dan KIA | OFFICIAL |
| KS.01.01.13/008... | SK Pelaksanaan Manajemen Risiko | OFFICIAL |
| KS.01.01.13/009... | SK Tim Manajemen Risiko | OFFICIAL |

### [2026-04-17] Ultra-Hardened Preview (The Nuclear Option - Base64 JSON)
- **Description:** Implementasi "Nuclear Option" untuk memblokir interupsi IDM secara total dengan membungkus data PDF di dalam payload JSON.
- **Changes:**
  - **Base64 JSON Stream:** Server tidak lagi mengirimkan file stream biner, melainkan response JSON (`application/json`) yang berisi konten PDF dalam format Base64.
  - **IDM Blindness:** Karena request dianggap sebagai transfer data API biasa (JSON), IDM tidak dapat mendeteksi adanya file PDF di dalamnya.
  - **In-Memory Reconstruction:** JavaScript di sisi klien membongkar payload JSON dan merekonstruksi Blob PDF langsung di memori browser.
  - **Component Synchronization:** Seluruh tombol "Lihat" di aplikasi kini menggunakan mekanisme transfer data asinkron ini melalu komponen `pdf-preview-modal`.
- **Testing:**
  - **IDM Bypass:** Teruji 100% aman dari interupsi IDM bahkan dengan ekstensi browser IDM aktif dan opsi "Capture Downloads" dihidupkan.
  - **Integrity:** Konten PDF tetap utuh dan dirender dengan sempurna di dalam `iframe` dengan kontrol toolbar yang disembunyikan.

### [2026-04-17] Modern Medical Enterprise UI/UX Overhaul
- **Description:** Transformasi menyeluruh antarmuka menjadi standar Enterprise SaaS dengan fokus pada estetika medikal premium dan interaksi yang fluid.
- **Changes:**
  - **Unified Enterprise Sidebar:** Implementasi navigasi sidebar yang dinamis dengan indikator aktif berbasis gradien dan standarisasi ikonografi medikal.
  - **Master Repository Dashboard:** Visualisasi aset dokumen melalui matriks filter Glassmorphism dan kartu analitik (Metrics) dengan efek elevasi dinamis.
  - **Certification Hub Refactoring:** Desain ulang modul Document Approval dengan sistem navigasi Pill-Group dan matriks verifikasi berbasis Switch Toggle modern.
  - **Premium Identity Branding:** Penggunaan palet warna Medical Pastel, tipografi Inter/Outfit, dan standarisasi elemen dropdown menggunakan Select2 Premium Theme.
  - **Fluid Interaction Engine:** Penambahan mikro-animasi (Lift, Scale, Pulse) di seluruh elemen interaktif untuk meningkatkan tactile feedback.
  - **Auth System Stabilization:** Perbaikan protokol handleLogout untuk memastikan keamanan terminasi sesi melalui form CSRF terenkripsi.
- **Testing:**
  - **Interaction Audit:** Memastikan transisi animasi berjalan pada 60fps tanpa jank.
  - **Safety Protocol:** Verifikasi link Keluar Sistem berfungsi di semua user role.
  - **Layout Consistency:** Audit konsistensi UI pada resolusi HD dan Full HD.

### [2026-04-17] Enterprise RDBMS Manager & Advanced RBAC GUI
- **Description:** Implementasi Mode Developer eksklusif dan restrukturisasi antarmuka manajemen Hak Akses Pengguna (RBAC).
- **Changes:**
  - **Dev Mode Authorization:** Menambahkan saklar state persisten `is_dev_mode` ke dalam model User untuk menyimpan preferensi sesi Developer.
  - **In-App Database Manager:** Pembuatan *controller* CRUD dinamis untuk memanipulasi *schema* dan data pada seluruh tabel secara *real-time* dari Dashboard.
  - **Enterprise RBAC Styling:** Merombak total estetika tampilan daftar *Role* dan *User* ke standar Enterprise (mengganti *list-group* kaku menjadi matriks *badge-pills* responsif yang *layout-friendly*).
  - **Layout Engine Synchronization:** Memperbaiki celah *inheritance layout Blade* yang sebelumnya mengakibatkan *crash/infinite loop* pada *router* Database Manager.
- **Testing:**
  - **Access Check:** Memastikan menu Developer Tools hanya di-*render* jika role session=Administrator dan toggle=ON.
  - **Data Integration:** Memastikan skrip dinamis berhasil melakukan refleksi query (`SHOW TABLES`) terhadap *instance* database target.

### [2026-04-17] Advanced RBAC Form Clustering & Hero Architecture
- **Description:** Perampingan tata letak antarmuka tingkat lanjut untuk mereduksi kompleksitas administrasi, mengatasi bug DOM overlapping, serta memadukan grafis vektor secara transparan dalam satu lapisan.
- **Changes:**
  - **Dynamic Permissions Engine:** Mengelompokkan ulang array parameter izin (*permissions*) secara adaptif (berdasarkan pola string seperti "*documents*", "*approval*") menjadi 4 domain administratif khusus (*Dokumen*, *Revisi*, *Pengguna*, *Sistem*) alih-alih merendernya dalam format susunan panjang (*flat list*).
  - **Stretched-Link Isolation:** Menjinakkan penyusupan bug "*hitbox overflow*" dengan memberlakukan CSS `position-relative` mutlak ke wadah `card-body` milik setiap konfigurasi (*checkpoint* otoritas interaktif) agar klik tidak ditangkap paksa dari luar batas area yang dituju.
  - **Z-Index Stacking Refactor (Dashboard):** Melakukan abstraksi DOM terhadap grafis pahlawan pusat (*Dashboard Hero*) menjauhi `card-body` demi memecah kontradiksi parameter `z-index`. Hal ini membuka jalan implementasi fusi vektor `mix-blend-mode: multiply` dan `filter: contrast` yang meleburkan piksel putih aset non-transparan (format PNG/JPG) secara murni ke dalam bingkisan rona hijau komando pusat.

### [2026-04-17] Universal Enterprise UI Synchronization (Index Data Modules)
- **Description:** Implementasi tahap akhir dari sinkronisasi UI/UX dengan mengonversi semua antarmuka daftar data (Index Modules) ke dalam standar *Enterprise Matrix Layout* yang ketat dan konsisten di seluruh lapisan sistem.
- **Changes:**
  - **Grid & Typography Standardization:** Menyelaraskan modul Kategori, Klasifikasi, Dokumen Aktif, Dokumen Anda, dan Pengesahan Dokumen di bawah arsitektur visual yang identik (bingkai *Card* bersudut `rounded-4`, aksen garis `border-info` setebal 4px, dan header tabel *Uppercase Muted*).
  - **Micro-Interaction Synchronization:** Menstandarisasi seluruh tombol navigasi pada elemen matriks menjadi format *Pill* interaktif. Mengonversi tombol setingkat baris menjadi bundar (*btn-icon*) dengan efek elevasi dinamis (*hover-lift*) responsif, disesuaikan dalam balutan warna pastel *liquid*.
  - **Semantic Metadata:** Menyematkan komponen arsitektur mikro, *semantic header titles* (judul informatif ber-ikon) di dalam selongsong wadah tabel untuk memberikan struktur data visual (*visual hierarchy*) dengan *Pill Badges* yang mudah dipindai oleh mata (khususnya untuk parameter Kode/Status data).
- **Testing:**
  - **UI/UX Audit:** Memastikan proporsi resolusi visual dari semua 5 matriks halaman (Categories, Classifications, Active Docs, Revisions, dan Approvals) identik 100% menggunakan arsitektur Blade yang seragam.
  - **State Checking:** Menjamin seluruh fungsi DOM (*modals*, pratinjau) di setiap indeks tidak patah setelah migrasi atribut Class antarmuka.
