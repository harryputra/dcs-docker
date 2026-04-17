@echo off
TITLE GARUDA DCS - Automatic Startup System
SETLOCAL EnableDelayedExpansion

:: --- CONFIGURATION ---
SET WEB_URL=http://localhost:8081
SET COMPOSE_FILE=docker-compose.yml

echo ============================================================
echo           GARUDA DCS - ANTIGRAVITY ARCHITECT
echo ============================================================
echo [1/4] Memeriksa status Docker...
docker info >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo.
    echo [ERROR] Docker Desktop belum dijalankan!
    echo Silakan buka aplikasi Docker Desktop terlebih dahulu.
    echo.
    pause
    exit /b
)

echo [2/4] Memulai Docker Containers...
docker-compose up -d

echo.
echo [2/4] Menunggu Database siap...
timeout /t 5 /nobreak > nul

echo.
echo [3/4] Menjalankan Vite Dev Server (Frontend Assets)...
:: Menggunakan start untuk membuka jendela baru agar logs Vite terlihat
start "GARUDA DCS - VITE SERVER" cmd /k "npm run dev"

echo.
echo [4/4] Membuka Dashboard Sistem...
start %WEB_URL%

echo.
echo ============================================================
echo   SISTEM BERHASIL DIJALANKAN!
echo   - Website  : %WEB_URL%
echo   - Database : localhost:33061
echo ============================================================
echo JANGAN TUTUP jendela CMD Vite yang terbuka untuk menjaga
echo fitur Hot-Reload tetap aktif.
echo.
pause
