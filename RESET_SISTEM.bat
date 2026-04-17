@echo off
TITLE GARUDA DCS - Database Reset
echo ============================================================
echo           GARUDA DCS - DATABASE RESET
echo ============================================================
echo.
echo WARNING: INI AKAN MENGHAPUS SELURUH DATA LAMA ANDA!
set /p confirm="Apakah Anda yakin ingin melakukan reset? (y/n): "
if /i "%confirm%" neq "y" exit /b

echo.
echo [1/3] Memastikan Container sedang berjalan...
docker-compose up -d

echo.
echo [2/3] Melakukan Fresh Migration ^& Seeding...
docker exec -it dcs_app php artisan migrate:fresh --seed

echo.
echo [3/3] Membersihkan Cache...
docker exec -it dcs_app php artisan optimize:clear

echo.
echo ============================================================
echo   RESET DATABASE BERHASIL!
echo ============================================================
echo.
pause
