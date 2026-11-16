# FUTH Lafia EMR — Installation Guide

## Requirements

- PHP 8.2 or higher (PHP 8.4 recommended) — works with **XAMPP 8.2.12**
- Composer 2.x
- MySQL 8.0+ or MariaDB 10.6+ (utf8mb4_unicode_ci)
- Nginx or Apache (for production)

### Required PHP Extensions
- pdo, pdo_mysql, mbstring, xml, curl, zip, gd, bcmath, intl, readline, fileinfo, openssl

---

## Installation Steps

### 1. Extract the ZIP

```bash
unzip futh-lafia-emr.zip
cd futh-lafia-emr
```

### 2. Install PHP dependencies

```bash
composer install --no-dev --optimize-autoloader
```

> If you see a memory limit error, run: `COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader`

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```
APP_NAME="FUTH Lafia EMR"
APP_URL=http://localhost:8000   # or http://localhost/futh-lafia-emr/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=futh_lafia_emr
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Create the database

#### Option A — phpMyAdmin (XAMPP)

1. Open `http://localhost/phpmyadmin`
2. Click **Databases** → create new database:
   - Name: `futh_lafia_emr`
   - Collation: `utf8mb4_unicode_ci`

#### Option B — Command line

```bash
mysql -u root -p -e "CREATE DATABASE futh_lafia_emr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 5. Run migrations & seeders

**Option A — Fresh install (empty database, run all migrations + seeders):**

```bash
php artisan migrate --force
php artisan db:seed --force
```

**Option B — Import the full demo database (includes all seeded demo data):**

```bash
mysql -u root -p futh_lafia_emr < database/seeders/futh_emr_full_dump.sql
# Then run the new lab_architecture migration + lab seeder:
php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\LaboratoryAndLabUnitSeeder --force
```

### 6. Create storage symlink

```bash
php artisan storage:link
```

### 7. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000/admin/login`

### 8. (Optional) Configure XAMPP Apache virtual host

In `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/futh-lafia-emr/public"
    ServerName futhlafia.test
    <Directory "C:/xampp/htdocs/futh-lafia-emr/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 futhlafia.test
```

Restart Apache. Visit `http://futhlafia.test/admin/login`

---

## Default Login Credentials

| Email                                  | Password     | Role                      |
|----------------------------------------|--------------|---------------------------|
| admin@futhlafia.gov.ng                 | Futh@2026    | Super Admin               |
| dr.okoro@futhlafia.gov.ng              | Futh@2026    | Doctor                    |
| dr.adebayo@futhlafia.gov.ng            | Futh@2026    | Doctor (OBGYN)            |
| nurse.john@futhlafia.gov.ng            | Futh@2026    | Nurse                     |
| pharm.musa@futhlafia.gov.ng            | Futh@2026    | Pharmacist                |
| lab.aisha@futhlafia.gov.ng             | Futh@2026    | Laboratory Scientist      |
| rad.tunde@futhlafia.gov.ng             | Futh@2026    | Radiographer              |
| records.hauwa@futhlafia.gov.ng         | Futh@2026    | Medical Records Officer   |
| accounts.david@futhlafia.gov.ng        | Futh@2026    | Accountant                |
| insurance.officer@futhlafia.gov.ng     | Futh@2026    | Insurance Officer         |

---

## 4-Lab Architecture (new in this build)

The EMR is configured with **four laboratories**:

| Lab Code       | Lab Name                  | Type       | Purpose                                       |
|----------------|---------------------------|------------|-----------------------------------------------|
| DNA            | DNA Laboratory            | dna        | Outpatient molecular work (PCR, DNA testing) |
| MAIN           | Main Laboratory           | main       | Inpatient testing with 7 units (see below)    |
| ANNEX_LAB      | FUTH Annex Laboratory     | subsidiary | Subsidiary lab at FUTH Annex                  |
| TAIMAKO_LAB    | FUTH Taimako Laboratory   | subsidiary | Subsidiary lab at Taimako                      |

