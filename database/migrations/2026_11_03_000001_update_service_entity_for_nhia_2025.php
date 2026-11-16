<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * v2.3 — Service entity update for the NHIA Procedure Price List 2025
 * (used for BOTH NHIS / federal NHIA and NASHIA / Nasarawa State Health
 * Insurance Agency tariffs) plus the special non-clinical services
 * (internship, research & data collection, gas plant sales, application
 * forms, ID cards).
 *
 * Changes:
 *   services:
 *     + nashia_rate        — NASHIA (Nasarawa State) tariff per service,
 *                            parallel to the existing nhia_rate column so
 *                            each scheme can be priced/edited separately.
 *     + allowed_roles      — JSON list of staff roles permitted to add
 *                            (bill) this service. NULL/empty = any staff
 *                            member with billing access may bill it.
 *     + allows_walk_in     — whether the service may be billed to a
 *                            walk-in (non-patient) customer, e.g. gas
 *                            plant oxygen sold to buyers from outside.
 *     + INDEX(nhia_code)   — fast tariff lookups by NHIA code.
 *
 *   invoices:
 *     + patient_id -> NULL — walk-in customers (gas plant, interns,
 *                            students, card applicants) are not patients.
 *     + customer_name      — walk-in customer name.
 *     + customer_phone     — walk-in customer phone.
 *     + payment_mode gains 'NASHIA' (state scheme) alongside 'NHIA'.
 */
return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------------
        // services
        // -------------------------------------------------------------
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'nashia_rate')) {
                $table->decimal('nashia_rate', 14, 2)->default(0)->after('nhia_rate');
            }
            if (! Schema::hasColumn('services', 'allowed_roles')) {
                $table->json('allowed_roles')->nullable()->after('requires_pre_authorization');
            }
            if (! Schema::hasColumn('services', 'allows_walk_in')) {
                $table->boolean('allows_walk_in')->default(false)->after('allowed_roles');
            }
        });

        if (! $this->hasIndex('services', 'services_nhia_code_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('nhia_code', 'services_nhia_code_index');
            });
        }

        // -------------------------------------------------------------
        // invoices — walk-in customer support
        // -------------------------------------------------------------
        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('patient_insurance_id');
            }
            if (! Schema::hasColumn('invoices', 'customer_phone')) {
                $table->string('customer_phone', 30)->nullable()->after('customer_name');
            }
        });

        // patient_id nullable: gas-plant buyers, interns, students and card
        // applicants are walk-in customers, not registered patients. The
        // existing FK (cascadeOnDelete) is preserved by MODIFY.
        DB::statement('ALTER TABLE `invoices` MODIFY `patient_id` BIGINT UNSIGNED NULL');

        // payment_mode: add 'NASHIA' so state-scheme invoices are
        // distinguishable from federal NHIA ones. Existing rows untouched.
        DB::statement(
            "ALTER TABLE `invoices` MODIFY `payment_mode` ENUM("
            . "'Cash','Insurance','NHIA','NASHIA','Corporate','Charity','Mixed'"
            . ") NOT NULL DEFAULT 'Cash'"
        );
    }

    public function down(): void
    {
        // Restore walk-in rows to a Cash invoice on a real patient is not
        // possible automatically — guard the downgrade.
        $walkIns = DB::table('invoices')->whereNull('patient_id')->count();
        if ($walkIns > 0) {
            throw new RuntimeException(
                "Cannot downgrade: {$walkIns} walk-in invoice(s) exist with no patient. "
                . "Re-assign or delete them first."
            );
        }

        DB::statement(
            "ALTER TABLE `invoices` MODIFY `payment_mode` ENUM("
            . "'Cash','Insurance','NHIA','Corporate','Charity','Mixed'"
            . ") NOT NULL DEFAULT 'Cash'"
        );
        DB::statement('ALTER TABLE `invoices` MODIFY `patient_id` BIGINT UNSIGNED NOT NULL');

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'customer_phone')) {
                $table->dropColumn('customer_phone');
            }
            if (Schema::hasColumn('invoices', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
        });

        if ($this->hasIndex('services', 'services_nhia_code_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropIndex('services_nhia_code_index');
            });
        }

        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'allows_walk_in')) {
                $table->dropColumn('allows_walk_in');
            }
            if (Schema::hasColumn('services', 'allowed_roles')) {
                $table->dropColumn('allowed_roles');
            }
            if (Schema::hasColumn('services', 'nashia_rate')) {
                $table->dropColumn('nashia_rate');
            }
        });
    }

    protected function hasIndex(string $table, string $indexName): bool
    {
        $exists = DB::select(
            'SELECT COUNT(*) AS `count` FROM information_schema.STATISTICS '
            . "WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?",
            [$table, $indexName]
        );

        return ((int) ($exists[0]->count ?? 0)) > 0;
    }
};
