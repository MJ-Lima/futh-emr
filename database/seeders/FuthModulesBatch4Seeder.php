<?php

namespace Database\Seeders;

use App\Models\{AuditCategory, InternalAudit, MedicalAudit, PatientRelationship, RelationshipType, ReportTemplate, User};
use Illuminate\Database\Seeder;

class FuthModulesBatch4Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding FUTH Lafia EMR modules batch 4 (Audit, Reports, Relationships)...');

        $hospitalId = 1;
        $admin = User::first();

        // Audit Categories
        $auditCats = [
            ['name' => 'Clinical Care Audit', 'code' => 'CCA', 'type' => 'Clinical'],
            ['name' => 'Mortality & Morbidity Review', 'code' => 'MMR', 'type' => 'Medical'],
            ['name' => 'Medication Safety Audit', 'code' => 'MSA', 'type' => 'Pharmacy'],
            ['name' => 'Infection Control Audit', 'code' => 'ICA', 'type' => 'Medical'],
            ['name' => 'Financial Audit', 'code' => 'FIN', 'type' => 'Financial'],
            ['name' => 'Operational Audit', 'code' => 'OPS', 'type' => 'Operational'],
            ['name' => 'Compliance Audit', 'code' => 'CMP', 'type' => 'Compliance'],
            ['name' => 'IT Security Audit', 'code' => 'ITS', 'type' => 'IT'],
            ['name' => 'HR Audit', 'code' => 'HRA', 'type' => 'Operational'],
            ['name' => 'Quality Assurance Audit', 'code' => 'QAS', 'type' => 'Quality'],
        ];
        foreach ($auditCats as $c) {
            AuditCategory::firstOrCreate(
                ['code' => $c['code']],
                array_merge($c, ['hospital_id' => $hospitalId, 'is_active' => true])
            );
        }

        // Relationship Types
        $relTypes = [
            ['name' => 'Spouse', 'code' => 'SPO', 'category' => 'Family', 'is_symmetric' => true],
            ['name' => 'Parent', 'code' => 'PAR', 'category' => 'Family'],
            ['name' => 'Child', 'code' => 'CHD', 'category' => 'Family'],
            ['name' => 'Sibling', 'code' => 'SIB', 'category' => 'Family', 'is_symmetric' => true],
            ['name' => 'Next of Kin', 'code' => 'NOK', 'category' => 'Family'],
            ['name' => 'Emergency Contact', 'code' => 'EMC', 'category' => 'Emergency'],
            ['name' => 'Referring Doctor', 'code' => 'RDR', 'category' => 'Medical'],
            ['name' => 'Insurance Beneficiary', 'code' => 'IBN', 'category' => 'Insurance'],
            ['name' => 'Insurance Principal', 'code' => 'IPL', 'category' => 'Insurance'],
            ['name' => 'Legal Guardian', 'code' => 'LGD', 'category' => 'Legal'],
            ['name' => 'Power of Attorney', 'code' => 'POA', 'category' => 'Legal'],
            ['name' => 'Caregiver', 'code' => 'CGV', 'category' => 'Family'],
        ];
        foreach ($relTypes as $r) {
            RelationshipType::firstOrCreate(
                ['code' => $r['code']],
                array_merge($r, ['is_active' => true])
            );
        }

        // Report Templates
        $reportTemplates = [
            ['name' => 'Patient Registration Report', 'code' => 'PAT-REG', 'category' => 'Patient', 'frequency' => 'Daily'],
            ['name' => 'Encounter Statistics Report', 'code' => 'ENC-STAT', 'category' => 'Clinical', 'frequency' => 'Daily'],
            ['name' => 'Revenue Collection Report', 'code' => 'REV-COL', 'category' => 'Financial', 'frequency' => 'Daily'],
            ['name' => 'Outstanding Invoices Report', 'code' => 'INV-OUT', 'category' => 'Financial', 'frequency' => 'Weekly'],
            ['name' => 'Pharmacy Dispensing Report', 'code' => 'PHM-DSP', 'category' => 'Pharmacy', 'frequency' => 'Daily'],
            ['name' => 'Low Stock Items Report', 'code' => 'STK-LOW', 'category' => 'Inventory', 'frequency' => 'Weekly'],
            ['name' => 'Lab Orders Summary', 'code' => 'LAB-SUM', 'category' => 'Laboratory', 'frequency' => 'Daily'],
            ['name' => 'Radiology Orders Summary', 'code' => 'RAD-SUM', 'category' => 'Radiology', 'frequency' => 'Daily'],
            ['name' => 'Insurance Claims Status', 'code' => 'INS-CLM', 'category' => 'Insurance', 'frequency' => 'Weekly'],
            ['name' => 'Bed Occupancy Report', 'code' => 'BED-OCC', 'category' => 'Operational', 'frequency' => 'Daily'],
            ['name' => 'Blood Bank Inventory', 'code' => 'BLD-INV', 'category' => 'Laboratory', 'frequency' => 'Daily'],
            ['name' => 'Mortality & Morbidity Report', 'code' => 'M&M-RPT', 'category' => 'Mortality', 'frequency' => 'Monthly'],
            ['name' => 'Infection Control Report', 'code' => 'INF-CTL', 'category' => 'Infection', 'frequency' => 'Monthly'],
            ['name' => 'Staff Attendance Report', 'code' => 'STF-ATT', 'category' => 'HR', 'frequency' => 'Monthly'],
            ['name' => 'Departmental Performance Report', 'code' => 'DEPT-PER', 'category' => 'Operational', 'frequency' => 'Monthly'],
            ['name' => 'Audit Findings Report', 'code' => 'AUD-FND', 'category' => 'Audit', 'frequency' => 'Quarterly'],
            ['name' => 'Budget vs Actual Report', 'code' => 'BUD-ACT', 'category' => 'Financial', 'frequency' => 'Monthly'],
            ['name' => 'Patient Satisfaction Report', 'code' => 'PAT-SAT', 'category' => 'Quality', 'frequency' => 'Quarterly'],
        ];
        foreach ($reportTemplates as $t) {
            ReportTemplate::firstOrCreate(
                ['code' => $t['code']],
                array_merge($t, [
                    'hospital_id' => $hospitalId,
                    'is_scheduled' => in_array($t['frequency'], ['Daily','Weekly','Monthly','Quarterly']),
                    'is_active' => true,
                    'created_by' => $admin->id ?? 1,
                ])
            );
        }

        // Sample Medical Audit
        $medicalAuditCat = AuditCategory::where('code', 'MMR')->first();
        MedicalAudit::firstOrCreate(
            ['audit_number' => 'MAU-' . now()->year . '-000001'],
            [
                'hospital_id' => $hospitalId,
                'audit_category_id' => $medicalAuditCat?->id,
                'auditor_id' => $admin->id ?? 1,
                'audit_type' => 'Mortality Review',
                'audit_date' => now()->subDays(7),
                'period_from' => now()->subMonth(),
                'period_to' => now()->subDays(7),
                'audit_scope' => 'Review of all mortality cases in the past month',
                'objectives' => 'Identify preventable causes of mortality and recommend improvements',
                'summary' => 'Reviewed 5 mortality cases. Majority were emergency presentations with delayed presentation.',
                'key_findings' => 'Late presentation was a common factor. Need for community sensitization.',
                'recommendations' => 'Strengthen emergency response system and community engagement.',
                'risk_level' => 'Moderate',
                'status' => 'Completed',
                'created_by' => $admin->id ?? 1,
            ]
        );

        // Sample Internal Audit
        $finCat = AuditCategory::where('code', 'FIN')->first();
        InternalAudit::firstOrCreate(
            ['audit_number' => 'IAU-' . now()->year . '-000001'],
            [
                'hospital_id' => $hospitalId,
                'audit_category_id' => $finCat?->id,
                'auditor_id' => $admin->id ?? 1,
                'audit_type' => 'Financial',
                'audit_date' => now()->subDays(14),
                'period_from' => now()->subQuarter(),
                'period_to' => now()->subDays(14),
                'audit_scope' => 'Quarterly financial audit of revenue collection and expenditure',
                'summary' => 'Revenue collection improved by 12% compared to last quarter. Some gaps in invoice reconciliation.',
                'key_findings' => '5 un reconciled invoices identified. Petty cash records need improvement.',
                'recommendations' => 'Implement daily reconciliation. Train petty cash holders on proper documentation.',
                'risk_level' => 'Low',
                'status' => 'Final Report',
                'financial_impact' => 125000,
                'created_by' => $admin->id ?? 1,
            ]
        );

        $this->command->info('Modules batch 4 seeded:');
        $this->command->info('- Audit: ' . AuditCategory::count() . ' categories, ' . MedicalAudit::count() . ' medical audits, ' . InternalAudit::count() . ' internal audits');
        $this->command->info('- Relationships: ' . RelationshipType::count() . ' relationship types');
        $this->command->info('- Reports: ' . ReportTemplate::count() . ' report templates');
    }
}
