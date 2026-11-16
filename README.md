# FUTH Lafia EMR — Role Normalization & Reference Data Update

This package contains all the files needed to:

1. **Normalize role names to snake_case** across the entire codebase
2. **Create sessions and password_reset_tokens tables** (defensive — already in consolidated migration)
3. **Import large reference data** (ICD-10 codes, NACRS complaints, services catalog, Nigerian states & LGAs)
4. **Fix user roles and login** so dashboard KPIs, quick actions, and role badges all work

## What Changed

### Role Name Convention

**Before**: Roles used Title Case (`'Super Admin'`, `'Doctor'`, `'Medical Records Officer'`)
**After**: Roles use snake_case (`'super_admin'`, `'doctor'`, `'medical_records_officer'`)

All 12 roles in the project are now snake_case:

| Old Name                  | New Name                    |
|---------------------------|-----------------------------|
| Super Admin               | super_admin                 |
| Medical Administrator     | medical_administrator       |
| Doctor                    | doctor                      |
| Resident Doctor           | resident_doctor             |
| Nurse                     | nurse                       |
| Pharmacist                | pharmacist                  |
| Laboratory Scientist      | laboratory_scientist        |
| Radiographer              | radiographer                |
| Medical Records Officer   | medical_records_officer     |
| Accountant                | accountant                 |
| Insurance Officer         | insurance_officer           |
| Scientific Officer        | scientific_officer          |

### Files Updated

**Migrations** (4 files):
- `database/migrations/2026_09_03_000001_normalize_role_names_to_snake_case.php` — NEW, renames existing roles in DB
- `database/migrations/2026_09_03_000002_create_sessions_and_password_reset_tokens.php` — NEW, defensive
- `database/migrations/0000_00_00_000000_futh_emr_consolidated_migration.php` — fixed (removed invalid `after('id')` modifiers)
- `database/migrations/2026_09_11_000001_create_notifications_table.php` — fixed (removed duplicate morphs index)

**App code** (19 files):
- `app/Filament/Pages/*.php` — all 15 Filament pages with `hasRole()` calls updated
- `app/Filament/BaseResource.php` — updated
- `app/Filament/Traits/HasUnitScoping.php` — updated
- `app/Models/User.php` — updated (`isSuperAdmin()`, `isMedicalAdmin()`)
- `app/Models/DashboardWidgetSetting.php` — `ROLES` constant updated, added `resident_doctor` and `scientific_officer`

**Seeders** (6 files):
- `database/seeders/FuthPermissionsSeeder.php` — uses snake_case role names + `PascalCase:Resource` permission names (matches filament-shield config + Policies)
- `database/seeders/FuthEmrSeeder.php` — uses snake_case role names
- `database/seeders/StaffSeeder.php` — fixed `staff_type` enum values (Title Case) and role assignments (snake_case)
- `database/seeders/FuthModulesBatch3Seeder.php` — fixed `'occupation' => 'Nurse'` (was wrongly `'nurse'`)
- `database/seeders/DatabaseSeeder.php` — added call to `ReferenceDataSqlImporterSeeder`
- `database/seeders/ReferenceDataSqlImporterSeeder.php` — NEW, imports the 4 SQL data files

**SQL data files** (4 files in `database/seeders/sql/`):
- `01_icd10_codes_fixed.sql` — 73,889 ICD-10 diagnosis codes
- `02_presenting_complaints.sql` — 332 NACRS Nigeria presenting complaints
- `03_services.sql` — 9,306 services + 46 service categories
- `States and LGAs.sql` — Nigerian states and LGAs reference data

**Views** (2 files):
- `resources/views/filament/pages/dashboard.blade.php` — role badge displays Title Case (via `ucwords(str_replace('_', ' ', $role))`)
- `resources/views/filament/pages/dashboard-widget-settings.blade.php` — role names display as Title Case