**Main Lab units** (7):

1. Haematology (HAEM)
2. Chemical Pathology (CHEM)
3. Microbiology (MICRO)
4. Parasitology (PARA)
5. Serology (SERO)
6. Histopathology (HISTO)
7. Blood Bank (BB)

Each `lab_test` is routed to a `lab_unit`, and each `lab_order` is routed to a `destination_lab`. Scientists only see orders routed to their lab.

---

## Lab Workflow

Each `LabOrder` row has 5 record actions in the **Lab Orders** table:

1. **Collect Sample** — visible while status = `Pending`. Sets `collected_at`, marks `received_by`, moves status → `Sample Collected`.
2. **Start Analysis** — visible while status = `Sample Collected`. Sets order and items → `In Progress`.
3. **Enter Result** — opens a dedicated **Enter Result** page (`/admin/lab-orders/{record}/result-entry`) listing every test item with:
   - Result Value (free text — `12.5`, `Positive`, etc.)
   - Numeric Value (for trending/graphing)
   - Unit (g/dL, mg/L, x10^9/L)
   - Reference Range (auto-filled from `lab_test`)
   - Flag toggle: Normal / Low / High / Critical Low / Critical High / Abnormal
   - Interpretation / Microscopy Narrative (e.g. "No parasites seen. Normal red cell morphology.")
   - Comments
   - Two submit buttons: **Save All Results** (keeps order In Progress) and **Save & Mark Complete** (marks order Completed).
4. **Verify & Approve** — visible when status = `Completed`. Marks each `lab_result.is_verified = true`, sets `verified_by` + `verified_at`, marks order `Verified`.
5. **Transfer to Lab** — visible while status ∈ `Pending`, `Sample Collected`, `In Progress`. Form requires `to_laboratory_id`, `reason`, optional `notes`. Creates a `lab_transfers` row (full audit trail), updates `lab_orders.destination_lab_id` to the new lab. The receiving lab sees the order in their list and can continue processing.

---

## Print & PDF Download

When the order is `Completed` or `Verified`, the **Print / PDF** action opens a print-optimized HTML report at `/admin/lab-orders/{id}/print`:

