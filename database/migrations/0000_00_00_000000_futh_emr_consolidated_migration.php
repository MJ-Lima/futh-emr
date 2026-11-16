<?php

/**
 * FUTH Lafia EMR — Consolidated Migration
 * 
 * This single migration creates ALL tables in the correct dependency order
 * with explicit table names on every foreign key constraint.
 * 
 * It works on both SQLite and MySQL.
 * 
 * Usage:
 *   1. Delete your existing database file (database/futh_emr.sqlite)
 *      OR drop all tables in MySQL
 *   2. Delete all rows from the migrations table (or drop it)
 *   3. Run: php artisan migrate --force
 *   4. Run: php artisan db:seed --force
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---- DISABLE FK CHECKS (works on both SQLite and MySQL) ----
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        // ---- DROP ALL EXISTING TABLES IN REVERSE ORDER ----
        $tablesToDrop = [
            'general_store_requisition_items', 'general_store_requisitions',
            'general_store_movements', 'general_store_stocks',
            'general_store_items', 'general_store_categories', 'general_store_locations',
            'report_subscriptions', 'report_schedules', 'saved_reports', 'report_templates',
            'patient_relationships', 'relationship_types', 'patient_dependants',
            'audit_follow_ups', 'internal_audit_findings', 'internal_audits',
            'medical_audit_findings', 'medical_audits', 'audit_categories',
            'procedure_sessions', 'procedure_bookings', 'procedures', 'procedure_categories',
            'service_categories',
            'blood_transfusions', 'blood_crossmatches', 'blood_requests',
            'blood_units', 'blood_donations', 'blood_donors',
            'stock_take_items', 'stock_takes',
            'store_requisition_items', 'store_requisitions',
            'store_movements', 'store_stocks', 'store_items', 'store_locations', 'store_categories',
            'goods_received_note_items', 'goods_received_notes', 'drug_stock_levels',
            'clinic_schedules', 'provider_schedules', 'appointment_slots',
            'appointment_types', 'holidays', 'leave_requests',
            'discharge_summaries', 'bed_transfers', 'admissions', 'beds', 'wards',
            'petty_cash_transactions', 'expense_claims', 'budgets',
            'journal_lines', 'journal_entries', 'chart_of_accounts',
            'invoice_items', 'invoices', 'payments', 'services',
            'pre_authorizations', 'insurance_claim_lines', 'insurance_claims',
            'patient_insurance', 'insurance_plans', 'insurance_providers',
            'duty_postings', 'duty_rosters',
            'prescriptions', 'prescription_items', 'dispensing_points', 'dispensing_logs',
            'stock_movements', 'stock_returns', 'drug_batches', 'drug_interactions',
            'drugs', 'drug_categories',
            'lab_results', 'lab_order_items', 'lab_orders', 'lab_tests', 'lab_panels',
            'radiology_results', 'radiology_orders', 'radiology_modalities',
            'vitals', 'clinical_notes', 'allergies', 'diagnoses',
            'purchases', 'purchase_items', 'receipts', 'receipt_items', 'purchase_items',
            'receipt_items',
            'dispensing_logs',
            'stock_returns',
            'lab_results',
            'radiology_results',
            'suppliers',
            'appointments', 'encounters', 'staff', 'patients',
            'departments', 'clinics', 'hospitals',
            'model_has_permissions', 'model_has_roles',
            'role_has_permissions', 'permissions', 'roles',
            'users', 'password_reset_tokens', 'sessions',
            'cache', 'cache_locks', 'jobs', 'job_batches', 'failed_jobs',
            'states', 'locals',
    
        ];

        foreach ($tablesToDrop as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }

        // ================================================================
        // CREATE ALL TABLES IN CORRECT DEPENDENCY ORDER
        // Every foreignId uses ->constrained('explicit_table_name')
        // ================================================================

        // ---- Core Laravel Tables ----
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->default(1)->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->default(1)->constrained('departments')->cascadeOnDelete();
            $table->foreignId('default_clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->string('avatar_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'locked'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken(100);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity');
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('updated_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // ---- Spatie Permission Tables ----
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });

        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->foreign('permission_id', 'mhp_permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->primary(['permission_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });

        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->foreign('role_id', 'mhr_role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->primary(['role_id', 'model_id', 'model_type']);
            $table->index(['model_id', 'model_type']);
        });

        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('permission_id', 'rhp_permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->foreign('role_id', 'rhp_role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        // ---- Geography ----
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->timestamps();
        });

        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->constrained('states')->cascadeOnDelete();
            $table->string('local_name');
            $table->timestamps();
        });

        // ---- Hospital Structure ----
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Patients ----
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_no', 200)->unique();
            $table->enum('patient_type', ['private', 'nhia', 'nashia', 'retainership', 'corporate', 'other_insurance'])->default('private');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->string('firstname', 200);
            $table->string('middlename', 200)->nullable();
            $table->string('lastname', 200);
            $table->date('dob');
            $table->string('gender', 7);
            $table->string('bloodgroup', 7)->nullable();
            $table->string('genotype', 2)->nullable();
            $table->string('marital', 20)->nullable();
            $table->string('religion', 200)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('phonemain', 13);
            $table->string('phonealt', 13)->nullable();
            $table->string('email', 250)->nullable();
            $table->text('address');
            $table->string('city', 250);
            $table->string('state', 250);
            $table->string('country', 250);
            $table->string('postalcode', 12)->nullable();
            $table->string('nokname', 250);
            $table->string('nok_firstname', 250)->nullable();
            $table->string('nok_middlename', 250)->nullable();
            $table->string('relationship', 250);
            $table->string('nokphone', 250);
            $table->text('nokaddress');
            $table->string('hereabout', 250)->nullable();
            $table->string('notes', 250)->nullable();
            $table->boolean('status')->default(false);
            $table->string('photo_path')->nullable();
            $table->string('thumbprint_path')->nullable();
            $table->longText('thumbprint_data')->nullable();
            $table->dateTime('biometric_captured_at')->nullable();
            $table->foreignId('biometric_captured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('verification_status', ['Pending', 'Verified', 'Rejected', 'Expired'])->default('Pending');
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('national_id', 50)->nullable();
            $table->enum('national_id_type', ['NIN', 'Voter Card', 'Driver License', 'International Passport', 'Birth Certificate', 'Other'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Staff ----
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->string('staff_number', 255)->unique();
            $table->string('title', 255)->nullable();
            $table->string('first_name', 255);
            $table->string('middle_name', 255)->nullable();
            $table->string('last_name', 255);
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('designation', 255)->nullable();
            $table->enum('staff_type', ['Doctor', 'Nurse', 'Pharmacist', 'Laboratory Scientist', 'Radiographer', 'Physiotherapist', 'Administrator', 'Accountant', 'ICT', 'Medical Records', 'Other']);
            $table->string('passport', 255)->nullable();
            $table->string('signature', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Encounters ----
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('encounter_number', 255)->unique();
            $table->enum('visit_type', ['Outpatient', 'Emergency', 'Inpatient', 'Telemedicine', 'Follow-up']);
            $table->date('encounter_date');
            $table->time('encounter_time');
            $table->enum('status', ['Waiting', 'In Progress', 'Completed', 'Cancelled'])->default('Waiting');
            $table->text('chief_complaint')->nullable();
            $table->string('referred_by', 255)->nullable();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Appointments ----
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('appointment_number', 255)->unique();
            $table->string('queue_number')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->integer('estimated_duration')->default(30);
            $table->enum('visit_type', ['New', 'Follow-up', 'Review', 'Telemedicine']);
            $table->enum('priority', ['Routine', 'Urgent', 'Emergency'])->default('Routine');
            $table->text('reason_for_visit');
            $table->enum('status', ['Scheduled', 'Confirmed', 'Checked In', 'Completed', 'Cancelled', 'No Show'])->default('Scheduled');
            $table->dateTime('checked_in_at')->nullable();
            $table->dateTime('consultation_started_at')->nullable();
            $table->dateTime('consultation_ended_at')->nullable();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Clinical: Vitals, Notes, Allergies, Diagnoses ----
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->dateTime('recorded_at');
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('bmi', 5, 2)->nullable();
            $table->decimal('muac_cm', 5, 2)->nullable();
            $table->smallInteger('systolic_bp')->nullable();
            $table->smallInteger('diastolic_bp')->nullable();
            $table->smallInteger('heart_rate')->nullable();
            $table->enum('bp_position', ['Sitting', 'Standing', 'Supine', 'Unknown'])->default('Unknown');
            $table->smallInteger('respiratory_rate')->nullable();
            $table->smallInteger('spo2')->nullable();
            $table->decimal('temperature_c', 4, 2)->nullable();
            $table->enum('temperature_route', ['Oral', 'Axillary', 'Tympanic', 'Rectal', 'Forehead'])->default('Oral');
            $table->smallInteger('pain_score')->nullable();
            $table->string('glasgow_coma_score', 8)->nullable();
            $table->decimal('blood_glucose_mmol_l', 5, 2)->nullable();
            $table->enum('glucose_fasting', ['Fasting', 'Random', 'Postprandial', 'Unknown'])->default('Unknown');
            $table->smallInteger('fetal_heart_rate')->nullable();
            $table->decimal('fundal_height_cm', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('note_number', 255)->unique();
            $table->enum('note_type', ['Progress', 'Admission', 'Discharge', 'Nursing', 'Procedure', 'Consultation', 'Referral', 'Death', 'Other'])->default('Progress');
            $table->string('title', 255)->nullable();
            $table->longText('subjective')->nullable();
            $table->longText('objective')->nullable();
            $table->longText('assessment')->nullable();
            $table->longText('plan')->nullable();
            $table->longText('narrative')->nullable();
            $table->longText('tags')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->string('substance', 255);
            $table->string('code', 255)->nullable();
            $table->enum('category', ['Medication', 'Food', 'Environment', 'Biologic', 'Other'])->default('Medication');
            $table->enum('criticality', ['Low', 'High', 'Unable to assess'])->default('Low');
            $table->enum('severity', ['Mild', 'Moderate', 'Severe', 'Fatal'])->default('Mild');
            $table->enum('reaction', ['Rash', 'Urticaria', 'Anaphylaxis', 'Angioedema', 'Bronchospasm', 'GI upset', 'Hepatotoxicity', 'Stevens-Johnson', 'Other'])->default('Rash');
            $table->text('reaction_description')->nullable();
            $table->date('onset_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Resolved', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->string('icd10_code', 10);
            $table->string('description', 255);
            $table->enum('type', ['Principal', 'Admitting', 'Working', 'Discharge', 'Secondary', 'Comorbidity'])->default('Working');
            $table->enum('status', ['Active', 'Resolved', 'Chronic', 'Recurrent'])->default('Active');
            $table->date('onset_date')->nullable();
            $table->date('resolved_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Wards, Beds, Admissions ----
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['General', 'Male Medical', 'Female Medical', 'Male Surgical', 'Female Surgical', 'Paediatrics', 'Maternity', 'Antenatal', 'Postnatal', 'ICU', 'HDU', 'NICU', 'Emergency', 'Isolation', 'Private', 'Psychiatry', 'Other'])->default('General');
            $table->enum('gender_restriction', ['Any', 'Male Only', 'Female Only', 'Paediatrics Only'])->default('Any');
            $table->integer('total_beds')->default(0);
            $table->string('floor')->nullable();
            $table->string('wing')->nullable();
            $table->decimal('daily_room_rate', 14, 2)->default(0);
            $table->foreignId('in_charge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Closed', 'Maintenance'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        // NOTE: beds has current_admission_id -> admissions, but admissions is created AFTER beds
        // So we use unsignedBigInteger without FK here, and add the FK after admissions is created
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->string('bed_number')->unique();
            $table->string('code')->nullable();
            $table->enum('type', ['Regular', 'Electric', 'ICU', 'HDU', 'Baby Cot', 'Stretchers', 'Recovery', 'Other'])->default('Regular');
            $table->enum('class', ['General', 'Private', 'Semi-Private', 'VIP', 'Suite'])->default('General');
            $table->decimal('daily_rate', 14, 2)->default(0);
            $table->enum('status', ['Available', 'Occupied', 'Reserved', 'Out of Service', 'Cleaning', 'Maintenance'])->default('Available');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('current_admission_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('admitting_doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('attending_doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->enum('admission_type', ['Emergency', 'Routine', 'Elective', 'Transfer In', 'Maternity', 'Day Case'])->default('Routine');
            $table->enum('admission_source', ['Emergency Room', 'Outpatient Clinic', 'Referral', 'Transfer', 'Direct Admission', 'Theatre'])->default('Outpatient Clinic');
            $table->text('admission_diagnosis')->nullable();
            $table->text('admission_reason')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->enum('status', ['Admitted', 'Discharged', 'Transferred', 'Absconded', 'Death', 'Cancelled'])->default('Admitted');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Now add the FK from beds.current_admission_id -> admissions.id
        Schema::table('beds', function (Blueprint $table) {
            $table->foreign('current_admission_id')->references('id')->on('admissions')->nullOnDelete();
        });

        Schema::create('bed_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->string('transfer_number')->unique();
            $table->foreignId('admission_id')->constrained('admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('from_ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('from_bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('to_ward_id')->constrained('wards')->cascadeOnDelete();
            $table->foreignId('to_bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->dateTime('transfer_date');
            $table->text('reason')->nullable();
            $table->foreignId('authorized_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('admission_id')->constrained('admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('discharging_doctor_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('discharge_date');
            $table->enum('discharge_type', ['Improved', 'Recovered', 'Refused Treatment', 'Absconded', 'Transferred', 'Death', 'AMA', 'Other'])->default('Improved');
            $table->text('final_diagnosis')->nullable();
            $table->text('comorbidities')->nullable();
            $table->text('procedures_performed')->nullable();
            $table->text('investigations_summary')->nullable();
            $table->text('treatment_summary')->nullable();
            $table->text('treatment_given')->nullable();
            $table->text('condition_on_discharge')->nullable();
            $table->text('medications_on_discharge')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('death_cause')->nullable();
            $table->time('death_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Duty & Staff Posting ----
        Schema::create('duty_rosters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('cycle', ['Daily', 'Weekly', 'Bi-Weekly', 'Monthly', 'Quarterly'])->default('Weekly');
            $table->text('description')->nullable();
            $table->enum('status', ['Draft', 'Active', 'Closed'])->default('Draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('duty_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('duty_roster_id')->nullable()->constrained('duty_rosters')->nullOnDelete();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('shift', ['Morning', 'Afternoon', 'Night', 'On-Call', 'Full Day'])->default('Morning');
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->enum('role_in_unit', ['In-Charge', 'Supervisor', 'Officer', 'Assistant', 'Trainee'])->default('Officer');
            $table->text('notes')->nullable();
            $table->enum('status', ['Scheduled', 'Active', 'Completed', 'Cancelled', 'Swapped'])->default('Scheduled');
            $table->foreignId('posted_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Insurance ----
        Schema::create('insurance_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('short_name')->nullable();
            $table->enum('type', ['NHIA', 'HMO', 'Private Insurance', 'Corporate', 'Community-Based', 'Government'])->default('HMO');
            $table->string('nhia_code')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('capitation_rate', 14, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('claim_settlement_days')->default(30);
            $table->string('logo_path')->nullable();
            $table->enum('status', ['Active', 'Suspended', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('tier', ['Formal', 'Informal', 'Equity', 'Vulnerable', 'Premium', 'Standard', 'VIP'])->default('Standard');
            $table->enum('coverage_type', ['Individual', 'Family', 'Corporate', 'Group'])->default('Individual');
            $table->decimal('annual_premium', 14, 2)->default(0);
            $table->decimal('annual_benefit_limit', 14, 2)->default(0);
            $table->decimal('per_encounter_limit', 14, 2)->default(0);
            $table->decimal('outpatient_deduction', 14, 2)->default(0);
            $table->decimal('inpatient_deduction', 14, 2)->default(0);
            $table->decimal('copay_percentage', 5, 2)->default(0);
            $table->json('covered_services')->nullable();
            $table->json('excluded_services')->nullable();
            $table->boolean('covers_pharmacy')->default(true);
            $table->boolean('covers_lab')->default(true);
            $table->boolean('covers_radiology')->default(true);
            $table->boolean('covers_inpatient')->default(false);
            $table->boolean('covers_maternity')->default(false);
            $table->boolean('covers_dental')->default(false);
            $table->boolean('covers_optical')->default(false);
            $table->text('description')->nullable();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        // NOTE: Table name is 'patient_insurance' (singular) — all FKs must use explicit name
        Schema::create('patient_insurance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->foreignId('insurance_plan_id')->constrained('insurance_plans')->cascadeOnDelete();
            $table->string('policy_number')->unique();
            $table->string('member_id')->nullable();
            $table->string('enrollee_code')->nullable();
            $table->enum('principal_relationship', ['Self', 'Spouse', 'Child', 'Parent', 'Sibling', 'Dependant'])->default('Self');
            $table->string('principal_name')->nullable();
            $table->string('principal_dob')->nullable();
            $table->string('principal_phone')->nullable();
            $table->string('principal_nin')->nullable();
            $table->date('enrollment_date');
            $table->date('effective_date');
            $table->date('expiry_date');
            $table->decimal('remaining_benefit', 14, 2)->default(0);
            $table->decimal('utilized_benefit', 14, 2)->default(0);
            $table->boolean('is_primary')->default(true);
            $table->enum('verification_status', ['Pending', 'Verified', 'Rejected', 'Expired'])->default('Pending');
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Suspended', 'Expired', 'Cancelled'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('insurance_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->cascadeOnDelete();
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->foreignId('insurance_plan_id')->constrained('insurance_plans')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->date('service_date_start');
            $table->date('service_date_end');
            $table->enum('claim_type', ['Outpatient', 'Inpatient', 'Emergency', 'Pharmacy', 'Laboratory', 'Radiology', 'Procedure', 'Maternity'])->default('Outpatient');
            $table->string('diagnosis_icd10')->nullable();
            $table->text('diagnosis_description')->nullable();
            $table->text('treatment_summary')->nullable();
            $table->decimal('total_claimed', 14, 2)->default(0);
            $table->decimal('total_approved', 14, 2)->default(0);
            $table->decimal('total_paid', 14, 2)->default(0);
            $table->decimal('patient_copay', 14, 2)->default(0);
            $table->decimal('deduction', 14, 2)->default(0);
            $table->string('pre_authorization_code')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Acknowledged', 'Processing', 'Approved', 'Partially Approved', 'Rejected', 'Paid', 'Reversed'])->default('Draft');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('acknowledged_at')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('insurance_claim_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_claim_id')->constrained('insurance_claims')->cascadeOnDelete();
            $table->enum('service_type', ['Consultation', 'Pharmacy', 'Laboratory', 'Radiology', 'Procedure', 'Consumable', 'Accommodation', 'Other'])->default('Consultation');
            $table->string('service_code')->nullable();
            $table->string('service_name');
            $table->string('item_reference')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('amount_claimed', 14, 2)->default(0);
            $table->decimal('amount_approved', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('patient_copay', 14, 2)->default(0);
            $table->enum('status', ['Pending', 'Approved', 'Partially Approved', 'Rejected'])->default('Pending');
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pre_authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('authorization_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('patient_insurance_id')->constrained('patient_insurance')->cascadeOnDelete();
            $table->foreignId('insurance_provider_id')->constrained('insurance_providers')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->enum('service_type', ['Consultation', 'Pharmacy', 'Laboratory', 'Radiology', 'Procedure', 'Surgery', 'Inpatient Admission', 'Maternity', 'Specialist Referral', 'Other'])->default('Consultation');
            $table->text('clinical_justification');
            $table->string('diagnosis_icd10')->nullable();
            $table->text('diagnosis_description')->nullable();
            $table->decimal('estimated_cost', 14, 2)->default(0);
            $table->decimal('approved_amount', 14, 2)->default(0);
            $table->date('requested_date');
            $table->date('required_by_date')->nullable();
            $table->date('authorized_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->enum('priority', ['Routine', 'Urgent', 'Emergency'])->default('Routine');
            $table->enum('status', ['Pending', 'Approved', 'Partially Approved', 'Rejected', 'Expired', 'Utilized', 'Cancelled'])->default('Pending');
            $table->text('provider_response')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Billing & Finance ----
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Consultation', 'Procedure', 'Laboratory', 'Radiology', 'Pharmacy', 'Accommodation', 'Consumable', 'Equipment', 'Emergency', 'Other'])->default('Consultation');
            $table->string('color', 7)->default('#0f5132');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('service_category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('category', ['Consultation', 'Procedure', 'Laboratory', 'Radiology', 'Pharmacy', 'Accommodation', 'Consumable', 'Equipment', 'Emergency', 'Other'])->default('Consultation');
            $table->enum('sub_category', ['General', 'Specialist', 'Surgery', 'Dental', 'Optical', 'Mental Health', 'Maternity', 'Physiotherapy'])->default('General');
            $table->string('nhia_code')->nullable();
            $table->decimal('standard_price', 14, 2)->default(0);
            $table->decimal('nhia_rate', 14, 2)->default(0);
            $table->decimal('private_rate', 14, 2)->default(0);
            $table->decimal('cost_price', 14, 2)->default(0);
            $table->string('unit_of_measure')->nullable();
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('requires_pre_authorization')->default(false);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('patient_insurance_id')->nullable()->constrained('patient_insurance')->nullOnDelete();
            $table->enum('payment_mode', ['Cash', 'Insurance', 'NHIA', 'Corporate', 'Charity', 'Mixed'])->default('Cash');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('insurance_covered', 14, 2)->default(0);
            $table->decimal('patient_portion', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('balance_due', 14, 2)->default(0);
            $table->enum('status', ['Draft', 'Pending', 'Partially Paid', 'Paid', 'Overdue', 'Cancelled', 'Refunded'])->default('Draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('billed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->enum('service_type', ['Consultation', 'Procedure', 'Laboratory', 'Radiology', 'Pharmacy', 'Accommodation', 'Consumable', 'Other'])->default('Consultation');
            $table->string('description');
            $table->string('reference_number')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('insurance_covered', 14, 2)->default(0);
            $table->decimal('patient_portion', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('insurance_claim_id')->nullable()->constrained('insurance_claims')->nullOnDelete();
            $table->enum('payer_type', ['Patient', 'Insurance', 'NHIA', 'Corporate', 'Government', 'Other'])->default('Patient');
            $table->string('payer_name')->nullable();
            $table->enum('payment_method', ['Cash', 'Card', 'Bank Transfer', 'Cheque', 'Mobile Money', 'POS', 'Insurance', 'Online'])->default('Cash');
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('currency', 3)->default('NGN');
            $table->string('reference_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('card_last4', 4)->nullable();
            $table->dateTime('payment_date');
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Completed', 'Reversed', 'Bounced'])->default('Completed');
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Accounting ----
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('account_code', 30)->unique();
            $table->string('name');
            $table->enum('type', ['Asset', 'Liability', 'Equity', 'Revenue', 'Expense', 'Contra'])->default('Asset');
            $table->enum('sub_type', ['Current Asset', 'Fixed Asset', 'Other Asset', 'Current Liability', 'Long-term Liability', 'Other Liability', 'Capital', 'Retained Earnings', 'Operating Revenue', 'Other Revenue', 'Operating Expense', 'Administrative Expense', 'Cost of Goods Sold', 'Other Expense', 'Contra Asset', 'Contra Revenue'])->default('Current Asset');
            $table->string('currency', 3)->default('NGN');
            $table->foreignId('parent_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->decimal('opening_balance', 18, 2)->default(0);
            $table->decimal('current_balance', 18, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_bank_account')->default(false);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->date('entry_date');
            $table->enum('type', ['Sales', 'Purchase', 'Payment', 'Receipt', 'Journal', 'Payroll', 'Adjustment', 'Closing', 'Reversal'])->default('Journal');
            $table->string('reference_number')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description');
            $table->decimal('total_debit', 18, 2)->default(0);
            $table->decimal('total_credit', 18, 2)->default(0);
            $table->boolean('is_posted')->default(false);
            $table->boolean('is_reversed')->default(false);
            $table->dateTime('posted_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->decimal('debit', 18, 2)->default(0);
            $table->decimal('credit', 18, 2)->default(0);
            $table->string('cost_center')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->timestamps();
        });

        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('chart_of_account_id')->nullable()->constrained('chart_of_accounts')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('period', ['Monthly', 'Quarterly', 'Annual'])->default('Annual');
            $table->year('fiscal_year');
            $table->integer('quarter')->nullable();
            $table->integer('month')->nullable();
            $table->decimal('planned_amount', 18, 2)->default(0);
            $table->decimal('actual_amount', 18, 2)->default(0);
            $table->decimal('variance', 18, 2)->default(0);
            $table->enum('status', ['Draft', 'Approved', 'Active', 'Closed', 'Revised'])->default('Draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('claimant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('title');
            $table->enum('type', ['Travel', 'Medical Supplies', 'Office Supplies', 'Maintenance', 'Utility', 'Transport', 'Meals', 'Training', 'Other'])->default('Other');
            $table->date('expense_date');
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->decimal('reimbursable_amount', 18, 2)->default(0);
            $table->decimal('approved_amount', 18, 2)->default(0);
            $table->string('currency', 3)->default('NGN');
            $table->text('description')->nullable();
            $table->json('receipts')->nullable();
            $table->enum('status', ['Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Paid', 'Cancelled'])->default('Draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dispensing_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->foreignId('in_charge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });


        Schema::create('petty_cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['Top Up', 'Expense', 'Reimbursement', 'Adjustment'])->default('Expense');
            $table->dateTime('transaction_date');
            $table->decimal('amount', 18, 2)->default(0);
            $table->decimal('balance_after', 18, 2)->default(0);
            $table->string('payee')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Posted'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained('receipts')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_received', 12, 2)->default(0);
            $table->decimal('quantity_rejected', 12, 2)->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ---- Pharmacy ----
        Schema::create('drug_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('drug_category_id')->nullable()->constrained('drug_categories')->nullOnDelete();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('code')->unique();
            $table->string('atc_code')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('strength')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('reorder_level', 12, 2)->default(0);
            $table->decimal('reorder_quantity', 12, 2)->default(0);
            $table->boolean('is_controlled')->default(false);
            $table->boolean('is_antenatal_safe')->default(false);
            $table->boolean('is_pediatric_safe')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('drug_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->string('batch_number');
            $table->date('expiry_date')->nullable();
            $table->date('manufactured_date')->nullable();
            $table->decimal('quantity_received', 12, 2)->default(0);
            $table->decimal('quantity_remaining', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->date('received_date')->nullable();
            $table->boolean('is_quarantined')->default(false);
            $table->timestamps();
        });

        Schema::create('drug_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->foreignId('drug_a_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_b_id')->constrained('drugs')->cascadeOnDelete();
            $table->enum('severity', ['Minor', 'Moderate', 'Major', 'Contraindicated'])->default('Moderate');
            $table->enum('effect', ['Additive', 'Synergistic', 'Antagonistic', 'Altered metabolism', 'Increased toxicity', 'Decreased efficacy', 'Other'])->default('Other');
            $table->text('description')->nullable();
            $table->text('clinical_action')->nullable();
            $table->string('source')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('registration_number')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dispensing_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->foreignId('prescription_item_id')->nullable()->constrained('prescription_items')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('drug_id')->nullable()->constrained('drugs')->nullOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->foreignId('dispensed_by')->constrained('users')->cascadeOnDelete();
            $table->string('dispensing_number')->nullable();
            $table->dateTime('dispensed_at');
            $table->decimal('quantity_dispensed', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('prescriber_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->string('prescription_number')->unique();
            $table->dateTime('prescribed_at');
            $table->dateTime('dispensed_at')->nullable();
            $table->foreignId('dispensed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Pending', 'Partially Dispensed', 'Dispensed', 'Cancelled', 'Rejected'])->default('Pending');
            $table->enum('priority', ['Routine', 'Urgent', 'STAT'])->default('Routine');
            $table->boolean('is_inpatient')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->string('drug_name');
            $table->string('dosage_form')->nullable();
            $table->string('strength')->nullable();
            $table->string('dose')->nullable();
            $table->string('route')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('quantity_dispensed', 12, 2)->default(0);
            $table->text('instructions')->nullable();
            $table->enum('status', ['Pending', 'Dispensed', 'Partially Dispensed', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['Receipt', 'Transfer In', 'Transfer Out', 'Dispense', 'Adjustment In', 'Adjustment Out', 'Return', 'Disposal', 'Stock Take']);
            $table->string('reference_number')->nullable();
            $table->dateTime('movement_date');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
        });


        Schema::create('stock_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->foreignId('prescription_id')->nullable()->constrained('prescriptions')->nullOnDelete();
            $table->foreignId('returned_by')->constrained('users')->cascadeOnDelete();
            $table->dateTime('return_date');
            $table->decimal('quantity_returned', 12, 2)->default(0);
            $table->string('reason')->nullable();
            $table->enum('condition', ['Good', 'Damaged', 'Expired', 'Sealed'])->default('Good');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Restocked'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Lab & Radiology ----
        Schema::create('lab_panels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('loinc_code')->nullable();
            $table->string('specimen_type')->nullable();
            $table->string('container')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->integer('turnaround_hours')->default(24);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('lab_panel_id')->nullable()->constrained('lab_panels')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('loinc_code')->nullable();
            $table->string('specimen_type')->nullable();
            $table->string('container')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('reference_range_low', 10, 2)->nullable();
            $table->decimal('reference_range_high', 10, 2)->nullable();
            $table->string('reference_notes')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->integer('turnaround_hours')->default(24);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->dateTime('ordered_at');
            $table->dateTime('collected_at')->nullable();
            $table->dateTime('resulted_at')->nullable();
            $table->enum('priority', ['Routine', 'Urgent', 'STAT', 'ASAP'])->default('Routine');
            $table->enum('status', ['Pending', 'Sample Collected', 'In Progress', 'Completed', 'Verified', 'Cancelled', 'Rejected'])->default('Pending');
            $table->text('clinical_notes')->nullable();
            $table->text('specimen_source')->nullable();
            $table->string('fasting_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lab_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained('lab_orders')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->string('test_name');
            $table->string('loinc_code')->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Verified', 'Cancelled'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_item_id')->constrained('lab_order_items')->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained('lab_tests')->cascadeOnDelete();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('value')->nullable();
            $table->string('value_numeric')->nullable();
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();
            $table->enum('flag', ['Normal', 'Low', 'High', 'Critical Low', 'Critical High', 'Abnormal'])->default('Normal');
            $table->text('interpretation')->nullable();
            $table->text('comments')->nullable();
            $table->dateTime('performed_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        Schema::create('radiology_modalities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->integer('turnaround_hours')->default(24);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('radiology_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('modality_id')->constrained('radiology_modalities')->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->dateTime('ordered_at');
            $table->dateTime('performed_at')->nullable();
            $table->dateTime('resulted_at')->nullable();
            $table->string('examination');
            $table->string('body_part')->nullable();
            $table->text('clinical_indication')->nullable();
            $table->text('request_notes')->nullable();
            $table->enum('priority', ['Routine', 'Urgent', 'STAT'])->default('Routine');
            $table->enum('status', ['Pending', 'Scheduled', 'In Progress', 'Completed', 'Verified', 'Cancelled'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('radiology_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_order_id')->constrained('radiology_orders')->cascadeOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('findings');
            $table->text('impression')->nullable();
            $table->text('recommendations')->nullable();
            $table->longText('images')->nullable();
            $table->dateTime('reported_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // ---- Schedule Configuration ----
        Schema::create('appointment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('category', ['New', 'Follow-up', 'Review', 'Procedure', 'Telemedicine', 'Emergency', 'Specialist'])->default('New');
            $table->string('color', 7)->default('#0f5132');
            $table->integer('default_duration_minutes')->default(30);
            $table->decimal('default_fee', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->boolean('requires_pre_auth')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('clinic_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('appointment_type_id')->nullable()->constrained('appointment_types')->nullOnDelete();
            $table->enum('weekday', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->integer('slot_duration_minutes')->default(15);
            $table->integer('max_patients_per_slot')->default(1);
            $table->integer('buffer_minutes_before')->default(0);
            $table->integer('buffer_minutes_after')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('provider_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->enum('weekday', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->integer('slot_duration_minutes')->default(15);
            $table->integer('max_patients_per_slot')->default(1);
            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('appointment_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('appointment_type_id')->nullable()->constrained('appointment_types')->nullOnDelete();
            $table->foreignId('clinic_schedule_id')->nullable()->constrained('clinic_schedules')->nullOnDelete();
            $table->foreignId('provider_schedule_id')->nullable()->constrained('provider_schedules')->nullOnDelete();
            $table->date('slot_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes')->default(15);
            $table->integer('capacity')->default(1);
            $table->integer('booked_count')->default(0);
            $table->enum('status', ['Available', 'Booked', 'Blocked', 'Completed', 'Cancelled', 'No Show'])->default('Available');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->date('holiday_date');
            $table->date('end_date')->nullable();
            $table->enum('type', ['Public Holiday', 'Religious', 'National', 'Hospital-specific', 'Local'])->default('Public Holiday');
            $table->boolean('is_working_day')->default(false);
            $table->boolean('affects_clinics')->default(true);
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('leave_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->enum('leave_type', ['Annual', 'Sick', 'Casual', 'Maternity', 'Paternity', 'Compassionate', 'Study', 'Sabbatical', 'Unpaid', 'Other'])->default('Annual');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_requested');
            $table->integer('days_approved')->nullable();
            $table->text('reason');
            $table->string('handover_to')->nullable();
            $table->text('handover_notes')->nullable();
            $table->json('attachment_paths')->nullable();
            $table->enum('status', ['Draft', 'Pending', 'Recommended', 'Approved', 'Rejected', 'Cancelled', 'On Leave', 'Returned'])->default('Pending');
            $table->foreignId('recommended_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('recommended_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Store & Inventory ----
        Schema::create('store_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Medical Supplies', 'Office Supplies', 'Cleaning', 'Furniture', 'Equipment', 'IT', 'Kitchen', 'Linens', 'Fuel & Lubricants', 'Stationery', 'PPE', 'Other'])->default('Medical Supplies');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Main Store', 'Sub-Store', 'Pharmacy Store', 'Theatre Store', 'Lab Store', 'Ward Store', 'Emergency Store', 'Other'])->default('Main Store');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();
            $table->foreignId('in_charge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('store_category_id')->nullable()->constrained('store_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('barcode')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('supplier')->nullable();
            $table->string('unit_of_measure');
            $table->string('pack_size')->nullable();
            $table->integer('units_per_pack')->default(1);
            $table->decimal('cost_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->decimal('reorder_level', 14, 2)->default(0);
            $table->decimal('reorder_quantity', 14, 2)->default(0);
            $table->boolean('is_consumable')->default(true);
            $table->boolean('is_reusable')->default(false);
            $table->boolean('requires_cold_chain')->default(false);
            $table->boolean('is_controlled')->default(false);
            $table->boolean('tracks_expiry')->default(false);
            $table->boolean('tracks_serial')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('store_item_id')->constrained('store_items')->cascadeOnDelete();
            $table->foreignId('store_location_id')->constrained('store_locations')->cascadeOnDelete();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_received', 14, 2)->default(0);
            $table->decimal('quantity_remaining', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->date('received_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_quarantined')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('store_item_id')->constrained('store_items')->cascadeOnDelete();
            $table->foreignId('store_stock_id')->nullable()->constrained('store_stocks')->nullOnDelete();
            $table->foreignId('from_location_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['Receipt', 'Transfer In', 'Transfer Out', 'Issue', 'Return', 'Adjustment In', 'Adjustment Out', 'Disposal', 'Stock Take'])->default('Issue');
            $table->string('reference_number')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->dateTime('movement_date');
            $table->decimal('quantity', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('requesting_department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('requesting_location_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->foreignId('source_location_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('required_date');
            $table->date('approved_date')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('received_date')->nullable();
            $table->enum('priority', ['Routine', 'Urgent', 'Emergency'])->default('Routine');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Partially Issued', 'Issued', 'Received', 'Rejected', 'Cancelled'])->default('Draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('store_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_requisition_id')->constrained('store_requisitions')->cascadeOnDelete();
            $table->foreignId('store_item_id')->constrained('store_items')->cascadeOnDelete();
            $table->decimal('quantity_requested', 14, 2)->default(0);
            $table->decimal('quantity_approved', 14, 2)->default(0);
            $table->decimal('quantity_issued', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_takes', function (Blueprint $table) {
            $table->id();
            $table->string('stock_take_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->enum('scope', ['Full Hospital', 'Single Location', 'Pharmacy', 'Specific Items'])->default('Full Hospital');
            $table->date('stock_take_date');
            $table->foreignId('initiated_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('counted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Planned', 'In Progress', 'Counted', 'Verified', 'Closed', 'Cancelled'])->default('Planned');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_take_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_take_id')->constrained('stock_takes')->cascadeOnDelete();
            $table->foreignId('store_item_id')->nullable()->constrained('store_items')->nullOnDelete();
            $table->foreignId('drug_id')->nullable()->constrained('drugs')->nullOnDelete();
            $table->string('item_name');
            $table->string('batch_number')->nullable();
            $table->decimal('system_quantity', 14, 2)->default(0);
            $table->decimal('counted_quantity', 14, 2)->default(0);
            $table->decimal('variance', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('variance_value', 14, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // ---- General Store ----
        Schema::create('general_store_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Main Store', 'Sub-Store', 'Ward Store', 'Department Store', 'Theatre Store', 'Emergency Store', 'Other'])->default('Main Store');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('room')->nullable();
            $table->foreignId('in_charge_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Medical Supplies', 'Office Supplies', 'Cleaning', 'Furniture', 'Equipment', 'IT', 'Kitchen', 'Linens', 'Fuel & Lubricants', 'Stationery', 'PPE', 'Surgical Consumables', 'Lab Consumables', 'Other'])->default('Medical Supplies');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('general_store_category_id')->nullable()->constrained('general_store_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('barcode')->nullable();
            $table->string('generic_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('supplier')->nullable();
            $table->string('unit_of_measure');
            $table->string('pack_size')->nullable();
            $table->integer('units_per_pack')->default(1);
            $table->decimal('cost_price', 14, 2)->default(0);
            $table->decimal('selling_price', 14, 2)->default(0);
            $table->decimal('reorder_level', 14, 2)->default(0);
            $table->decimal('reorder_quantity', 14, 2)->default(0);
            $table->boolean('is_consumable')->default(true);
            $table->boolean('is_reusable')->default(false);
            $table->boolean('requires_cold_chain')->default(false);
            $table->boolean('is_controlled')->default(false);
            $table->boolean('tracks_expiry')->default(false);
            $table->boolean('tracks_serial')->default(false);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('general_store_item_id')->constrained('general_store_items')->cascadeOnDelete();
            $table->foreignId('general_store_location_id')->constrained('general_store_locations')->cascadeOnDelete();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_received', 14, 2)->default(0);
            $table->decimal('quantity_remaining', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->date('received_date')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_quarantined')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('general_store_item_id')->constrained('general_store_items')->cascadeOnDelete();
            $table->foreignId('general_store_stock_id')->nullable()->constrained('general_store_stocks')->nullOnDelete();
            $table->foreignId('from_location_id')->nullable()->constrained('general_store_locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('general_store_locations')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['Receipt', 'Transfer In', 'Transfer Out', 'Issue', 'Return', 'Adjustment In', 'Adjustment Out', 'Disposal', 'Stock Take'])->default('Issue');
            $table->string('reference_number')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->dateTime('movement_date');
            $table->decimal('quantity', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('requesting_department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('requesting_location_id')->nullable()->constrained('general_store_locations')->nullOnDelete();
            $table->foreignId('source_location_id')->nullable()->constrained('general_store_locations')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('required_date');
            $table->date('approved_date')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('received_date')->nullable();
            $table->enum('priority', ['Routine', 'Urgent', 'Emergency'])->default('Routine');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Partially Issued', 'Issued', 'Received', 'Rejected', 'Cancelled'])->default('Draft');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('general_store_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('general_store_requisition_id')->constrained('general_store_requisitions', 'id', 'gs_req_items_req_id_foreign')->cascadeOnDelete();
            $table->foreignId('general_store_item_id')->constrained('general_store_items')->cascadeOnDelete();
            $table->decimal('quantity_requested', 14, 2)->default(0);
            $table->decimal('quantity_approved', 14, 2)->default(0);
            $table->decimal('quantity_issued', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ---- Blood Bank ----
        Schema::create('blood_donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('donor_number')->unique();
            $table->enum('donor_type', ['Voluntary', 'Replacement', 'Autologous', 'Directed', 'Paid'])->default('Voluntary');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->date('date_of_birth');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('occupation')->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->enum('rh_factor', ['Positive', 'Negative']);
            $table->date('last_donation_date')->nullable();
            $table->integer('total_donations')->default(0);
            $table->decimal('total_volume_donated_ml', 8, 0)->default(0);
            $table->boolean('is_eligible')->default(true);
            $table->text('ineligibility_reason')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Deferred', 'Blacklisted'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blood_donations', function (Blueprint $table) {
            $table->id();
            $table->string('donation_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('donor_id')->constrained('blood_donors')->cascadeOnDelete();
            $table->date('donation_date');
            $table->time('donation_time');
            $table->enum('donation_type', ['Whole Blood', 'Plasma', 'Platelets', 'Double Red Cells', 'Cryoprecipitate'])->default('Whole Blood');
            $table->decimal('volume_collected_ml', 6, 0)->default(450);
            $table->foreignId('collected_by')->constrained('users')->cascadeOnDelete();
            $table->string('collection_site')->nullable();
            $table->string('vein_used')->nullable();
            $table->text('adverse_reactions')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Collected', 'Screening', 'Quarantined', 'Available', 'Discarded', 'Expired', 'Transfused'])->default('Collected');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('donation_id')->constrained('blood_donations')->cascadeOnDelete();
            $table->foreignId('donor_id')->constrained('blood_donors')->cascadeOnDelete();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
            $table->enum('component_type', ['Whole Blood', 'Packed Red Cells', 'Plasma', 'Platelets', 'Cryoprecipitate', 'Fresh Frozen Plasma'])->default('Whole Blood');
            $table->decimal('volume_ml', 6, 0)->default(450);
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->string('storage_location')->nullable();
            $table->decimal('storage_temperature_c', 4, 1)->default(4.0);
            $table->enum('hiv_status', ['Pending', 'Negative', 'Positive', 'Not Tested'])->default('Pending');
            $table->enum('hepatitis_b_status', ['Pending', 'Negative', 'Positive', 'Not Tested'])->default('Pending');
            $table->enum('hepatitis_c_status', ['Pending', 'Negative', 'Positive', 'Not Tested'])->default('Pending');
            $table->enum('syphilis_status', ['Pending', 'Negative', 'Positive', 'Not Tested'])->default('Pending');
            $table->enum('malaria_status', ['Pending', 'Negative', 'Positive', 'Not Tested'])->default('Pending');
            $table->text('screening_notes')->nullable();
            $table->foreignId('screened_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('screened_at')->nullable();
            $table->boolean('is_screened')->default(false);
            $table->boolean('is_quarantined')->default(false);
            $table->enum('status', ['Collected', 'Screening', 'Available', 'Reserved', 'Transfused', 'Discarded', 'Expired'])->default('Collected');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->enum('requested_blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown']);
            $table->enum('component_requested', ['Whole Blood', 'Packed Red Cells', 'Plasma', 'Platelets', 'Cryoprecipitate', 'Fresh Frozen Plasma'])->default('Whole Blood');
            $table->decimal('units_requested', 6, 0)->default(1);
            $table->enum('urgency', ['Routine', 'Urgent', 'Emergency', 'STAT'])->default('Routine');
            $table->dateTime('required_by');
            $table->text('clinical_indication')->nullable();
            $table->text('diagnosis')->nullable();
            $table->decimal('patient_hb_level', 5, 1)->nullable();
            $table->enum('patient_blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown'])->default('Unknown');
            $table->enum('patient_rh_factor', ['Positive', 'Negative', 'Unknown'])->default('Unknown');
            $table->text('crossmatch_notes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Crossmatched', 'Issued', 'Transfused', 'Cancelled', 'Rejected'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('crossmatched_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('crossmatched_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('issued_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blood_crossmatches', function (Blueprint $table) {
            $table->id();
            $table->string('crossmatch_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('blood_request_id')->constrained('blood_requests')->cascadeOnDelete();
            $table->foreignId('blood_unit_id')->constrained('blood_units')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('performed_by')->constrained('users')->cascadeOnDelete();
            $table->enum('method', ['Saline', 'Albumin', 'AHG', 'Gel Card', 'Electronic Crossmatch'])->default('Saline');
            $table->enum('major_crossmatch', ['Compatible', 'Incompatible', 'Pending'])->default('Pending');
            $table->enum('minor_crossmatch', ['Compatible', 'Incompatible', 'Not Done', 'Pending'])->default('Not Done');
            $table->text('results')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('performed_at');
            $table->enum('status', ['Pending', 'Compatible', 'Incompatible', 'Expired', 'Transfused', 'Discarded'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blood_transfusions', function (Blueprint $table) {
            $table->id();
            $table->string('transfusion_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->foreignId('blood_request_id')->constrained('blood_requests')->cascadeOnDelete();
            $table->foreignId('blood_unit_id')->constrained('blood_units')->cascadeOnDelete();
            $table->foreignId('crossmatch_id')->nullable()->constrained('blood_crossmatches')->nullOnDelete();
            $table->foreignId('transfused_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('transfusion_started_at');
            $table->dateTime('transfusion_ended_at')->nullable();
            $table->decimal('volume_transfused_ml', 6, 0)->default(0);
            $table->string('site_of_transfusion')->nullable();
            $table->string('cannula_size')->nullable();
            $table->decimal('pre_transfusion_bp', 8, 0)->nullable();
            $table->decimal('post_transfusion_bp', 8, 0)->nullable();
            $table->decimal('pre_transfusion_temp_c', 4, 1)->nullable();
            $table->decimal('post_transfusion_temp_c', 4, 1)->nullable();
            $table->decimal('pre_transfusion_pulse', 4, 0)->nullable();
            $table->decimal('post_transfusion_pulse', 4, 0)->nullable();
            $table->text('adverse_reactions')->nullable();
            $table->enum('reaction_severity', ['None', 'Mild', 'Moderate', 'Severe', 'Fatal'])->default('None');
            $table->text('action_taken')->nullable();
            $table->enum('status', ['In Progress', 'Completed', 'Interrupted', 'Cancelled'])->default('In Progress');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Procedures ----
        Schema::create('procedure_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('specialty', ['General Surgery', 'Orthopaedics', 'OBGYN', 'ENT', 'Ophthalmology', 'Dental', 'Cardiology', 'Urology', 'Neurosurgery', 'Plastic Surgery', 'Paediatric Surgery', 'Endoscopy', 'Other'])->default('General Surgery');
            $table->text('description')->nullable();
            $table->boolean('requires_consent')->default(true);
            $table->boolean('requires_anaesthesia')->default(true);
            $table->boolean('requires_pre_op_assessment')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('procedure_category_id')->nullable()->constrained('procedure_categories')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icd10_pcs_code')->nullable();
            $table->string('cpt_code')->nullable();
            $table->enum('type', ['Minor', 'Major', 'Intermediate', 'Complex', 'Day Case'])->default('Minor');
            $table->enum('site', ['Theatre', 'Procedure Room', 'Ward Bedside', 'Consulting Room', 'Emergency Room', 'ICU', 'Other'])->default('Theatre');
            $table->enum('anaesthesia_type', ['Local', 'Regional', 'Spinal', 'Epidural', 'General', 'Sedation', 'None'])->default('Local');
            $table->integer('estimated_duration_minutes')->default(60);
            $table->integer('default_pre_op_days')->default(0);
            $table->integer('default_post_op_days')->default(1);
            $table->text('description')->nullable();
            $table->text('indication')->nullable();
            $table->text('contraindication')->nullable();
            $table->text('pre_op_instructions')->nullable();
            $table->text('post_op_instructions')->nullable();
            $table->text('complications')->nullable();
            $table->decimal('standard_fee', 14, 2)->default(0);
            $table->decimal('nhia_rate', 14, 2)->default(0);
            $table->decimal('private_rate', 14, 2)->default(0);
            $table->decimal('surgeon_fee', 14, 2)->default(0);
            $table->decimal('anaesthetist_fee', 14, 2)->default(0);
            $table->decimal('theatre_fee', 14, 2)->default(0);
            $table->decimal('consumables_fee', 14, 2)->default(0);
            $table->boolean('requires_pre_authorization')->default(false);
            $table->boolean('requires_blood_reservation')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('procedure_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->foreignId('procedure_id')->constrained('procedures')->cascadeOnDelete();
            $table->foreignId('surgeon_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('anaesthetist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assistant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('scrub_nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('circulating_nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('theatre_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->dateTime('scheduled_start');
            $table->dateTime('scheduled_end')->nullable();
            $table->enum('priority', ['Elective', 'Urgent', 'Emergency'])->default('Elective');
            $table->enum('booking_status', ['Scheduled', 'Confirmed', 'In Progress', 'Completed', 'Cancelled', 'Postponed', 'No Show'])->default('Scheduled');
            $table->date('pre_op_assessment_date')->nullable();
            $table->boolean('consent_signed')->default(false);
            $table->dateTime('consent_signed_at')->nullable();
            $table->foreignId('consent_witnessed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('fasting_confirmed')->default(false);
            $table->boolean('investigations_done')->default(false);
            $table->boolean('anaesthesia_review_done')->default(false);
            $table->boolean('blood_reserved')->default(false);
            $table->text('pre_op_notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('procedure_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->nullable()->default(1);
            $table->string('session_number')->unique();
            $table->foreignId('procedure_booking_id')->constrained('procedure_bookings')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('procedure_id')->constrained('procedures')->cascadeOnDelete();
            $table->foreignId('surgeon_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('anaesthetist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('theatre_id')->nullable()->constrained('store_locations')->nullOnDelete();
            $table->dateTime('actual_start');
            $table->dateTime('actual_end')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->enum('anaesthesia_used', ['Local', 'Regional', 'Spinal', 'Epidural', 'General', 'Sedation', 'None'])->default('Local');
            $table->text('anaesthesia_notes')->nullable();
            $table->text('findings_intra_op')->nullable();
            $table->text('procedure_performed')->nullable();
            $table->text('steps_taken')->nullable();
            $table->text('complications_intra_op')->nullable();
            $table->text('blood_loss_ml')->nullable();
            $table->text('specimens_sent')->nullable();
            $table->text('implants_used')->nullable();
            $table->text('drains_placed')->nullable();
            $table->text('dressing_applied')->nullable();
            $table->text('post_op_instructions')->nullable();
            $table->enum('outcome', ['Successful', 'Complicated', 'Incomplete', 'Abandoned', 'Death'])->default('Successful');
            $table->text('notes')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['In Progress', 'Completed', 'Abandoned', 'Cancelled'])->default('In Progress');
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Audit ----
        Schema::create('audit_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['Medical', 'Internal', 'Financial', 'Operational', 'Compliance', 'IT', 'Clinical', 'Pharmacy', 'Laboratory', 'Nursing', 'Other'])->default('Medical');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('medical_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('audit_category_id')->nullable()->constrained('audit_categories')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
            $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('audit_type', ['Case Review', 'Mortality Review', 'Morbidity Review', 'Documentation Audit', 'Prescription Audit', 'Lab Utilization', 'Infection Control', 'Patient Safety', 'Clinical Guideline Compliance', 'Surgical Audit', 'Medication Safety', 'Other'])->default('Case Review');
            $table->date('audit_date');
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->text('audit_scope')->nullable();
            $table->text('objectives')->nullable();
            $table->text('methodology')->nullable();
            $table->text('summary')->nullable();
            $table->text('key_findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('action_plan')->nullable();
            $table->enum('risk_level', ['Low', 'Moderate', 'High', 'Critical'])->default('Low');
            $table->enum('status', ['Planned', 'In Progress', 'Completed', 'Reviewed', 'Closed', 'Cancelled'])->default('Planned');
            $table->date('review_date')->nullable();
            $table->text('review_notes')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('medical_audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_audit_id')->constrained('medical_audits')->cascadeOnDelete();
            $table->string('finding_number');
            $table->enum('category', ['Clinical', 'Documentation', 'Medication', 'Procedure', 'Diagnostic', 'Communication', 'Follow-up', 'Other'])->default('Clinical');
            $table->string('title');
            $table->text('description');
            $table->text('criteria')->nullable();
            $table->text('condition_observed')->nullable();
            $table->text('cause')->nullable();
            $table->text('effect')->nullable();
            $table->enum('severity', ['Minor', 'Moderate', 'Major', 'Critical'])->default('Minor');
            $table->enum('risk_level', ['Low', 'Moderate', 'High', 'Critical'])->default('Low');
            $table->text('recommendation')->nullable();
            $table->text('corrective_action')->nullable();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('target_completion_date')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Implemented', 'Verified', 'Closed'])->default('Open');
            $table->date('date_closed')->nullable();
            $table->text('closure_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('internal_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('audit_category_id')->nullable()->constrained('audit_categories')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lead_auditor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('audit_type', ['Financial', 'Operational', 'Compliance', 'IT', 'HR', 'Procurement', 'Inventory', 'Revenue Cycle', 'Payroll', 'Asset Management', 'Risk Management', 'Other'])->default('Financial');
            $table->date('audit_date');
            $table->date('period_from');
            $table->date('period_to');
            $table->text('audit_scope');
            $table->text('objectives')->nullable();
            $table->text('methodology')->nullable();
            $table->text('documents_reviewed')->nullable();
            $table->text('persons_interviewed')->nullable();
            $table->text('summary')->nullable();
            $table->text('key_findings')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('management_response')->nullable();
            $table->text('action_plan')->nullable();
            $table->decimal('financial_impact', 18, 2)->default(0);
            $table->enum('risk_level', ['Low', 'Moderate', 'High', 'Critical'])->default('Low');
            $table->enum('status', ['Planned', 'In Progress', 'Draft Report', 'Final Report', 'Management Response', 'Action Plan', 'Closed', 'Cancelled'])->default('Planned');
            $table->date('report_date')->nullable();
            $table->date('management_response_date')->nullable();
            $table->date('final_report_date')->nullable();
            $table->date('closure_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('internal_audit_findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_audit_id')->constrained('internal_audits')->cascadeOnDelete();
            $table->string('finding_number');
            $table->enum('category', ['Financial', 'Operational', 'Compliance', 'IT', 'HR', 'Procurement', 'Other'])->default('Financial');
            $table->string('title');
            $table->text('description');
            $table->text('criteria')->nullable();
            $table->text('condition_observed')->nullable();
            $table->text('cause')->nullable();
            $table->text('effect')->nullable();
            $table->text('evidence')->nullable();
            $table->decimal('financial_impact', 18, 2)->default(0);
            $table->enum('severity', ['Minor', 'Moderate', 'Major', 'Critical'])->default('Minor');
            $table->enum('risk_level', ['Low', 'Moderate', 'High', 'Critical'])->default('Low');
            $table->text('recommendation')->nullable();
            $table->text('management_response')->nullable();
            $table->text('corrective_action')->nullable();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->date('target_completion_date')->nullable();
            $table->enum('status', ['Open', 'In Progress', 'Implemented', 'Verified', 'Closed', 'Rejected'])->default('Open');
            $table->date('date_closed')->nullable();
            $table->text('closure_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->string('follow_up_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->enum('audit_type', ['Medical', 'Internal'])->default('Medical');
            $table->foreignId('medical_audit_id')->nullable()->constrained('medical_audits')->nullOnDelete();
            $table->foreignId('internal_audit_id')->nullable()->constrained('internal_audits')->nullOnDelete();
            $table->unsignedBigInteger('finding_id')->nullable();
            $table->foreignId('followed_up_by')->constrained('users')->cascadeOnDelete();
            $table->date('follow_up_date');
            $table->text('progress_notes');
            $table->text('evidence_provided')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('progress_status', ['Not Started', 'In Progress', 'Delayed', 'Completed', 'Overdue'])->default('In Progress');
            $table->integer('completion_percentage')->default(0);
            $table->date('next_follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Patient Dependants & Relationships ----
        Schema::create('patient_dependants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('principal_patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('patient_insurance_id')->nullable()->constrained('patient_insurance')->nullOnDelete();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('relationship', ['Spouse', 'Child', 'Parent', 'Sibling', 'Dependant', 'Other']);
            $table->string('national_id')->nullable();
            $table->enum('national_id_type', ['NIN', 'Voter Card', 'Driver License', 'International Passport', 'Birth Certificate', 'Other'])->nullable();
            $table->string('photo_path')->nullable();
            $table->string('thumbprint_path')->nullable();
            $table->longText('thumbprint_data')->nullable();
            $table->dateTime('biometric_captured_at')->nullable();
            $table->foreignId('biometric_captured_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('verification_status', ['Pending', 'Verified', 'Rejected', 'Expired'])->default('Pending');
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('relationship_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('category', ['Family', 'Medical', 'Financial', 'Legal', 'Insurance', 'Referral', 'Emergency', 'Other'])->default('Family');
            $table->text('description')->nullable();
            $table->boolean('is_symmetric')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('patient_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('related_patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('relationship_type_id')->constrained('relationship_types')->cascadeOnDelete();
            $table->string('related_party_name')->nullable();
            $table->string('related_party_phone', 30)->nullable();
            $table->string('related_party_email')->nullable();
            $table->text('related_party_address')->nullable();
            $table->text('notes')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_primary_contact')->default(false);
            $table->boolean('is_emergency_contact')->default(false);
            $table->boolean('can_make_decisions')->default(false);
            $table->enum('status', ['Active', 'Inactive', 'Terminated'])->default('Active');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Reports ----
        Schema::create('report_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('category', ['Patient', 'Clinical', 'Financial', 'Pharmacy', 'Laboratory', 'Radiology', 'Insurance', 'HR', 'Inventory', 'Audit', 'Mortality', 'Morbidity', 'Infection', 'Quality', 'Operational', 'Other'])->default('Patient');
            $table->enum('frequency', ['On Demand', 'Daily', 'Weekly', 'Monthly', 'Quarterly', 'Annual'])->default('On Demand');
            $table->text('description')->nullable();
            $table->json('parameters')->nullable();
            $table->json('columns')->nullable();
            $table->json('filters')->nullable();
            $table->json('group_by')->nullable();
            $table->json('chart_config')->nullable();
            $table->string('data_source')->nullable();
            $table->text('sql_query')->nullable();
            $table->boolean('is_scheduled')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('saved_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('report_template_id')->nullable()->constrained('report_templates')->nullOnDelete();
            $table->string('title');
            $table->enum('category', ['Patient', 'Clinical', 'Financial', 'Pharmacy', 'Laboratory', 'Radiology', 'Insurance', 'HR', 'Inventory', 'Audit', 'Mortality', 'Morbidity', 'Infection', 'Quality', 'Operational', 'Other'])->default('Patient');
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('parameters')->nullable();
            $table->json('data')->nullable();
            $table->json('summary')->nullable();
            $table->integer('record_count')->default(0);
            $table->decimal('total_value', 18, 2)->nullable();
            $table->string('file_path')->nullable();
            $table->enum('format', ['HTML', 'PDF', 'Excel', 'CSV', 'JSON'])->default('HTML');
            $table->enum('status', ['Generating', 'Completed', 'Failed', 'Archived'])->default('Generating');
            $table->text('error_message')->nullable();
            $table->foreignId('generated_by')->constrained('users')->cascadeOnDelete();
            $table->dateTime('generated_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('report_template_id')->constrained('report_templates')->cascadeOnDelete();
            $table->string('name');
            $table->enum('frequency', ['Daily', 'Weekly', 'Monthly', 'Quarterly', 'Annual'])->default('Monthly');
            $table->time('run_at')->default('08:00:00');
            $table->integer('day_of_week')->nullable();
            $table->integer('day_of_month')->nullable();
            $table->integer('month_of_quarter')->nullable();
            $table->json('parameters')->nullable();
            $table->json('recipients')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('last_run_at')->nullable();
            $table->dateTime('next_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('report_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('report_template_id')->nullable()->constrained('report_templates')->nullOnDelete();
            $table->foreignId('report_schedule_id')->nullable()->constrained('report_schedules')->nullOnDelete();
            $table->enum('delivery_method', ['Email', 'In-App', 'Both'])->default('Email');
            $table->json('filter_preferences')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ---- Pharmacy Stock Enhancements ----
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->string('purchase_number')->unique();
            $table->string('invoice_number')->nullable();
            $table->string('lpo_number')->nullable();
            $table->date('order_date');
            $table->date('received_date')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->enum('status', ['Draft', 'Ordered', 'Partially Received', 'Received', 'Cancelled'])->default('Draft');
            $table->enum('payment_status', ['Unpaid', 'Partially Paid', 'Paid'])->default('Unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->string('drug_name');
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->decimal('quantity_ordered', 12, 2)->default(0);
            $table->decimal('quantity_received', 12, 2)->default(0);
            $table->decimal('unit_cost', 12, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->timestamps();
        });


        // ---- Purchases & Receipts ----
        Schema::create('goods_received_notes', function (Blueprint $table) {
            $table->id();
            $table->string('grn_number')->unique();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('receipt_id')->nullable()->constrained('receipts')->nullOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('received_at');
            $table->string('invoice_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->string('lpo_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('total_value', 14, 2)->default(0);
            $table->decimal('total_rejected', 14, 2)->default(0);
            $table->enum('status', ['Pending', 'Verified', 'Rejected', 'Posted'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('goods_received_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grn_id')->constrained('goods_received_notes')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('drug_batch_id')->nullable()->constrained('drug_batches')->nullOnDelete();
            $table->string('drug_name');
            $table->string('batch_number');
            $table->date('expiry_date');
            $table->date('manufactured_date')->nullable();
            $table->decimal('quantity_ordered', 12, 2)->default(0);
            $table->decimal('quantity_received', 12, 2)->default(0);
            $table->decimal('quantity_rejected', 12, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('drug_stock_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('drug_id')->constrained('drugs')->cascadeOnDelete();
            $table->foreignId('dispensing_point_id')->nullable()->constrained('dispensing_points')->nullOnDelete();
            $table->decimal('quantity_on_hand', 12, 2)->default(0);
            $table->decimal('quantity_reserved', 12, 2)->default(0);
            $table->decimal('quantity_available', 12, 2)->default(0);
            $table->decimal('quantity_in_transit', 12, 2)->default(0);
            $table->decimal('reorder_level', 12, 2)->default(0);
            $table->decimal('reorder_quantity', 12, 2)->default(0);
            $table->date('last_received_date')->nullable();
            $table->date('last_dispensed_date')->nullable();
            $table->date('last_counted_date')->nullable();
            $table->enum('stock_status', ['In Stock', 'Low Stock', 'Out of Stock', 'Over Stock', 'Discontinued'])->default('In Stock');
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- Purchases & Receipts ----

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->date('received_date');
            $table->string('invoice_number')->nullable();
            $table->string('delivery_note_number')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Verified', 'Rejected'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
        });

        // ---- RE-ENABLE FK CHECKS ----
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
    }

    public function down(): void
    {
        // This migration creates everything — dropping is not supported
    }
};
