<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tables that need hospital_id added (child/detail tables that reference a parent with hospital_id)
        $tablesNeedingHospitalId = [
            'allergies',
            'beds',
            'bed_transfers',
            'diagnoses',
            'discharge_summaries',
            'dispensing_logs',
            'drug_batches',
            'drug_categories',
            'drug_interactions',
            'insurance_plans',
            'internal_audit_findings',
            'journal_lines',
            'lab_results',
            'lab_transfers',
            'medical_audit_findings',
            'patient_insurance',
            'procedure_sessions',
            'radiology_results',
            // Child/detail tables — these reference parents that have hospital_id
            // We add hospital_id as nullable so existing records aren't broken
            'encounter_presenting_complaints',
            'general_store_requisition_items',
            'goods_received_note_items',
            'insurance_claim_lines',
            'invoice_items',
            'lab_order_items',
            'prescription_items',
            'purchase_items',
            'receipt_items',
            'stock_take_items',
        ];

        foreach ($tablesNeedingHospitalId as $table) {
            if (!Schema::hasColumn($table, 'hospital_id')) {
                // Add hospital_id as nullable (no FK constraint to avoid issues with existing data)
                DB::statement("ALTER TABLE `{$table}` ADD COLUMN `hospital_id` BIGINT UNSIGNED NULL DEFAULT 1 AFTER `id`");
                
                // Add index for faster scoping queries
                DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$table}_hospital_id_index` (`hospital_id`)");
            }
        }
    }

    public function down(): void
    {
        $tablesNeedingHospitalId = [
            'allergies', 'beds', 'bed_transfers', 'diagnoses', 'discharge_summaries',
            'dispensing_logs', 'drug_batches', 'drug_categories', 'drug_interactions',
            'insurance_plans', 'internal_audit_findings', 'journal_lines',
            'lab_results', 'lab_transfers', 'medical_audit_findings', 'patient_insurance',
            'procedure_sessions', 'radiology_results',
            'encounter_presenting_complaints', 'general_store_requisition_items',
            'goods_received_note_items', 'insurance_claim_lines', 'invoice_items',
            'lab_order_items', 'prescription_items', 'purchase_items',
            'receipt_items', 'stock_take_items',
        ];

        foreach ($tablesNeedingHospitalId as $table) {
            if (Schema::hasColumn($table, 'hospital_id')) {
                DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$table}_hospital_id_index`");
                DB::statement("ALTER TABLE `{$table}` DROP COLUMN `hospital_id`");
            }
        }
    }
};
