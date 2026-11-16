# DATABASE SYNC — FUTH Lafia EMR

This document explains how to bring your **live database** (`futh_lafia_emr.sql`
phpMyAdmin dump) and the **codebase** into the same shape after the public
portal update.

---

## TL;DR — pick your path

| Your situation | What to run |
|---|---|
| **A.** I already have the live DB running and just want to add the public portal features on top. | Run `database/sql/futh_lafia_emr_public_portal_patch.sql` against your DB. Skip the seed file — you already have data. |
| **B.** I am setting up a fresh install from the codebase + the live SQL dump. | 1. Import `futh_lafia_emr.sql` (or the schema-only subset).<br>2. Run `php artisan migrate` (Laravel sees the new `2026_11_01_*` migrations as pending and runs them).<br>3. Optionally run `database/sql/futh_lafia_emr_seed_data.sql` if you want the live demo data (patients, ICD-10 codes, drugs, etc.). |
| **C.** I am setting up a fresh install from the codebase only (no live dump), want a working demo. | `php artisan migrate --seed` — runs all migrations (including new portal ones) and all seeders, including the new `PublicPortalRolesSeeder` and `PublicHospitalDirectorySeeder`. |
| **D.** I have the live DB (v2.2) and want the **NHIA 2025 price list + special services** (v2.3). | Run `php artisan migrate` then the two new seeders, **or** simply import `database/sql/v2_3_services_entity_update.sql` via phpMyAdmin — it contains the schema changes, the 1,158 NHIA 2025 services (NHIS + NASHIA tariffs) and the 12 special services in one idempotent file. See `CHANGELOG_V2.3_SERVICES.md`. |

---

## What changed in this update (DB-wise)

The schema diff against the live dump came back **clean** — the codebase
migrations 1–21 already reproduce the live DB schema exactly (zero missing
columns across all 140 shared tables). The only changes are additions:

### 1. `hospitals` table — extended with 21 new public-directory columns

Added by `2026_11_01_000001_create_public_portal_tables.php` (Laravel) and
mirrored as raw SQL in `futh_lafia_emr_public_portal_patch.sql`:

```
type, tagline, description,
address_line1, address_line2, city, state, country, postal_code,
phone_main, phone_alt, email, website,
logo_path, cover_path, latitude, longitude,
services (JSON), operating_hours (JSON),
is_public (BOOL), accepts_online_registration (BOOL)
```

### 2. Four new tables

| Table | Purpose |
|---|---|
| `patient_portal_users` | Patient self-service portal logins (separate from staff `users`) |
| `provider_applications` | Doctor / hospital registration applications (admin approval queue) |
| `patient_portal_messages` | Two-way patient ↔ hospital messaging |
| `patient_portal_password_reset_tokens` | Password reset tokens for portal users |

### 3. Two new roles

Added to `roles` table:
- `private_doctor` — assigned to users created when a doctor application is approved
- `hospital_administrator` — assigned to users created when a hospital application is approved

### 4. Two new migrations for legacy geography tables

`2026_11_01_000002_create_legacy_geography_tables.php` creates the `lgas`
and `states_tb` lookup tables with the **exact same legacy schema** the live
DB uses (`LGID`/`NAME`/`STATE_CODE`/`STATEID`, `stateID`/`code`/`stateNm`).
Previously these were created by `ReferenceDataSqlImporterSeeder` only,
which meant a fresh `php artisan migrate` (without `--seed`) would leave the
patient registration form unable to populate its LGA dropdown. They are now
created by a real migration; the actual data load still happens via the
seeder SQL.

---

## Path A: existing live DB → add public portal

```bash
# From your MySQL / phpMyAdmin, run:
mysql -u root -p futh_lafia_emr < database/sql/futh_lafia_emr_public_portal_patch.sql
```

The patch is **idempotent** — safe to re-run. It will:

1. Add the 21 new columns to `hospitals` (each guarded by an
   `information_schema.COLUMNS` check — skips if the column already exists).
2. Create the 4 new portal tables (uses `CREATE TABLE IF NOT EXISTS`).
3. Insert the 2 new provider roles (uses `INSERT IGNORE`).
4. Create the `lgas` and `states_tb` tables if they don't exist (no-op if
   you already have them — which you do).
5. Register the new migrations in the `migrations` table so a subsequent
   `php artisan migrate` won't try to re-run them.

After running the patch, clear Laravel's cached config and routes:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

Then login to `/admin` as super_admin → you'll see three new resources in
the *Administration* group (Provider Applications, Patient Portal Users) and
one in *Front Desk* (Patient Messages). The public site lives at `/`.

