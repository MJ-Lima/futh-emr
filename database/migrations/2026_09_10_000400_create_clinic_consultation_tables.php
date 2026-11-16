<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. Presenting Complaints — selectable from a database list
        // ---------------------------------------------------------------
        if (!Schema::hasTable('presenting_complaints')) {
            Schema::create('presenting_complaints', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category', 80)->default('General')->comment('e.g. Constitutional, Respiratory, Cardiovascular, GI, Neuro, Musculoskeletal, Dermatological, ENT, Eye, O&G, Psych');
                $table->string('code', 40)->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->index(['category', 'is_active']);
            });
        }

        // ---------------------------------------------------------------
        // 2. ICD-10 diagnoses master list (used in the encounter form's
        //    "New Diagnosis" select). The existing per-patient `diagnoses`
        //    table continues to record diagnoses on encounters.
        // ---------------------------------------------------------------
        if (!Schema::hasTable('icd10_diagnoses')) {
            Schema::create('icd10_diagnoses', function (Blueprint $table) {
                $table->id();
                $table->string('code', 10)->unique();
                $table->string('description');
                $table->string('category', 80)->nullable()->comment('ICD-10 chapter (e.g. Infectious, Neoplasms, Endocrine, Nervous, Respiratory, Digestive)');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->index(['category', 'is_active']);
                $table->index('code');
            });
        }

        // ---------------------------------------------------------------
        // 3. Encounter presenting complaints (many-to-many)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('encounter_presenting_complaints')) {
            Schema::create('encounter_presenting_complaints', function (Blueprint $table) {
                $table->id();
                $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete();
                $table->foreignId('presenting_complaint_id')->constrained('presenting_complaints')->cascadeOnDelete();
                $table->string('duration', 100)->nullable()->comment('e.g. 3 days, 2 weeks');
                $table->text('description')->nullable()->comment('HPI / details about this complaint');
                $table->timestamps();
                $table->index('encounter_id');
            });
        }

        // ---------------------------------------------------------------
        // 4. Clinic Templates — physical/general/specific history sections
        //    per clinic type (ANC, SOPD, Dental, ENT, Paediatric, Eye,
        //    Psychiatry, Psychology, etc.)
        // ---------------------------------------------------------------
        if (!Schema::hasTable('clinic_templates')) {
            Schema::create('clinic_templates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
                $table->string('clinic_type', 60)->comment('e.g. ANC, SOPD, Dental, ENT, Paediatric, Eye, Psychiatry, Psychology, General');
                $table->string('name');
                $table->string('section_type', 50)->comment('physical_history, general_history, consultation, specific_template');
                $table->text('fields_json')->nullable()->comment('JSON describing the form fields specific to this clinic');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                $table->index(['clinic_type', 'section_type', 'is_active']);
            });
        }

        // ---------------------------------------------------------------
        // 5. Consultation Notes — doctor's structured findings, linked to
        //    the encounter, presenting complaints, diagnoses, and clinic
        //    template.
        // ---------------------------------------------------------------
        if (!Schema::hasTable('consultation_notes')) {
            Schema::create('consultation_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('encounter_id')->constrained('encounters')->cascadeOnDelete();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->foreignId('clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
                $table->foreignId('clinic_template_id')->nullable()->constrained('clinic_templates')->nullOnDelete();
                $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
                $table->string('note_number')->unique();
                // — Physical Examination —
                $table->text('physical_examination_general')->nullable()->comment('General appearance, build, nutrition, hydration, etc.');
                $table->text('physical_examination_systemic')->nullable()->comment('Respiratory, CVS, Abdomen, CNS, MSS — systemic exam findings');
                $table->text('physical_examination_specific')->nullable()->comment('Clinic-specific examination, e.g. Pelvic exam, Dental exam, ENT exam, Eye exam, Mental state exam');
                // — General History —
                $table->text('history_presenting_illness')->nullable()->comment('HPI — narrative of the present illness');
                $table->text('history_past_medical')->nullable();
                $table->text('history_past_surgical')->nullable();
                $table->text('history_drug_allergies')->nullable();
                $table->text('history_family')->nullable();
                $table->text('history_social')->nullable()->comment('Smoking, alcohol, occupation, housing, etc.');
                $table->text('history_obstetric')->nullable()->comment('Gravida, Para, etc. — used in ANC/O&G');
                $table->text('history_developmental')->nullable()->comment('Milestones — used in Paediatric');
                // — Doctor's Clinic Note (findings) —
                $table->text('examination_findings')->nullable();
                $table->text('assessment')->nullable();
                $table->text('plan')->nullable();
                $table->text('doctor_notes')->nullable();
                // — Clinic-specific template data (JSON) —
                $table->json('clinic_specific_data')->nullable();
                $table->enum('status', ['Draft', 'Finalized', 'Counter-signed', 'Amended'])->default('Draft');
                $table->foreignId('countersigned_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('countersigned_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['encounter_id', 'created_at']);
                $table->index(['patient_id', 'created_at']);
            });
        }

        // ---------------------------------------------------------------
        // 6. Resident doctor admission notes (separate from the main
        //    `admissions` table — this stores the resident's structured
        //    admission note for inpatient encounters).
        // ---------------------------------------------------------------
        if (!Schema::hasTable('resident_admission_notes')) {
            Schema::create('resident_admission_notes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('admission_id')->nullable()->constrained('admissions')->cascadeOnDelete();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->foreignId('resident_doctor_id')->constrained('users')->cascadeOnDelete()->comment('The resident doctor who wrote this note');
                $table->foreignId('supervising_consultant_id')->nullable()->constrained('users')->nullOnDelete()->comment('The consultant who counter-signs the note');
                $table->string('note_number')->unique();
                // — Admission note fields —
                $table->text('presenting_complaint')->nullable();
                $table->text('history_presenting_illness')->nullable();
                $table->text('past_medical_history')->nullable();
                $table->text('drug_history')->nullable();
                $table->text('family_history')->nullable();
                $table->text('social_history')->nullable();
                $table->text('physical_examination')->nullable();
                $table->text('systemic_review')->nullable();
                $table->text('provisional_diagnosis')->nullable();
                $table->text('differential_diagnosis')->nullable();
                $table->text('investigations_ordered')->nullable();
                $table->text('management_plan')->nullable();
                $table->text('resident_notes')->nullable();
                $table->text('consultant_feedback')->nullable();
                $table->enum('status', ['Draft', 'Submitted', 'Counter-signed', 'Amended'])->default('Draft');
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('countersigned_at')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['patient_id', 'created_at']);
                $table->index(['resident_doctor_id', 'created_at']);
            });
        }

        // ---------------------------------------------------------------
        // 7. Extend `admissions` with discharge_reason + resident fields
        // ---------------------------------------------------------------
        if (!Schema::hasColumn('admissions', 'discharge_reason')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->text('discharge_reason')->nullable()->after('discharge_date');
                $table->text('discharge_diagnosis')->nullable()->after('discharge_reason');
                $table->text('discharge_summary')->nullable()->after('discharge_diagnosis');
                $table->foreignId('resident_doctor_id')->nullable()->constrained('users')->nullOnDelete()->after('attending_doctor_id');
                $table->foreignId('discharged_by')->nullable()->constrained('users')->nullOnDelete()->after('resident_doctor_id');
            });
        }

        // ---------------------------------------------------------------
        // 8. Extend `staff` with `staff_type` enum values for Resident / Intern
        // ---------------------------------------------------------------
        $staffTypeCol = \DB::selectOne("SHOW COLUMNS FROM staff WHERE Field = 'staff_type'")->Type ?? '';
        if (str_contains($staffTypeCol, 'Resident Doctor') === false) {
            \DB::statement("ALTER TABLE staff MODIFY COLUMN staff_type ENUM(
                'Doctor', 'Resident Doctor', 'Intern', 'Medical Officer', 'Nurse',
                'Pharmacist', 'Laboratory Scientist', 'Radiographer',
                'Physiotherapist', 'Administrator', 'Accountant', 'ICT',
                'Medical Records', 'Scientific Officer', 'Other'
            ) NOT NULL");
        }

        // ---------------------------------------------------------------
        // 9. Extend `clinics` with `clinic_type` column (so we can route
        //    templates by type — useful for FUTH Annex / Taimako satellite
        //    clinics that share the same template as the main one).
        // ---------------------------------------------------------------
        if (!Schema::hasColumn('clinics', 'clinic_type')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->string('clinic_type', 60)->default('General')->after('code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('clinics', 'clinic_type')) {
            Schema::table('clinics', function (Blueprint $table) {
                $table->dropColumn('clinic_type');
            });
        }
        // Revert staff_type to original enum
        \DB::statement("ALTER TABLE staff MODIFY COLUMN staff_type ENUM('Doctor','Nurse','Pharmacist','Laboratory Scientist','Radiographer','Physiotherapist','Administrator','Accountant','ICT','Medical Records','Other') NOT NULL");
        if (Schema::hasColumn('admissions', 'discharge_reason')) {
            Schema::table('admissions', function (Blueprint $table) {
                $table->dropColumn(['discharged_by', 'resident_doctor_id', 'discharge_summary', 'discharge_diagnosis', 'discharge_reason']);
            });
        }
        Schema::dropIfExists('resident_admission_notes');
        Schema::dropIfExists('consultation_notes');
        Schema::dropIfExists('clinic_templates');
        Schema::dropIfExists('encounter_presenting_complaints');
        Schema::dropIfExists('icd10_diagnoses');
        Schema::dropIfExists('presenting_complaints');
    }
};
