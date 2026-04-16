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
