# CHANGELOG — FUTH Lafia EMR v2.3 Service Entity Update

This update brings the **NHIA Procedure Price List 2025 (April 2025)** into
the service catalogue as the tariff for **both** insurance schemes — **NHIS**
(federal NHIA) and **NASHIA** (Nasarawa State Health Insurance Agency) — and
adds the special non-clinical services with a complete billing workflow:

> authorised user adds a service → invoice created → **invoice appears at the
> cashier point** for payment collection.

---

## 1. Service entity (database) updates

**Migration:** `database/migrations/2026_11_03_000001_update_service_entity_for_nhia_2025.php`
(the standalone SQL equivalent is `database/sql/v2_3_services_entity_update.sql`)

### `services` table

| New column | Purpose |
|---|---|
| `nashia_rate` DECIMAL(14,2) | NASHIA (Nasarawa State) tariff — parallel to `nhia_rate`, so each scheme can be priced/edited separately |
| `allowed_roles` JSON | Roles permitted to **add (bill)** the service from the *Bill a Service* desk. NULL/empty = all billing roles (default for clinical services) |
| `allows_walk_in` BOOL | Service may be billed to a **walk-in (non-patient) customer** — gas plant oxygen, internship fees, research fees, application forms, ID cards |
| INDEX `nhia_code` | Fast tariff lookup by NHIA code |

### `invoices` table

| Change | Purpose |
|---|---|
| `patient_id` now nullable | Walk-in customers (gas plant buyers, interns, students, card applicants) are not registered patients |
| `customer_name`, `customer_phone` | Walk-in customer details shown on the invoice |
| `payment_mode` gains `NASHIA` | State-scheme invoices are distinguishable from federal NHIA ones |

## 2. NHIA Procedure Price List 2025 — 1,158 services, 17 sections

Imported from the official April 2025 PDF (nhia.gov.ng) into `services` with
`nhia_code` set and **nhia_rate = nashia_rate = published price** (the list
applies to both schemes). All prices remain editable per service.

| Section | Services | Section | Services |
|---|---|---|---|
| General Description | 19 | Paediatrics | 52 |
| ENT | 58 | Dental Surgery & Maxillofacial | 41 |
| Obstetrics & Gynaecology | 60 | Physiotherapy | 29 |
| Internal Medicine | 21 | Hysteroscopic Procedures | 4 |
| Orthopaedics | 62 | Neurosurgery | 42 |
| General Surgery | 319 | Radiology Investigations | 96 |
| Endoscopic Procedures | 32 | Laboratory Investigations | 194 |
| Optometry | 25 | Psychiatric / Mental Health | 11 |
| Ophthalmology | 93 | | |

Notes on data fidelity:
- Rows printed **[restricted]** are flagged `requires_pre_authorization = 1`.
- The printed PDF reuses ENT codes 022/023/024 inside the Dental section;
  those dental rows keep the printed code in `nhia_code` but get a unique
  `code` suffix `-DENT` (e.g. `NHIA-022-001-DENT`).
- The few un-coded lab panel rows (e.g. *Full Electrolytes*) received
  synthesized codes `NHIA-180-P1`, `NHIA-180-P2` (noted in their descriptions).
- Four legacy demo services (Normal Delivery, Caesarean Section, Specialist
  Consultation, Bed Occupancy) were re-priced to the April 2025 values.

**Seeders:** `NhiaPriceList2025Seeder` (+ data file
`database/seeders/data/nhia_price_list_2025.json`) — idempotent, upserts by
service `code`.

## 3. Special (non-clinical) services

**Seeder:** `SpecialServicesSeeder` — idempotent.

| Category | Services | Walk-in | Billing roles |
|---|---|---|---|
| Internship & Training | 3 / 6 / 9 months placement fees | ✔ | medical_administrator, accountant, biller (+ super_admin) |
| Research & Data Collection | Diploma / BSc / MSc / PhD student fees | ✔ | medical_administrator, accountant, biller (+ super_admin) |
| Gas Plant | Medical oxygen (per cylinder) | ✔ | accountant, biller, cashier (+ super_admin) |
| Application Forms | Application form | ✔ | medical_records_officer, accountant, biller (+ super_admin) |
| ID Cards | Staff / NHIS enrollee / NASHIA enrollee | ✔ | medical_records_officer, insurance_officer, accountant, biller (+ super_admin) |

> **PLACEHOLDER PRICES** — where no official tariff exists, clearly-flagged
> defaults were seeded (form ₦1,000; ID cards ₦2,000; research ₦3,500 —
> grounded on the hospital's legacy tariff; internship and gas plant are
> proportional estimates). **Review every price via Billing & Finance →
> Services → filter "Walk-in Billable" before going live.** Roles per service
> are editable on the same form.

## 4. The editable Service model (edit prices any time)

