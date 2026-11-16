# CHANGELOG — FUTH Lafia EMR v2.2 Enhancement Pack

This document lists every change made in the v2.2 enhancement pack. The new
work adds: password-reset flows for staff and patient users, email
notifications on provider application approval/rejection, staff-to-staff
internal messaging, public patient feedback form, and automatic cashier
queue drops when any billable service is created.

---

## 1. Password reset — staff users (`web` guard)

- **Routes:** `password.request`, `password.email`, `password.reset`,
  `password.update` (under `/password/...`).
- **Files:**
  - `app/Http/Controllers/Auth/PasswordResetController.php`
  - `resources/views/auth/passwords/request.blade.php`
  - `resources/views/auth/passwords/reset.blade.php`
  - `app/Mail/PasswordResetLink.php`
  - `resources/views/emails/password-reset-link.blade.php`
- **Existing infrastructure:** Laravel's `password_reset_tokens` table
  (already in the live DB), `users` password broker.
- The Filament custom-login page's "Forgot Password" link is now wired to
  `route('password.request')` instead of `#`.

## 2. Password reset — patient portal users (`patient_portal` guard)

- **Routes:** `portal.password.request`, `portal.password.email`,
  `portal.password.reset`, `portal.password.update` (under `/portal/password/...`).
- **Existing infrastructure:** `patient_portal_password_reset_tokens` table
  (created by the public-portal migration) + `patient_portal_users` password
  broker (configured in `config/auth.php`).
- The patient portal login page (`/portal/login`) now has a "Forgot
  password?" link.

## 3. Email notifications on provider application approval/rejection

- **Files:**
  - `app/Mail/ProviderApplicationApproved.php` + view
  - `app/Mail/ProviderApplicationRejected.php` + view
  - `app/Services/ProviderApprovalService.php` (updated) — dispatches the
    email after the database transaction commits. Failures are logged
    but never crash the approval action.
- **Triggers:** When an admin uses the Approve/Reject action on a provider
  application in the Filament admin panel, the applicant receives a
  branded HTML email at the address they registered with.
- **Email config:** Uses the existing `.env` mail settings
  (`MAIL_MAILER=smtp`, `MAIL_HOST=mailpit`). For local dev with Mailpit,
  the emails land in the Mailpit inbox at `http://localhost:8025`.

## 4. Staff-to-staff messaging

- **New table:** `staff_messages` (sender_id, recipient_id, parent_id for
  threading, subject, body, priority, status, read_at, archived_at,
  attachments JSON, soft deletes).
- **New Filament resource:** `StaffMessageResource` (under
  *Administration* nav group). Includes inbox table, view page, reply
  action, compose action, and a navigation badge showing unread count.
- **Files:**
  - `app/Models/StaffMessage.php`
  - `app/Filament/Resources/StaffMessages/**`
  - `app/Mail/StaffMessageReceived.php` + view — emails the recipient when
    a new staff message arrives (best-effort, never crashes the send
    action).

## 5. Patient feedback (public form + admin review)

- **Public form:** `/feedback` (no login required) — captures the
  patient's hospital, optional name/phone, 8 ratings (reception, nursing,
  doctor care, cleanliness, waiting time, pharmacy, lab service, overall),
  best/worst experience text, suggestions, complaints, and
  would-recommend checkbox. Submissions get a reference number.
- **Existing infrastructure:** Reuses the live DB's
  `patient_satisfaction_surveys` table (already has its own Filament
  resource `PatientSatisfactionSurveyResource` for admin review).
- **Files:**
  - `app/Http/Controllers/PublicPortal/FeedbackController.php`
  - `resources/views/public/feedback.blade.php`
  - `resources/views/public/feedback-success.blade.php`
- The "Feedback" link has been added to the public nav bar + footer.

## 6. Auto-drop billable services to cashier

- **New table:** `cashier_tasks` (task_number, hospital_id, patient_id,
  encounter_id, appointment_id, source_type, source_id, description,
  amount_due, amount_paid, status, assigned_cashier_id, paid_at,
  payment_method, receipt_number, invoice_id, patient_receipt_id, notes).
