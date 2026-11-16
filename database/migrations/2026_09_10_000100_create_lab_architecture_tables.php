<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Laboratories — the 4-lab architecture
        // DNA Lab (outpatient) / Main Lab (inpatient, 7 units) /
        // FUTH Annex Lab / FUTH Taimako subsidiary Lab
        if (!Schema::hasTable('laboratories')) {
            Schema::create('laboratories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hospital_id')->constrained('hospitals')->cascadeOnDelete();
                $table->string('name');
                $table->string('code')->unique();
                $table->enum('lab_type', ['dna', 'main', 'subsidiary'])->default('subsidiary');
                $table->string('address')->nullable();
                $table->string('phone')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Lab units — Main Lab has 7 (Haematology, Chem. Path, Microbiology,
        // Parasitology, Serology, Histopathology, Blood Bank).
        // Other labs have a single 'General' unit.
        if (!Schema::hasTable('lab_units')) {
            Schema::create('lab_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('laboratory_id')->constrained('laboratories')->cascadeOnDelete();
                $table->string('name');
                $table->string('code', 30);
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->unique(['laboratory_id', 'code']);
            });
        }

        // Lab transfers — full audit trail of order being moved between labs
        if (!Schema::hasTable('lab_transfers')) {
            Schema::create('lab_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lab_order_id')->constrained('lab_orders')->cascadeOnDelete();
                $table->foreignId('from_laboratory_id')->nullable()->constrained('laboratories')->nullOnDelete();
                $table->foreignId('to_laboratory_id')->constrained('laboratories')->cascadeOnDelete();
                $table->foreignId('transferred_by')->constrained('users')->cascadeOnDelete();
                $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('reason');
                $table->enum('status', ['Pending', 'Accepted', 'Rejected', 'Cancelled'])->default('Pending');
                $table->timestamp('transferred_at')->useCurrent();
                $table->timestamp('received_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Add destination_lab_id to lab_orders (which lab will process this order)
        if (!Schema::hasColumn('lab_orders', 'destination_lab_id')) {
            Schema::table('lab_orders', function (Blueprint $table) {
                $table->foreignId('destination_lab_id')
                    ->nullable()
                    ->after('hospital_id')
                    ->constrained('laboratories')
                    ->nullOnDelete();
            });
        }

        // Add lab_unit_id to lab_panels and lab_tests so each test is routed to a unit
        if (!Schema::hasColumn('lab_panels', 'lab_unit_id')) {
            Schema::table('lab_panels', function (Blueprint $table) {
                $table->foreignId('lab_unit_id')
                    ->nullable()
                    ->after('hospital_id')
                    ->constrained('lab_units')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('lab_tests', 'lab_unit_id')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->foreignId('lab_unit_id')
                    ->nullable()
                    ->after('hospital_id')
                    ->constrained('lab_units')
                    ->nullOnDelete();
            });
        }

        // Add lab_unit_id to lab_order_items
        if (!Schema::hasColumn('lab_order_items', 'lab_unit_id')) {
            Schema::table('lab_order_items', function (Blueprint $table) {
                $table->foreignId('lab_unit_id')
                    ->nullable()
                    ->after('lab_test_id')
                    ->constrained('lab_units')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lab_order_items', 'lab_unit_id')) {
            Schema::table('lab_order_items', function (Blueprint $table) {
                $table->dropForeign(['lab_unit_id']);
                $table->dropColumn('lab_unit_id');
            });
        }
        if (Schema::hasColumn('lab_tests', 'lab_unit_id')) {
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->dropForeign(['lab_unit_id']);
                $table->dropColumn('lab_unit_id');
            });
        }
        if (Schema::hasColumn('lab_panels', 'lab_unit_id')) {
            Schema::table('lab_panels', function (Blueprint $table) {
                $table->dropForeign(['lab_unit_id']);
                $table->dropColumn('lab_unit_id');
            });
        }
        if (Schema::hasColumn('lab_orders', 'destination_lab_id')) {
            Schema::table('lab_orders', function (Blueprint $table) {
                $table->dropForeign(['destination_lab_id']);
                $table->dropColumn('destination_lab_id');
            });
        }
        Schema::dropIfExists('lab_transfers');
        Schema::dropIfExists('lab_units');
        Schema::dropIfExists('laboratories');
    }
};
