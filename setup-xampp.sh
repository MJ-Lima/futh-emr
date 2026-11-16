#!/usr/bin/env bash
# ============================================================
#  FUTH Lafia EMR - Linux / macOS Setup (with XAMPP-like LAMP)
# ============================================================
# This script:
#   1. Installs Composer dependencies
#   2. Generates APP_KEY
#   3. Runs migrations and seeders
#   4. Creates storage symlink
#   5. Caches config, routes, views, Filament assets
#   6. Starts the dev server at http://localhost:8000
# ============================================================

set -e

# --- Step 1: Composer install ---
echo ""
echo "============================================"
echo " Step 1: Installing PHP dependencies..."
echo "============================================"
composer install --no-dev --optimize-autoloader

# --- Step 2: Environment file ---
if [ ! -f ".env" ]; then
    echo ""
    echo "============================================"
    echo " Step 2: Setting up .env file..."
    echo "============================================"
    cp .env.example .env
    php artisan key:generate
fi

# --- Step 3: Configure database ---
echo ""
echo "============================================"
echo " Step 3: Database configuration"
echo "============================================"
echo "Please make sure you created the database 'futh_lafia_emr' in phpMyAdmin"
echo "(Collation: utf8mb4_unicode_ci)."
echo ""
echo "Default XAMPP credentials are: root / (empty password)."
echo "If you use different credentials, edit .env manually before continuing."
echo ""
read -p "Press Enter to continue..."

# --- Step 4: Run migrations ---
echo ""
echo "============================================"
echo " Step 4: Running migrations..."
echo "============================================"
php artisan migrate --force

# --- Step 5: Run seeders ---
echo ""
echo "============================================"
echo " Step 5: Seeding database with demo data..."
echo "============================================"
php artisan db:seed --force

# --- Step 6: Storage link ---
echo ""
echo "============================================"
echo " Step 6: Creating storage symlink..."
echo "============================================"
php artisan storage:link

# --- Step 7: Cache ---
echo ""
echo "============================================"
echo " Step 7: Caching config and routes..."
echo "============================================"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:assets

# --- Done ---
echo ""
echo "============================================"
echo " Setup complete!"
echo "============================================"
echo "Open http://127.0.0.1:8000/admin/login in your browser."
echo ""
echo "Default credentials:"
echo "   admin@futhlafia.gov.ng / Futh@2026  (Super Admin)"
echo "   dr.okoro@futhlafia.gov.ng / Futh@2026  (Doctor)"
echo "   lab.aisha@futhlafia.gov.ng / Futh@2026  (Lab Scientist)"
echo "   pharm.musa@futhlafia.gov.ng / Futh@2026 (Pharmacist)"
echo ""
echo "Starting dev server..."
php artisan serve
