# CHANGELOG — FUTH Lafia EMR Public Portal & Provider Registration

This document lists every change made in this build, grouped by feature. The new
work adds a public-facing patient portal, provider (doctor / hospital) registration
with admin approval, an extended hospital directory, and several bug fixes.

---

## 1. New public landing page

Replaces the default Laravel `welcome.blade.php` at `/` with a proper institutional
landing page: hero, services grid, hospital directory preview, provider CTA band,
and a full footer.

- **Route:** `GET /` → `public.landing`
- **Files:** `app/Http/Controllers/PublicPortal/LandingController.php`,
  `resources/views/public/landing.blade.php`, `resources/views/public/layout.blade.php`

## 2. Hospital directory & profiles

A public, searchable directory of all network hospitals, plus individual hospital
profile pages with contacts, services, clinics, operating hours and quick actions.

- **Routes:** `GET /hospitals` → `public.directory`, `GET /hospitals/{hospital}` → `public.hospital`, `GET /about` → `public.about`, `GET /ajax/clinics` → `public.ajax.clinics`
- **Files:** `resources/views/public/directory.blade.php`,
  `resources/views/public/hospital.blade.php`, `resources/views/public/about.blade.php`

## 3. Patient self-registration (any network hospital)

A multi-section public registration form that creates a `Patient` record at the
chosen hospital AND a `PatientPortalUser` login (status `pending` until admin
approval). A hospital number is auto-generated on save.

- **Routes:** `GET /register`, `GET /register/{hospital}`, `POST /register`, `GET /register/success`
- **Files:** `app/Http/Controllers/PublicPortal/PatientRegistrationController.php`,
  `resources/views/public/patient-register.blade.php`,
  `resources/views/public/patient-register-success.blade.php`,
  `resources/views/public/patient-register-unavailable.blade.php`

## 4. Patient portal (Full PHR)

Authenticated self-service portal for verified patients: dashboard, encounter
history, lab results, prescriptions, invoices, receipts, appointment booking,
cancellation, and two-way secure messaging with hospital staff.

- **Routes:** `portal.login`, `portal.logout`, `portal.dashboard`,
  `portal.encounters`, `portal.lab-results`, `portal.prescriptions`,
  `portal.invoices`, `portal.receipts`, `portal.appointments`,
  `portal.appointments.create`, `portal.appointments.store`,
  `portal.appointments.cancel`, `portal.appointments.slots`,
  `portal.messages`, `portal.messages.create`, `portal.messages.store`,
  `portal.messages.show`
- **Files:**
  - `app/Http/Controllers/PatientPortal/AuthController.php`
  - `app/Http/Controllers/PatientPortal/DashboardController.php`
  - `app/Http/Controllers/PatientPortal/AppointmentController.php`
  - `app/Http/Controllers/PatientPortal/MessageController.php`
  - `resources/views/portal/layout.blade.php`
  - `resources/views/portal/auth/login.blade.php`
  - `resources/views/portal/dashboard.blade.php`
  - `resources/views/portal/encounters.blade.php`
  - `resources/views/portal/lab-results.blade.php`
  - `resources/views/portal/prescriptions.blade.php`
  - `resources/views/portal/invoices.blade.php`
  - `resources/views/portal/receipts.blade.php`
  - `resources/views/portal/appointments.blade.php`
  - `resources/views/portal/appointment-create.blade.php`
  - `resources/views/portal/messages.blade.php`
  - `resources/views/portal/message-create.blade.php`
  - `resources/views/portal/message-show.blade.php`

## 5. Provider registration (private doctor + hospital/clinic)

Two separate registration flows, each landing in an admin approval queue:

- **Doctor:** captures title, name, contact, professional registration number,
  specialty, qualification, years of experience, bio, services offered.
- **Hospital/Clinic:** captures facility name, type, license number, address,
  administrator contact, services offered.

On **admin approval**:
- Doctor → creates a `User` with role `private_doctor` linked to the default hospital.
- Hospital/Clinic → creates a new `Hospital` record, a default `Department`, and an
  admin `User` with role `hospital_administrator` linked to that hospital.

- **Routes:** `provider.index`, `provider.doctor.register`,
  `provider.doctor.register.store`, `provider.hospital.register`,
  `provider.hospital.register.store`, `provider.success`
- **Files:**
  - `app/Http/Controllers/Provider/RegistrationController.php`
  - `app/Services/ProviderApprovalService.php`
  - `resources/views/provider/index.blade.php`
  - `resources/views/provider/doctor-register.blade.php`
  - `resources/views/provider/hospital-register.blade.php`
  - `resources/views/provider/success.blade.php`

## 6. Admin (Filament) resources for the new workflow

Three new admin resources, auto-discovered by Filament, with approve / reject /
suspend / reactivate actions and navigation badges showing pending counts:

- **Provider Applications** (`Administration` group) — list + view with Approve and
  Reject actions (rejection requires a reason).
