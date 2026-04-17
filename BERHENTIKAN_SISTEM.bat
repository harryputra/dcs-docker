@echo off
TITLE GARUDA DCS - Shutdown System
echo ============================================================
echo           GARUDA DCS - SHUTDOWN SEQUENCE
echo ============================================================
echo.
echo [1/2] Menghentikan Docker Containers...
docker-compose stop

echo.
echo [2/2] Membersihkan Process Vite (jika ada)...
taskkill /F /IM node.exe /T >nul 2>&1

echo.
echo ============================================================
echo   SISTEM BERHASIL DIHENTIKAN.
echo ============================================================
echo Sampai jumpa kembali, Architect.
echo.
pause
