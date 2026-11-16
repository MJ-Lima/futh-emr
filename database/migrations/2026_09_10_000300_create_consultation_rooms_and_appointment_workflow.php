<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Consultation Rooms — physical rooms per clinic
        // ---------------------------------------------------------------
        if (!Schema::hasTable('consultation_rooms')) {
            Schema::create('consultation_rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
                $table->string('name');
                $table->string('code', 30);
                $table->integer('capacity')->default(1)->comment('How many patients can be in the room at once');
                $table->foreignId('assigned_doctor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->unique(['clinic_id', 'code']);
                $table->index(['clinic_id', 'is_active']);
            });
        }

        // ---------------------------------------------------------------
        // Add workflow fields to appointments
        // ---------------------------------------------------------------
        if (!Schema::hasColumn('appointments', 'card_fee_paid')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->boolean('card_fee_paid')->default(false)->after('status');
                $table->decimal('card_fee_amount', 12, 2)->default(0)->after('card_fee_paid');
                $table->string('card_fee_payment_method', 30)->nullable()->after('card_fee_amount');
                $table->string('card_fee_receipt_no', 100)->nullable()->after('card_fee_payment_method');
                $table->foreignId('card_fee_received_by')->nullable()->constrained('users')->nullOnDelete()->after('card_fee_receipt_no');
                $table->timestamp('card_fee_paid_at')->nullable()->after('card_fee_received_by');
            });
        }

        if (!Schema::hasColumn('appointments', 'vitals_taken_at')) {
            Schema::table('appointments', function (Blueprint $table) {
                // Nurse workflow
                $table->enum('vitals_status', ['Pending', 'In Progress', 'Completed', 'Skipped'])
                    ->default('Pending')->after('card_fee_paid_at');
                $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete()->after('vitals_status');
                $table->timestamp('vitals_taken_at')->nullable()->after('nurse_id');

                // Consultation room allocation (auto after vitals)
                $table->foreignId('consultation_room_id')->nullable()->constrained('consultation_rooms')->nullOnDelete()->after('vitals_taken_at');
                $table->integer('room_queue_position')->nullable()->after('consultation_room_id');
                $table->timestamp('room_allocated_at')->nullable()->after('room_queue_position');
            });
        }

        // Extend the appointments.status enum to include the new workflow states
        // (In Nurse Queue, Vitals Taken, Waiting for Doctor, In Consultation)
        // MySQL allows ALTER on enums directly.
        $existingStatusType = \DB::selectOne("SHOW COLUMNS FROM appointments WHERE Field = 'status'")->Type ?? '';
        if (str_contains($existingStatusType, 'In Nurse Queue') === false) {
            \DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM(
                'Scheduled',
                'Confirmed',
                'Checked In',
                'In Nurse Queue',
                'Vitals Taken',
                'Waiting for Doctor',
                'In Consultation',
                'Completed',
                'Cancelled',
                'No Show'
            ) NOT NULL DEFAULT 'Scheduled'");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('appointments', 'room_allocated_at')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn([
                    'room_allocated_at', 'room_queue_position', 'consultation_room_id',
                    'vitals_taken_at', 'nurse_id', 'vitals_status',
                    'card_fee_paid_at', 'card_fee_received_by', 'card_fee_receipt_no',
                    'card_fee_payment_method', 'card_fee_amount', 'card_fee_paid',
                ]);
            });
        }
        Schema::dropIfExists('consultation_rooms');
    }
};
