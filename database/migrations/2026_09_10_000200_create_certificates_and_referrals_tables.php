<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Medical Certificates — covers both inpatient and outpatient
        // ---------------------------------------------------------------
        if (!Schema::hasTable('medical_certificates')) {
            Schema::create('medical_certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
                $table->foreignId('issued_by')->constrained('users')->cascadeOnDelete();
                $table->string('certificate_number')->unique();
                $table->enum('certificate_type', [
                    'Sick Leave',
                    'Fitness to Work',
                    'Medical Examination',
                    'Death Certificate',
                    'Birth Notification',
                    'Discharge Summary',
                    'Medical Report',
                    'Fitness to Travel',
                    'Fitness for Surgery',
                    'Custom',
                ])->default('Medical Report');
                $table->enum('patient_category', ['Outpatient', 'Inpatient', 'Emergency'])->default('Outpatient');
                $table->date('issued_date');
                $table->date('valid_from')->nullable();
                $table->date('valid_until')->nullable();
                $table->text('diagnosis')->nullable();
                $table->text('clinical_findings')->nullable();
                $table->text('treatment_given')->nullable();
                $table->text('recommendations')->nullable();
                $table->text('restrictions')->nullable();
                $table->integer('rest_days')->nullable()->comment('Sick leave days, if applicable');
                $table->string('issued_at_location')->nullable();
                $table->enum('status', ['Draft', 'Issued', 'Printed', 'Cancelled', 'Revoked'])->default('Draft');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['patient_id', 'issued_date']);
                $table->index('certificate_type');
            });
        }

        // ---------------------------------------------------------------
        // Referrals — both inpatient and outpatient, internal & external
        // ---------------------------------------------------------------
        if (!Schema::hasTable('referrals')) {
            Schema::create('referrals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->foreignId('admission_id')->nullable()->constrained('admissions')->nullOnDelete();
                $table->foreignId('referred_by')->constrained('users')->cascadeOnDelete();
                $table->foreignId('referred_to_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('referral_number')->unique();
                $table->enum('referral_type', [
                    'Internal',      // within FUTH Lafia
                    'External',      // to another hospital
                    'Inter-Hospital', // between FUTH main, Annex, Taimako
                    'Specialist',    // to a specialist clinic
                    'Emergency',
                ])->default('Internal');
                $table->enum('patient_category', ['Outpatient', 'Inpatient', 'Emergency'])->default('Outpatient');
                $table->string('destination_facility')->nullable()->comment('External hospital/clinic name');
                $table->string('destination_department')->nullable();
                $table->string('destination_doctor')->nullable();
                $table->text('clinical_summary');
                $table->text('diagnosis');
                $table->text('treatment_given')->nullable();
                $table->text('reason_for_referral');
                $table->text('investigations_done')->nullable();
                $table->text('investigations_requested')->nullable();
                $table->enum('urgency', ['Routine', 'Urgent', 'STAT', 'ASAP'])->default('Routine');
                $table->enum('status', [
                    'Pending',
                    'Accepted',
                    'Rejected',
                    'Completed',
                    'Cancelled',
                ])->default('Pending');
                $table->date('referred_date');
                $table->date('appointment_date')->nullable();
                $table->text('receiving_feedback')->nullable()->comment('Feedback from receiving facility');
                $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('received_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['patient_id', 'referred_date']);
                $table->index('referral_type');
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('medical_certificates');
    }
};