- **Service:** `App\Services\CashierTaskService` registers Eloquent
  `::created` listeners on **seven** billable models:
  1. `Invoice` — balance due
  2. `LabOrder` — sum of item prices
  3. `RadiologyOrder` — modality price
  4. `Prescription` — sum of drug selling prices × quantities
  5. `Appointment` — card fee (when `card_fee_amount > 0` and `card_fee_paid = false`)
  6. `Encounter` — consultation fee (looked up from linked appointment's `appointment_type.default_fee`, or a `Service` with `category = 'Consultation'` matching the visit_type, or a configurable default in `config/futh.php`)
  7. `Vitals` — nursing service fee (looked up from an active `Service` whose name contains 'nursing', or a configurable default in `config/futh.php`)

  Whenever a new billable record is created with a positive amount due,
  a `CashierTask` is automatically created in `pending` status.
- **Cashier UI:** New `CashierTaskResource` Filament resource (under
  *Accounts* nav group) with a "Cashier Queue" navigation badge showing
  pending count. The cashier can collect payment, record payment method,
  receipt number, and notes. Paid tasks automatically update the linked
  invoice's `amount_paid` and `balance_due` columns, and mark appointment
  card fees as paid on the original `appointments` record.
- **Config:** `config/futh.php` provides configurable fallback fees
  (`default_consultation_fee`, `default_nursing_fee`) for when no matching
  service or appointment type is found. Both default to 0 (suppress
  auto-drop) — set them in `.env` to enable.

---

## Files added/changed

### New files
- `app/Http/Controllers/Auth/PasswordResetController.php`
- `app/Http/Controllers/PublicPortal/FeedbackController.php`
- `app/Models/StaffMessage.php`
- `app/Models/CashierTask.php`
- `app/Mail/ProviderApplicationApproved.php`
- `app/Mail/ProviderApplicationRejected.php`
- `app/Mail/PasswordResetLink.php`
- `app/Mail/StaffMessageReceived.php`
- `app/Services/CashierTaskService.php`
- `app/Filament/Resources/StaffMessages/**` (resource, table, schema, 2 pages)
- `app/Filament/Resources/CashierTasks/**` (resource, table, schema, 2 pages)
- `app/Http/Controllers/PublicPortal/FeedbackController.php`
- `database/migrations/2026_11_01_000003_create_staff_messages_and_cashier_tasks_tables.php`
- `resources/views/auth/passwords/request.blade.php`
- `resources/views/auth/passwords/reset.blade.php`
- `resources/views/emails/provider-application-approved.blade.php`
- `resources/views/emails/provider-application-rejected.blade.php`
- `resources/views/emails/password-reset-link.blade.php`
- `resources/views/emails/staff-message-received.blade.php`
- `resources/views/public/feedback.blade.php`
- `resources/views/public/feedback-success.blade.php`

### Modified files
- `app/Services/ProviderApprovalService.php` — added email dispatch
- `app/Providers/AppServiceProvider.php` — registers CashierTaskService
- `routes/web.php` — added staff password reset, portal password reset,
  public feedback routes
- `resources/views/filament/pages/auth/custom-login.blade.php` — wired the
  Forgot Password link
- `resources/views/portal/auth/login.blade.php` — added Forgot password
  link
- `resources/views/public/layout.blade.php` — added Feedback nav + footer
- `database/sql/futh_lafia_emr_public_portal_patch.sql` — added the two
  new tables to the live-DB patch (idempotent `CREATE TABLE IF NOT
  EXISTS`)

---

## Setup after copying the files

### If you already have the live DB and applied the v2.1 patch:

The live-DB patch file (`database/sql/futh_lafia_emr_public_portal_patch.sql`)
has been updated to also create the two new v2.2 tables. Re-run it:

```bash
mysql -u root -p futh_lafia_emr < database/sql/futh_lafia_emr_public_portal_patch.sql
```

Or if you prefer Laravel:

```bash
php artisan migrate
php artisan config:clear && php artisan route:clear && php artisan cache:clear
```

### If you're setting up fresh:

```bash
php artisan migrate --seed
```

The new migration `2026_11_01_000003_create_staff_messages_and_cashier_tasks_tables.php`
is auto-discovered by Laravel.

### Mail configuration

The email features require working mail configuration. The default `.env`
ships with Mailpit (a popular local SMTP testing tool):

```
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_FROM_ADDRESS="noreply@futhlafia.gov.ng"
```

For production, change to your real SMTP server. The approval/rejection
emails will fail silently and log a warning if mail is not configured —
the database action itself always succeeds.

### New URLs
- `/password/forgot` — staff password reset request
- `/password/reset/{token}` — staff password reset form
- `/portal/password/forgot` — patient password reset request
- `/portal/password/reset/{token}` — patient password reset form
- `/feedback` — public patient feedback form
- `/admin/staff-messages` — staff messaging (Filament)
- `/admin/cashier-tasks` — auto-dropped cashier queue (Filament)

### Default logins (unchanged)
- **Staff:** `admin@futhlafia.gov.ng` / `Futh@2026`
- **Patient portal:** patients self-register at `/register`; accounts
  become active after admin approval in *Patient Portal Users*.