- Hospital letterhead with circular emblem ("HEALTH IS WEALTH" arc, FUTH logo) and green brand colour (#0f5132)
- Patient demographics block (Name, Hospital #, Age/Sex, Phone, Specimen source, Fasting status, Clinical notes)
- Per-unit result tables grouped by Lab Unit (Haematology table, then Microbiology table, etc.) showing Test, Result, Unit, Reference Range, Flag (colour-coded), interpretation, comments
- Signature lines for **Scientist** and **Senior Scientist (Verifier)** with date
- "UNVERIFIED" watermark (rotated, semi-transparent red) appears diagonally across the report when the order status is not yet `Verified`
- Toolbar buttons: 🖨️ Print (browser dialog), ⬇️ Download PDF (DomPDF-rendered file `Lab-Report-LAB-2026-000001.pdf`), ← Back to admin

---

## Pharmacy Dispensing Workflow

The **Prescriptions** resource (Pharmacy nav group) has two record actions:

- **Dispense All** — one-click full dispense. Iterates all pending items, calls FEFO (First-Expiry-First-Out) batch decrement, creates `stock_movements` and `dispensing_logs` for each item, marks prescription status → `Dispensed`.
- **Partial Dispense** — opens a form listing each item with current quantity, remaining (qty − qty_dispensed), and available batch stock. Pharmacist enters how many units to dispense per item. Stock is decremented from the oldest-expiring non-expired batch first. Items where `quantity_dispensed >= quantity` are marked `Dispensed`; others `Partially Dispensed`. Prescription status becomes `Partially Dispensed` until all items are fully dispensed, then `Dispensed`.

A persistent **low-stock alert** fires whenever a drug's total stock (sum across batches) drops at or below its `reorder_level`.

---

## Branded Login Page

The login page matches your screenshot — a centred card with:

- Hospital circular emblem: white circle with green (#22C55E) border, "HEALTH IS WEALTH" arc text at top, "FEDERAL UNIVERSITY TEACHING HOSPITAL" arc text at bottom, green plus sign in the middle
- **FUTH EMR** brand name in green, large bold
- Hospital subtitle in uppercase letter-spaced text
- White login card (max-width 480px) with "Welcome Back" header, "Sign In" subtitle
- Username field, Password field with eye-toggle for visibility
- Remember me checkbox + Forgot Password link (green)
- Full-width green Login button

View: `resources/views/filament/pages/auth/custom-login.blade.php`
Class: `app/Filament/Pages/Auth/CustomLogin.php`

---

## Modules (93 Filament resources)

| Nav group        | Resources                                                                                                              |
|------------------|-----------------------------------------------------------------------------------------------------------------------|
| Front Desk       | Patients, Encounters, Appointments, Appointment Types, Appointment Slots, Clinic Schedules, Provider Schedules, Holidays |
| Clinical         | Vitals, Clinical Notes, Allergies, Diagnoses, Discharge Summaries, Procedures, Procedure Categories, Procedure Bookings, Procedure Sessions |
| Pharmacy         | Prescriptions (with dispensing workflow), Drugs, Drug Batches, Drug Categories, Drug Stock Levels, Dispensing Points, Stock Movements, Stock Takes, Suppliers, Goods Received Notes |
| Laboratory       | Lab Orders (with Collect/Start/EnterResult/Verify/Transfer/Print), Lab Panels, Lab Tests, **Laboratories**, **Lab Units**, **Lab Transfers** |
| Radiology        | Radiology Modalities, Radiology Orders                                                                                 |
| Inpatient        | Wards, Beds, Admissions, Bed Transfers                                                                                 |
| Insurance        | Insurance Providers, Insurance Plans, Patient Insurance, Pre-Authorizations (Claims not yet wired up)                |
| General Stores   | Store Locations, Categories, Items, Stocks, Movements, Requisitions, General Store Locations/Categories/Items/Stocks/Movements/Requisitions |
| Blood Bank       | Blood Donors, Donations, Blood Units, Requests, Crossmatches, Transfusions                                           |
| Audit            | Audit Categories, Medical Audits + Findings, Internal Audits + Findings, Audit Follow-ups                             |
| Accounts         | Chart of Accounts, Journal Entries + Lines, Budgets, Expense Claims, Petty Cash Transactions                         |
| Administration   | Users, Departments, Hospitals, Clinics, Duty Rosters, Duty Postings, Services, Service Categories, Roles, Leave Requests, Report Templates, Saved Reports, Report Schedules, Patient Dependants, Relationships |

---

## Troubleshooting

### "No application encryption key has been specified"
Run: `php artisan key:generate`

### Composer install fails with extension errors
Make sure your `php.ini` has these extensions enabled:
```
extension=pdo_mysql
extension=mbstring
extension=xml
extension=curl
extension=zip
extension=gd
extension=bcmath
extension=intl
extension=readline
extension=fileinfo
```

### Migration fails with "Cannot add foreign key constraint"
Make sure the database is `utf8mb4_unicode_ci` collation and that you run the consolidated migration first (it's already ordered correctly by migration filename `0000_00_00_000000_...`).

### Login fails (403 / "These credentials do not match")
Run: `php artisan db:seed --force` (the seeder resets all user passwords to `Futh@2026`).

### Filament assets missing / UI broken
Run:
```bash
php artisan filament:assets
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 500 error on `/admin/login`
Check `storage/logs/laravel.log` for the exact error. Common causes:
- Missing `.env` (run `php artisan key:generate`)
- `storage/` directory not writable (run `chmod -R 775 storage bootstrap/cache`)
- Database not created or credentials wrong in `.env`

---

## Project structure

```
futh-lafia-emr/
├── app/
│   ├── Filament/
│   │   ├── Pages/Auth/CustomLogin.php              ← branded login page
│   │   ├── Resources/                              ← 93 resources (split architecture)
│   │   │   ├── LabOrders/
│   │   │   │   ├── LabOrderResource.php
│   │   │   │   ├── Pages/                           ← incl. EnterLabResult (per-item result entry)
│   │   │   │   ├── Schemas/                         ← LabOrderForm with destination_lab_id + items repeater
│   │   │   │   └── Tables/LabOrdersTable.php         ← Collect/Start/Result/Verify/Transfer/Print actions
│   │   │   ├── Laboratories/                       ← NEW: 4-lab CRUD
│   │   │   ├── LabUnits/                            ← NEW: 7 main lab units CRUD
│   │   │   ├── LabTransfers/                        ← NEW: lab-to-lab transfer audit trail
│   │   │   ├── Prescriptions/                       ← with Dispense All + Partial Dispense actions
│   │   │   ├── ... (89 other resources)
│   │   ├── Traits/HasUnitScoping.php
│   │   ├── Pages/Dashboard.php
│   │   └── Widgets/
│   ├── Http/Controllers/
│   │   └── LabOrderPrintController.php             ← print + PDF
│   ├── Models/                                     ← 110 Eloquent models
│   │   ├── LabOrder.php (updated with destinationLab + transfers relations)
│   │   ├── LabOrderItem.php (updated with unit + result hasOne)
│   │   ├── LabTest.php (updated with unit relation + reference_range accessor)
│   │   ├── LabPanel.php (updated with unit relation)
│   │   ├── Laboratory.php                          ← NEW
│   │   ├── LabUnit.php                             ← NEW
│   │   ├── LabTransfer.php                         ← NEW
│   │   ├── Drug.php (with stock_qty + is_low_stock accessors)
│   │   └── ...
│   └── Providers/Filament/AdminPanelProvider.php   ← green FUTH theme + custom login + nav groups
├── database/
│   ├── migrations/
│   │   ├── 0000_00_00_000000_futh_emr_consolidated_migration.php  ← all 102 tables
│   │   ├── 2026_07_28_172512_create_schedule_configurations_table.php
│   │   └── 2026_09_10_000100_create_lab_architecture_tables.php   ← NEW: labs + units + transfers + destination_lab_id
│   └── seeders/
│       ├── DatabaseSeeder.php                       ← calls FuthPermissions + FuthEmr + LaboratoryAndLabUnit
│       ├── FuthPermissionsSeeder.php                ← role → permission matrix
│       ├── FuthEmrSeeder.php                        ← hospitals, depts, users, insurance, lab tests, drugs
│       ├── LaboratoryAndLabUnitSeeder.php           ← NEW: 4 labs + 7 main units + 3 subsidiary units
│       ├── futh_emr_full_dump.sql                   ← full demo database dump (MySQL)
│       └── ... (22 more seeders)
├── resources/views/
│   ├── filament/pages/auth/custom-login.blade.php   ← matches your screenshot
│   ├── lab-orders/print.blade.php                   ← lab report with letterhead + UNVERIFIED watermark
│   └── ...
├── public/
│   ├── futh-logo.jpeg                              ← hospital logo
│   └── css/futh-emr-theme.css                       ← green theme overrides
├── .env.example                                     ← XAMPP MySQL config
├── composer.json                                    ← Laravel 12 + Filament v5 + Spatie + filament-shield + DomPDF
└── INSTALL.md                                       ← this file
```

---

## Tech stack

| Layer        | Tech                                                                  |
|--------------|----------------------------------------------------------------------|
| Backend      | Laravel 12.x (PHP 8.2+ — XAMPP 8.2.12 compatible)                     |
| Admin UI     | Filament v5.x + Livewire v3 + Tailwind                                  |
| Auth / RBAC  | Spatie laravel-permission + bezhansalleh/filament-shield              |
| PDF          | barryvdh/laravel-dompdf (lab result reports)                         |
| Database     | MySQL 8 / MariaDB 10+ (utf8mb4_unicode_ci)                           |

## Licence

Internal use — Federal University Teaching Hospital, Lafia.