- **Patient Portal Users** (`Administration` group) — list + view with Approve,
  Suspend, Reactivate actions. Approving also verifies the linked patient record.
- **Patient Messages** (`Front Desk` group) — list + view with a Reply action that
  sends a reply back into the patient's portal inbox.

- **Files:**
  - `app/Filament/Resources/ProviderApplications/**`
  - `app/Filament/Resources/PatientPortalUsers/**`
  - `app/Filament/Resources/PatientPortalMessages/**`

The existing **Hospital** resource was extended: the form schema (previously an
empty TODO) now covers identity, contact, public-directory fields (logo, cover,
coordinates, services tags, operating hours, public / online-registration toggles),
and the table now shows type, city, state, phone, public/online-reg flags with
filters.

## 7. Database migrations

- **`2026_11_01_000001_create_public_portal_tables.php`** — extends the `hospitals`
  table with 20 public-directory columns, and creates:
  - `patient_portal_users`
  - `provider_applications`
  - `patient_portal_messages`
  - `patient_portal_password_reset_tokens`

## 8. Models updated / added

- **`Hospital`** — extended fillable + casts + `scopePublic()` + directory accessors;
  added `patientPortalMessages` and `providerApplications` relations.
- **`Patient`** — added `portalUser()`, `messages()`, `receipts()` relations.
- **`PatientPortalUser`** (new) — authenticatable, separate `patient_portal` guard.
- **`ProviderApplication`** (new) — auto-generates `application_number`.
- **`PatientPortalMessage`** (new).

## 9. Authentication

- **`config/auth.php`** — added a `patient_portal` session guard backed by a new
  `patient_portal_users` Eloquent provider and its own password-reset broker, so
  patient logins are fully isolated from staff logins.
- **`bootstrap/app.php`** — fixed guest redirect to be guard-aware (patient-portal
  guests go to `/portal/login`, everyone else to `/admin/login`).

## 10. Seeders

- **`PublicHospitalDirectorySeeder`** — 6 sample network hospitals (FUTH Lafia +
  branches/clinics across Nasarawa + Abuja telemedicine hub) with addresses,
  phones, emails, services and operating hours. Sample data — edit before live.
- **`PublicPortalRolesSeeder`** — ensures the new `private_doctor` and
  `hospital_administrator` roles exist (safe to re-run).
- **`DatabaseSeeder`** — calls both new seeders as a new step 8.

## 11. Styling

- **`public/css/futh-public-portal.css`** — a self-contained FUTH-branded theme:
  Nigerian green + white + gold, Playfair Display serif headings, Inter body,
  responsive navbar, hero, card grid, auth forms, portal sidebar layout, stats,
  panels, tables, badges, stepper. Loaded by the public and portal layouts only
  (does not affect the Filament admin theme).

## 12. Bug fixes (broad sweep)

1. **`PatientForm.php`** — removed `title`, `upi` and renamed `nin` → `national_id`
   with a matching `national_id_type` select. The previous `nin` field was being
   saved to a column that does not exist on the `patients` table, which would throw
   "Column not found" on every new patient create. Gender options now match the
   public registration form (`Male` / `Female` / `Other`).
2. **`HospitalForm.php`** — replaced the empty TODO form schema with a complete
   schema covering all extended hospital fields.
3. **`bootstrap/app.php`** — `redirectGuestsTo` was hardcoded to `/admin/login`,
   sending patient-portal guests to the staff login. Now guard-aware.
4. **`routes/web.php`** — `/register/{hospital}` was registered before
   `/register/success`, so the success page was shadowed by route-model-binding and
   returned 404. Literal routes now come first.
5. **`Patient` model** — added `messages()`, `receipts()`, `portalUser()` relations
   referenced by the new portal controllers (previously undefined, would throw
   `BadMethodCallException`).

---

## Setup after copying the files

```bash
# 1. Run the new migration (extends hospitals + creates 4 new tables)
php artisan migrate

# 2. Seed the new directory data + roles (non-destructive, uses updateOrCreate)
php artisan db:seed --class=PublicPortalRolesSeeder
php artisan db:seed --class=PublicHospitalDirectorySeeder

# 3. (Optional) Re-run the full seeder — already wired into DatabaseSeeder step 8
php artisan db:seed

# 4. Clear cached config + routes so the new guard + routes are picked up
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Default logins (unchanged)
- **Staff admin:** `admin@futhlafia.gov.ng` / `Futh@2026`
- **Patient portal:** patients self-register at `/register`; accounts become
  active after an admin approves them in *Patient Portal Users*.

### New public URLs
- `/` — public landing
- `/hospitals` — hospital directory
- `/hospitals/{id}` — hospital profile
- `/register` — new patient registration
- `/providers` — provider registration home
- `/providers/doctors/register` — doctor registration
- `/providers/hospitals/register` — hospital / clinic registration
- `/portal/login` — patient portal sign in
- `/portal/dashboard` — patient portal home (after sign in)
