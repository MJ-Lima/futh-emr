<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends the hospitals table with public-facing directory fields and creates
 * the supporting tables for the public Patient Portal and Provider Registration.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Extend the hospitals table with public directory fields
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('type')->default('General')->after('code')->comment('General, Teaching, Specialist, Clinic, Maternity, Primary');
            $table->string('tagline')->nullable()->after('type');
            $table->text('description')->nullable()->after('tagline');
            $table->string('address_line1')->nullable()->after('description');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city')->nullable()->after('address_line2');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->default('Nigeria')->after('state');
            $table->string('postal_code', 20)->nullable()->after('country');
            $table->string('phone_main', 30)->nullable()->after('postal_code');
            $table->string('phone_alt', 30)->nullable()->after('phone_main');
            $table->string('email')->nullable()->after('phone_alt');
            $table->string('website')->nullable()->after('email');
            $table->string('logo_path')->nullable()->after('website');
            $table->string('cover_path')->nullable()->after('logo_path');
            $table->decimal('latitude', 10, 7)->nullable()->after('cover_path');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->json('services')->nullable()->after('longitude')->comment('Array of services offered');
            $table->json('operating_hours')->nullable()->after('services')->comment('Weekly opening hours JSON');
            $table->boolean('is_public')->default(true)->after('operating_hours')->comment('Show in public directory');
            $table->boolean('accepts_online_registration')->default(true)->after('is_public');
        });

        // 2) Patient self-service portal accounts (separate from staff users)
        Schema::create('patient_portal_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 30)->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'locked'])->default('pending');
            $table->string('verification_token', 100)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken(100);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3) Provider (doctor / hospital) registration applications
        Schema::create('provider_applications', function (Blueprint $table) {
            $table->id();
            $table->enum('account_type', ['doctor', 'hospital', 'clinic'])->default('doctor');
            $table->string('application_number')->unique();

            // Applicant identity
            $table->string('applicant_name');
            $table->string('email')->unique();
            $table->string('phone', 30);
            $table->string('password'); // hashed - used when account is approved
            $table->string('title', 30)->nullable()->comment('Dr, Prof, Mr, Mrs, etc.');

            // For doctors
            $table->string('professional_registration_number')->nullable()->comment('MDCN, NMC, etc.');
            $table->string('specialty')->nullable();
            $table->string('qualification')->nullable();
            $table->integer('years_of_experience')->nullable();

            // For hospitals / clinics
            $table->string('facility_name')->nullable();
            $table->string('facility_type')->nullable()->comment('Teaching, General, Specialist, Clinic, Maternity, Primary');
            $table->string('facility_license_number')->nullable();
            $table->text('facility_address')->nullable();
            $table->string('facility_city')->nullable();
            $table->string('facility_state')->nullable();
            $table->string('facility_country')->default('Nigeria');

            // Common
            $table->text('bio')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('website')->nullable();
            $table->json('services_offered')->nullable();
            $table->text('notes')->nullable();

            // Workflow
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'withdrawn'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('User created on approval');
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete()->comment('Hospital record created on approval');

            $table->timestamps();
            $table->softDeletes();
        });

        // 4) Patient-to-hospital messaging (secure two-way)
        Schema::create('patient_portal_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_portal_user_id')->constrained('patient_portal_users')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
            $table->foreignId('staff_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Staff who replied');
            $table->enum('direction', ['inbound', 'outbound'])->default('inbound');
            $table->enum('status', ['sent', 'delivered', 'read', 'replied'])->default('sent');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('attachments')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5) Password reset tokens for patient portal users
        Schema::create('patient_portal_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_portal_password_reset_tokens');
        Schema::dropIfExists('patient_portal_messages');
        Schema::dropIfExists('provider_applications');
        Schema::dropIfExists('patient_portal_users');

        Schema::table('hospitals', function (Blueprint $table) {
            $columns = [
                'type', 'tagline', 'description', 'address_line1', 'address_line2',
                'city', 'state', 'country', 'postal_code', 'phone_main', 'phone_alt',
                'email', 'website', 'logo_path', 'cover_path', 'latitude', 'longitude',
                'services', 'operating_hours', 'is_public', 'accepts_online_registration',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('hospitals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