**Role & Permission Cache Fix** (7 files) — fixes the "role changes don't reflect sometimes" issue:
- `app/Filament/Resources/Users/Schemas/UserForm.php` — replaced `Select::relationship('roles', 'name')` with `Select::options() + dehydrated(false)` so roles are saved via Spatie's `syncRoles()` (not direct pivot writes)
- `app/Filament/Resources/Users/Pages/EditUser.php` — calls `syncRoles()` in `afterSave`, flushes Spatie cache, invalidates user's active sessions
- `app/Filament/Resources/Users/Pages/CreateUser.php` — calls `assignRole()` in `afterCreate`, flushes Spatie cache
- `app/Filament/Resources/Roles/RoleResource.php` — replaced `CheckboxList::relationship('permissions', 'name')` with `CheckboxList::options() + dehydrated(false)` so permissions are saved via Spatie's `syncPermissions()`
- `app/Filament/Resources/Roles/Pages/EditRole.php` — calls `syncPermissions()` in `afterSave`, flushes Spatie cache
- `app/Filament/Resources/Roles/Pages/CreateRole.php` — calls `givePermissionTo()` in `afterCreate`, flushes Spatie cache
- `config/permission.php` — reduced Spatie cache TTL from 24 hours to 5 minutes (safety net)

**Why this fix matters**: Filament's `Select::relationship()` / `CheckboxList::relationship()` write directly to the pivot tables (`model_has_roles`, `role_has_permissions`), bypassing Spatie's `syncRoles()` / `syncPermissions()` methods. This leaves Spatie's permission cache stale, and `hasRole()` / `can()` checks return the old values until the cache expires (default: 24 hours). By using `->options()` + `->dehydrated(false)` and handling the save manually via `syncRoles()` / `syncPermissions()`, the cache is properly cleared on every change.

## Installation

### Step 1: Backup your current project

```cmd
cd C:\xampp\htdocs\futh-lafia-emr
xcopy /E /I /Y . \backup\futh-lafia-emr-%date:~10,4%%date:~4,2%%date:~7,2%
```

### Step 2: Extract this update package on top of your project

Extract the contents of this zip directly into `C:\xampp\htdocs\futh-lafia-emr\`, allowing it to overwrite existing files. The directory structure in the zip mirrors your project, so files will land in the right place.

### Step 3: Drop and recreate the database (cleanest path)

Open phpMyAdmin or MySQL command line:

```sql
DROP DATABASE IF EXISTS futh_lafia_emr;
CREATE DATABASE futh_lafia_emr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 4: Run migrations and seeders

```cmd
cd C:\xampp\htdocs\futh-lafia-emr
php artisan migrate:fresh --seed --force
php artisan optimize:clear
```

### Step 5: Start the server and log in

```cmd
php artisan serve
```

Visit http://127.0.0.1:8000/admin and log in:

| Email                              | Password    | Role                |
|------------------------------------|-------------|---------------------|
| admin@futhlafia.gov.ng             | Futh@2026   | super_admin         |
| dr.okoro@futhlafia.gov.ng          | Futh@2026   | doctor              |
| dr.adebayo@futhlafia.gov.ng        | Futh@2026   | doctor              |
| nurse.john@futhlafia.gov.ng        | Futh@2026   | nurse               |
| pharm.musa@futhlafia.gov.ng        | Futh@2026   | pharmacist          |
| lab.aisha@futhlafia.gov.ng         | Futh@2026   | laboratory_scientist|
| rad.tunde@futhlafia.gov.ng         | Futh@2026   | radiographer        |
| records.hauwa@futhlafia.gov.ng     | Futh@2026   | medical_records_officer |
| accounts.david@futhlafia.gov.ng    | Futh@2026   | accountant          |
| insurance.officer@futhlafia.gov.ng | Futh@2026   | insurance_officer   |
cashier.peter@futhlafia.gov.ng
php artisan schedule:work
## Verification

After logging in as `admin@futhlafia.gov.ng`:

