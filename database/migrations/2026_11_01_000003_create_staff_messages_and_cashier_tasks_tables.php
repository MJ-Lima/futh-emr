<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the supporting tables for the v2.2 enhancement pack:
 *   - staff_messages      : staff-to-staff internal messaging
 *   - cashier_tasks       : auto-generated cashier work items when any
 *                           billable service is created (invoice, lab
 *                           order, radiology order, prescription, card fee)
 *
 * These complement (do not replace) the existing patient_portal_messages
 * table from the public-portal migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('staff_messages')) {
            Schema::create('staff_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('recipient_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('parent_id')->nullable()->constrained('staff_messages')->cascadeOnDelete();
                $table->string('subject')->nullable();
                $table->longText('body');
                $table->enum('priority', ['Normal', 'Important', 'Urgent'])->default('Normal');
                $table->enum('status', ['sent', 'delivered', 'read', 'archived'])->default('sent');
                $table->timestamp('read_at')->nullable();
                $table->timestamp('archived_at')->nullable();
                $table->json('attachments')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['recipient_id', 'read_at']);
                $table->index(['sender_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('cashier_tasks')) {
            Schema::create('cashier_tasks', function (Blueprint $table) {
                $table->id();
                $table->string('task_number')->unique();
                $table->foreignId('hospital_id')->nullable()->constrained('hospitals')->nullOnDelete();
                $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
                $table->foreignId('encounter_id')->nullable()->constrained('encounters')->nullOnDelete();
                $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();

                // Source — what triggered this cashier task
                $table->string('source_type')->comment('Invoice, LabOrder, RadiologyOrder, Prescription, AppointmentCardFee, Manual');
                $table->foreignId('source_id')->nullable();

                // What is being billed
                $table->string('description');
                $table->decimal('amount_due', 14, 2)->default(0);
                $table->decimal('amount_paid', 14, 2)->default(0);

                // Cashier workflow
                $table->enum('status', ['pending', 'in_progress', 'paid', 'cancelled', 'refunded', 'short_paid'])
                      ->default('pending');
                $table->foreignId('assigned_cashier_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('assigned_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('receipt_number')->nullable();
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
                $table->foreignId('patient_receipt_id')->nullable()->constrained('patient_receipts')->nullOnDelete();
                $table->text('notes')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['hospital_id', 'status']);
                $table->index(['patient_id', 'status']);
                $table->index(['source_type', 'source_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_tasks');
        Schema::dropIfExists('staff_messages');
    }
};
