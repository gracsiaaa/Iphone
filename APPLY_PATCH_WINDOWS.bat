@echo off
setlocal
cd /d "%~dp0"

echo =============================================
echo  Username or Email Login - Laravel Patch
echo =============================================
echo.

echo Membersihkan cache Laravel...
php artisan optimize:clear
if errorlevel 1 goto :error

echo Menjalankan migration...
php artisan migrate
if errorlevel 1 goto :error

echo Mengompilasi frontend...
call npm run build
if errorlevel 1 goto :error

echo Menjalankan test...
php artisan test
if errorlevel 1 goto :error

echo.
echo Patch berhasil dipasang.
pause
exit /b 0

:error
echo.
echo Proses gagal. Periksa pesan error di atas.
pause
exit /b 1
