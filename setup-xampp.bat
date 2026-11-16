@echo off
REM ============================================================
REM  FUTH Lafia EMR - XAMPP 8.2.12 Windows Setup
REM  ============================================================
REM  This batch file:
REM    1. Installs Composer dependencies
REM    2. Generates APP_KEY
REM    3. Asks for DB credentials and updates .env
REM    4. Runs migrations and seeders
REM    5. Creates storage symlink
REM    6. Starts the dev server at http://localhost:8000
REM
REM  Run from inside the project root directory:
REM    setup-xampp.bat
REM ============================================================

setlocal

REM --- Step 1: Composer install ---
echo.
echo ============================================
echo  Step 1: Installing PHP dependencies...
echo ============================================
call composer install --no-dev --optimize-autoloader
if errorlevel 1 (
    echo [ERROR] Composer install failed. Make sure Composer is in your PATH.
    pause
    exit /b 1
)

REM --- Step 2: Environment file ---
if not exist ".env" (
    echo.
    echo ============================================
    echo  Step 2: Setting up .env file...
    echo ============================================
    copy .env.example .env > nul
    php artisan key:generate
)

REM --- Step 3: Configure database ---
echo.
echo ============================================
echo  Step 3: Database configuration
echo ============================================
echo Please make sure you created the database `futh_lafia_emr` in phpMyAdmin
echo (Collation: utf8mb4_unicode_ci).
echo.
echo Default XAMPP credentials are: root / (empty password).
echo If you use different credentials, edit .env manually before continuing.
echo.
pause

REM --- Step 4: Run migrations ---
echo.
echo ============================================
echo  Step 4: Running migrations...
echo ============================================
php artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Migration failed. Check your DB credentials in .env.
    pause
    exit /b 1
)

REM --- Step 5: Run seeders ---
echo.
echo ============================================
echo  Step 5: Seeding database with demo data...
echo ============================================
php artisan db:seed --force
if errorlevel 1 (
    echo [ERROR] Seeding failed.
    pause
    exit /b 1
)

REM --- Step 6: Storage link ---
echo.
echo ============================================
echo  Step 6: Creating storage symlink...
echo ============================================
php artisan storage:link

REM --- Step 7: Cache ---
echo.
echo ============================================
echo  Step 7: Caching config and routes...
echo ============================================
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:assets

REM --- Done ---
echo.
echo ============================================
echo  Setup complete!
echo ============================================
echo Open http://127.0.0.1:8000/admin/login in your browser.
echo.
echo Default credentials:
echo   admin@futhlafia.gov.ng / Futh@2026  (Super Admin)
echo   dr.okoro@futhlafia.gov.ng / Futh@2026  (Doctor)
echo   lab.aisha@futhlafia.gov.ng / Futh@2026  (Lab Scientist)
echo   pharm.musa@futhlafia.gov.ng / Futh@2026 (Pharmacist)
echo.
echo Press any key to start the dev server...
pause > nul

php artisan serve