- `app/Models/Service.php`:
  - `priceFor('nhia' | 'nashia' | 'private' | 'standard')` resolves the unit
    price with fallbacks (nashia → nhia → standard).
  - `schemeForPaymentMode()` maps invoice payment modes to schemes.
  - `scopeBillableBy($roles)` / `isBillableBy($roles)` enforce per-service
    billing permissions.
- **Billing & Finance → Services** (Filament) was rebuilt:
  - Form sections: Service Details / Tariff-Pricing / **Billing Access**
    (allowed roles + walk-in toggle) / Tax & Pre-Auth / Description.
  - Table: NHIA + NASHIA + private rate columns, category-group, status and
    walk-in filters, billing-roles badge.
  - View (infolist) page shows every tariff and access field.
- Billing & Finance → Service Categories remains the place to add/rename
  groups (ENT, Gas Plant, Internship …).

## 5. Bill a Service → invoice at the cashier point

- **New page:** *Front Desk → Bill a Service* (`/admin/service-billing`,
  `app/Filament/Pages/ServiceBilling.php`).
  - Bill a **registered patient** (search by hospital no / name / phone) or a
    **walk-in customer**.
  - Service search is filtered by the user's roles (`allowed_roles`) and, for
    walk-ins, by `allows_walk_in`.
  - Payment mode defaults from the patient's scheme (NHIA → NHIA, NASHIA →
    NASHIA, private → Cash) and the unit price auto-fills from the matching
    tariff; both remain overridable.
    Optional "insurance covered" amount for scheme patients.
  - On submit, `ServiceBillingService` creates the **Invoice + InvoiceItem**.
- **Cashier point (unchanged mechanism):** the existing
  `CashierTaskService` listener (registered in `AppServiceProvider`) auto-drops
  a **CashierTask** for every invoice with a positive balance, so the new bill
  instantly appears in *Front Desk → Cashier / Payments → Cashier Tasks* with
  the amount due and the walk-in customer name.
- The cashier table's first column now shows **Billed To** — patient name +
  hospital number, or the walk-in customer name/phone.

## 6. Updated files

| File | Change |
|---|---|
| `database/migrations/2026_11_03_000001_update_service_entity_for_nhia_2025.php` | New — schema changes above |
| `database/sql/v2_3_services_entity_update.sql` | New — idempotent phpMyAdmin import (schema + 1,158 NHIA services + 12 special services + legacy alignment) |
| `database/seeders/data/nhia_price_list_2025.json` | New — parsed NHIA 2025 price list |
| `database/seeders/NhiaPriceList2025Seeder.php` | New |
| `database/seeders/SpecialServicesSeeder.php` | New |
| `database/seeders/DatabaseSeeder.php` | Steps 9 & 10 added |
| `app/Models/Service.php` | nashia_rate, allowed_roles, allows_walk_in, priceFor(), billing scopes |
| `app/Models/Invoice.php` | walk-in customer fields + `billed_to_label` |
| `app/Services/ServiceBillingService.php` | New — creates invoice + item, enforces billing permissions |
| `app/Filament/Pages/ServiceBilling.php` + `resources/views/filament/pages/service-billing.blade.php` | New — Bill a Service desk |
| `app/Filament/Resources/Services/Schemas/ServiceForm.php` | Rebuilt — full editing incl. tariffs & billing access |
| `app/Filament/Resources/Services/Schemas/ServiceInfolist.php` | Rebuilt (was an empty stub) |
| `app/Filament/Resources/Services/Tables/ServicesTable.php` | Rates columns + filters |
| `app/Filament/Resources/CashierTasks/Tables/CashierTasksTable.php` | "Billed To" column for walk-ins |
| `app/Filament/Resources/Invoices/Schemas/InvoiceForm.php` | NASHIA mode + walk-in customer fields |

## 7. How to apply

**Option 1 — Artisan (recommended):**
```bash
php artisan migrate
php artisan db:seed --class=NhiaPriceList2025Seeder
php artisan db:seed --class=SpecialServicesSeeder
php artisan config:clear && php artisan route:clear && php artisan cache:clear
```

**Option 2 — phpMyAdmin only:** import
`database/sql/v2_3_services_entity_update.sql` (idempotent; safe to re-run).
It applies the same schema changes, the full NHIA 2025 list, the special
services and the legacy price alignment in one go.

**Verify:**
```sql
SELECT COUNT(*) FROM services WHERE nhia_code IS NOT NULL;        -- 1158
SELECT COUNT(*) FROM services WHERE allows_walk_in = 1;           -- 12
SELECT code, standard_price, nhia_rate, nashia_rate FROM services WHERE code IN
  ('NHIA-033-004','NHIA-030-002','SPEC-GAS-001','SPEC-INT-006'); -- sample tariffs
SHOW COLUMNS FROM services WHERE Field IN ('nashia_rate','allowed_roles','allows_walk_in');
```

Then: review placeholder prices (Services → filter *Walk-in Billable*), and
make sure the staff who operate each desk (gas plant, internship, research,
records, insurance) have the matching roles under Administration → Users.
