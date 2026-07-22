@echo off
setlocal
cd /d "%~dp0"
echo =============================================
echo  iPhone Reseller Store - Laravel Setup
echo =============================================
echo.
echo Pastikan database iphone_reseller_store sudah dibuat dan MySQL Laragon sedang aktif.
echo.
where composer >nul 2>nul || (echo Composer tidak ditemukan di PATH.& pause & exit /b 1)
where npm >nul 2>nul || (echo npm tidak ditemukan di PATH.& pause & exit /b 1)

if not exist .env copy .env.example .env
call composer install
if errorlevel 1 goto :error
php artisan key:generate
if errorlevel 1 goto :error
php artisan storage:link
php artisan migrate --seed
if errorlevel 1 goto :db_error
call npm install
if errorlevel 1 goto :error
call npm run build
if errorlevel 1 goto :error

echo.
echo Instalasi selesai. Buka http://iphone-reseller-store.test
echo Superadmin: superadmin@example.com / Superadmin123!
pause
exit /b 0

:db_error
echo.
echo Migrasi gagal. Periksa apakah database iphone_reseller_store sudah dibuat dan konfigurasi .env benar.
pause
exit /b 1

:error
echo.
echo Instalasi gagal. Periksa pesan error di atas.
pause
exit /b 1