> **Note:** If you also want the 6 sample network hospitals in the public
> directory (FUTH Lafia branches across Nasarawa + Abuja telemedicine hub),
> run `php artisan db:seed --class=PublicHospitalDirectorySeeder` after the
> patch. They use `INSERT IGNORE`/`updateOrCreate` so they won't duplicate
> your existing hospital rows.

---

## Path B: fresh codebase + live SQL dump

```bash
# 1. Create the database and import the live dump
mysql -u root -p -e "CREATE DATABASE futh_lafia_emr"
mysql -u root -p futh_lafia_emr < /path/to/futh_lafia_emr.sql

# 2. Update .env to point at the new DB
# DB_DATABASE=futh_lafia_emr

# 3. Run any pending migrations — Laravel will see only the two new
#    2026_11_01_* migrations as pending (the patch already registered
#    them in the migrations table when you imported the dump).
php artisan migrate

# 4. (Optional) Seed the public-portal-specific data:
php artisan db:seed --class=PublicPortalRolesSeeder
php artisan db:seed --class=PublicHospitalDirectorySeeder

# 5. Clear caches and you're done
php artisan config:clear && php artisan route:clear && php artisan cache:clear
```

If you want to skip the live dump and rebuild from scratch:

```bash
php artisan migrate --seed
```

This runs all 23 migrations (including the two new `2026_11_01_*` ones)
and all seeders. The `PublicHospitalDirectorySeeder` and
`PublicPortalRolesSeeder` are wired into `DatabaseSeeder` step 8.

---

## Path C: completely fresh codebase, no live data

```bash
php artisan migrate --seed
```

That's it. All migrations run, all seeders run, including the new public
portal ones. The demo data will include:

- 1 default hospital (FUTH Lafia)
- 21 departments
- 37 clinics
- 17 staff roles + permissions
- 12 staff users (super_admin, doctor, nurse, pharmacist, etc.)
- 1133 ICD-10 diagnosis codes
- 101 presenting complaints
- 6 sample network hospitals (FUTH Lafia + branches across Nasarawa + Abuja telemedicine hub)
- 2 new provider roles (`private_doctor`, `hospital_administrator`)

Default staff login: `admin@futhlafia.gov.ng` / `Futh@2026`

---

## Files added in this update

| File | Purpose |
|---|---|
| `database/migrations/2026_11_01_000001_create_public_portal_tables.php` | New Laravel migration — extends `hospitals`, creates 4 portal tables |
| `database/migrations/2026_11_01_000002_create_legacy_geography_tables.php` | New Laravel migration — creates `lgas` + `states_tb` |
| `database/sql/futh_lafia_emr_public_portal_patch.sql` | **Standalone SQL patch** — apply this to your live DB instead of running `php artisan migrate` if you want to upgrade the live DB in place (idempotent) |
| `database/sql/futh_lafia_emr_seed_data.sql` | **Standalone SQL seed** — captures all lookup data from the live DB (1133 ICD-10 codes, drugs, lab tests, services, insurance plans, demo patients/users/encounters, etc.) as `INSERT IGNORE` statements. Use this if you want to populate a fresh codebase install with the same starting dataset the live DB has. |
| `database/seeders/PublicPortalRolesSeeder.php` | Laravel seeder — adds the 2 new provider roles |
| `database/seeders/PublicHospitalDirectorySeeder.php` | Laravel seeder — adds 6 sample network hospitals |

---

## Verifying the patch worked

After running the patch (Path A), you can verify:

```sql
-- 21 new columns should be present:
SHOW COLUMNS FROM hospitals WHERE Field IN (
  'type', 'tagline', 'description', 'address_line1', 'address_line2',
  'city', 'state', 'country', 'postal_code', 'phone_main', 'phone_alt',
  'email', 'website', 'logo_path', 'cover_path', 'latitude', 'longitude',
  'services', 'operating_hours', 'is_public', 'accepts_online_registration'
);

-- 4 new tables should exist:
SHOW TABLES LIKE 'patient_portal_%';
SHOW TABLES LIKE 'provider_applications';

-- 2 new roles should be present:
SELECT * FROM roles WHERE name IN ('private_doctor', 'hospital_administrator');
```

After clearing caches, visit:
- `/` — public landing page
- `/hospitals` — hospital directory
- `/register` — new patient registration form
- `/providers` — provider registration hub
- `/providers/doctors/register` — doctor registration
- `/providers/hospitals/register` — hospital registration
- `/portal/login` — patient portal login (use a portal user, not a staff user)
- `/admin` — Filament staff panel (super_admin login unchanged)