1. **Role badge** in top-right should show "Super Admin" (Title Case)
2. **Dashboard title** should be "Administrator Dashboard"
3. **KPI cards** should display: Total Patients, New Patients, Encounters Today, Appointments, Patient Queue, Completed Visits, Revenue Today, Pending Rx, Pending Labs, Pending Radiology, Active Admissions, Bed Occupancy, Insurance Claims, Blood Units, Outstanding, Staff
4. **Quick Action buttons** should display: Register Patient, New Encounter, Book Appointment, New Prescription, Lab Order, Admit Patient, New Invoice, Record Payment, Insurance Claim
5. **Charts** should display: Insured vs Private, Appointment Trend, Queue Overview, Patients by Clinic, Encounter Types, Bed Status
6. **Side widgets** should display: Average Waiting Time, Longest Waiting Time, Average Length of Stay, Prescriptions Pending
7. **Recent Activities** should show recent registrations and appointments
8. **Doctor Worklist** should be visible for `doctor`, `resident_doctor`, `super_admin`, `medical_administrator` roles

## Troubleshooting

### "SQLSTATE[HY000]: General error: 1030 Got error 194"

Orphaned InnoDB tablespace. See the conversation history — run:
```sql
DROP TABLE IF EXISTS <problematic_table>;
```
Or do a full DB drop and recreate (Step 3 above).

### "SQLSTATE[42000]: 1064 Syntax error near 'after `id`'"

This is fixed by the updated `0000_00_00_000000_futh_emr_consolidated_migration.php` — make sure you overwrote it with the version in this zip.

### "SQLSTATE[42000]: 1061 Duplicate key name 'notifications_notifiable_type_notifiable_id_index'"

This is fixed by the updated `2026_09_11_000001_create_notifications_table.php` — make sure you overwrote it.

### Login fails with "Invalid credentials"

Make sure `FuthEmrSeeder` ran successfully. Check that the `users` table has rows:
```sql
SELECT id, email, name FROM users;
```
If empty, run:
```cmd
php artisan db:seed --class=FuthEmrSeeder --force
```

### KPI cards or buttons still missing

Clear all caches:
```cmd
php artisan optimize:clear
php artisan permission:cache-reset
php artisan view:clear
php artisan config:clear
```

Then hard-refresh your browser (Ctrl+Shift+R).

### ICD-10 / Services import failed

The SQL imports can fail if your MySQL/MariaDB has a small `max_allowed_packet` setting. To fix:

1. Open `C:\xampp\mysql\bin\my.ini`
2. Under `[mysqld]`, add or change: `max_allowed_packet=64M`
3. Restart MySQL from XAMPP Control Panel
4. Re-run the import:
   ```cmd
   php artisan db:seed --class=ReferenceDataSqlImporterSeeder --force
   ```

## What Was NOT Changed

- The `staff_type` enum column still uses Title Case values (`'Doctor'`, `'Nurse'`, etc.) — this is a database column constraint, not a role name
- The `futh_emr_full_dump.sql` file in seeders still has Title Case role names — this file is NOT used by the seeders, it's a legacy dump
- The `users_roles_relationship.sql` file from the upload was intentionally **not applied** — Spatie already provides a `model_has_roles` polymorphic pivot table that the codebase uses. Adding a separate `role_user` table would conflict.

## Architecture Notes

### Permission Naming Convention

The project uses **filament-shield's** permission naming convention:

- Format: `<Action>:<Resource>` (e.g., `ViewAny:Patient`, `Create:Invoice`)
- Actions: `ViewAny`, `View`, `Create`, `Update`, `Delete`, `DeleteAny`
- Resources: PascalCase model names (`Patient`, `Encounter`, `LabOrder`, etc.)

This matches:
- `config/filament-shield.php` (`case=pascal`, `separator=:`)
- `app/Policies/*.php` (e.g., `PatientPolicy` checks `'ViewAny:Patient'`)
- `app/Filament/Pages/Dashboard.php` (all `$user->can(...)` calls)

### Role Naming Convention

All role names are snake_case (`super_admin`, `medical_administrator`, etc.). This matches:
- `app/Models/User.php` (`isSuperAdmin()`, `isMedicalAdmin()`)
- All `hasRole()` calls throughout `app/`
- `app/Models/DashboardWidgetSetting.php` `ROLES` constant
- All seeder role assignments

### Display Convention

When showing role names to users, convert to Title Case using:
```php
ucwords(str_replace('_', ' ', $role))
```
This displays `super_admin` as `Super Admin`, `medical_administrator` as `Medical Administrator`, etc.
