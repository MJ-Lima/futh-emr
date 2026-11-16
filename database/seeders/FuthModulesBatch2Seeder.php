<?php

namespace Database\Seeders;

use App\Models\{Admission, AppointmentSlot, AppointmentType, Bed, Budget, ChartOfAccount, ClinicSchedule, Department, Holiday, LeaveRequest, Patient, ProviderSchedule, User, Ward};
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class FuthModulesBatch2Seeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding FUTH Lafia EMR modules batch 2...');

        $hospitalId = 1;
        $departments = Department::all();

        // ---- 1. Wards ----
        $wards = [
            ['name' => 'Male Medical Ward', 'code' => 'MMW', 'type' => 'Male Medical', 'gender_restriction' => 'Male Only', 'total_beds' => 20, 'floor' => 'Ground', 'wing' => 'East', 'daily_room_rate' => 5000, 'department_id' => 1],
            ['name' => 'Female Medical Ward', 'code' => 'FMW', 'type' => 'Female Medical', 'gender_restriction' => 'Female Only', 'total_beds' => 20, 'floor' => 'Ground', 'wing' => 'West', 'daily_room_rate' => 5000, 'department_id' => 1],
            ['name' => 'Male Surgical Ward', 'code' => 'MSW', 'type' => 'Male Surgical', 'gender_restriction' => 'Male Only', 'total_beds' => 15, 'floor' => 'First', 'wing' => 'East', 'daily_room_rate' => 7000, 'department_id' => 2],
            ['name' => 'Female Surgical Ward', 'code' => 'FSW', 'type' => 'Female Surgical', 'gender_restriction' => 'Female Only', 'total_beds' => 15, 'floor' => 'First', 'wing' => 'West', 'daily_room_rate' => 7000, 'department_id' => 2],
            ['name' => 'Paediatrics Ward', 'code' => 'PAED-W', 'type' => 'Paediatrics', 'gender_restriction' => 'Paediatrics Only', 'total_beds' => 18, 'floor' => 'Second', 'wing' => 'East', 'daily_room_rate' => 6000, 'department_id' => 3],
            ['name' => 'Maternity Ward', 'code' => 'MAT-W', 'type' => 'Maternity', 'gender_restriction' => 'Female Only', 'total_beds' => 22, 'floor' => 'Second', 'wing' => 'West', 'daily_room_rate' => 8000, 'department_id' => 4],
            ['name' => 'Intensive Care Unit (ICU)', 'code' => 'ICU', 'type' => 'ICU', 'gender_restriction' => 'Any', 'total_beds' => 6, 'floor' => 'Ground', 'wing' => 'Central', 'daily_room_rate' => 50000, 'department_id' => 5],
            ['name' => 'High Dependency Unit (HDU)', 'code' => 'HDU', 'type' => 'HDU', 'gender_restriction' => 'Any', 'total_beds' => 8, 'floor' => 'Ground', 'wing' => 'Central', 'daily_room_rate' => 25000, 'department_id' => 5],
            ['name' => 'Private Ward A', 'code' => 'PRIV-A', 'type' => 'Private', 'gender_restriction' => 'Any', 'total_beds' => 10, 'floor' => 'Third', 'wing' => 'East', 'daily_room_rate' => 20000, 'department_id' => 1],
            ['name' => 'Emergency Observation Ward', 'code' => 'ER-OBS', 'type' => 'Emergency', 'gender_restriction' => 'Any', 'total_beds' => 12, 'floor' => 'Ground', 'wing' => 'Central', 'daily_room_rate' => 3000, 'department_id' => 5],
        ];

        $wardIds = [];
        foreach ($wards as $w) {
            $ward = Ward::firstOrCreate(
                ['code' => $w['code']],
                array_merge($w, [
                    'hospital_id' => $hospitalId,
                    'status' => 'Active',
                ])
            );
            $wardIds[$w['code']] = $ward->id;
        }

        // ---- 2. Beds (5 per ward) ----
        $bedClasses = ['General', 'Private', 'Semi-Private', 'VIP'];
        foreach (Ward::all() as $ward) {
            for ($i = 1; $i <= min(5, $ward->total_beds); $i++) {
                $bedClass = $ward->type === 'Private' ? 'Private' : ($i <= 2 ? 'General' : ($i === 3 ? 'Semi-Private' : 'Private'));
                $rate = $ward->daily_room_rate + ($bedClass === 'VIP' ? 30000 : ($bedClass === 'Private' ? 10000 : 0));
                Bed::firstOrCreate(
                    ['bed_number' => $ward->code . '-' . str_pad($i, 2, '0', STR_PAD_LEFT)],
                    [
                        'ward_id'      => $ward->id,
                        'code'         => $ward->code . '/B' . $i,
                        'type'         => $ward->type === 'ICU' ? 'ICU' : ($ward->type === 'HDU' ? 'HDU' : 'Regular'),
                        'class'        => $bedClass,
                        'daily_rate'   => $rate,
                        'status'       => 'Available',
                    ]
                );
            }
        }

        // ---- 3. Admissions (3 active + 2 discharged) ----
        $patients = Patient::limit(5)->get();
        $doctor = User::where('email', 'dr.okoro@futhlafia.gov.ng')->first();
        $admittingUser = User::where('email', 'admin@futhlafia.gov.ng')->first();

        // Admit patient 1 to Male Medical Ward bed 1
        $admission1 = Admission::firstOrCreate(
            ['admission_number' => 'ADM-' . now()->year . '-000001'],
            [
                'hospital_id'      => $hospitalId,
                'patient_id'       => $patients[0]->id,
                'ward_id'          => $wardIds['MMW'],
                'bed_id'           => Bed::where('bed_number', 'MMW-01')->first()->id,
                'admitting_doctor_id' => $doctor->id,
                'attending_doctor_id' => $doctor->id,
                'admission_date'   => now()->subDays(3),
                'admission_type'   => 'Emergency',
                'admission_source' => 'Emergency Room',
                'admission_diagnosis' => 'Severe Malaria with Anaemia',
                'admission_reason' => 'Patient collapsed at home, brought in by family',
                'status'           => 'Admitted',
                'created_by'       => $admittingUser->id,
            ]
        );

        // Admit patient 2 to Female Medical Ward bed 1
        $admission2 = Admission::firstOrCreate(
            ['admission_number' => 'ADM-' . now()->year . '-000002'],
            [
                'hospital_id'      => $hospitalId,
                'patient_id'       => $patients[1]->id,
                'ward_id'          => $wardIds['FMW'],
                'bed_id'           => Bed::where('bed_number', 'FMW-01')->first()->id,
                'admitting_doctor_id' => $doctor->id,
                'attending_doctor_id' => $doctor->id,
                'admission_date'   => now()->subDays(1),
                'admission_type'   => 'Routine',
                'admission_source' => 'Outpatient Clinic',
                'admission_diagnosis' => 'Typhoid Fever',
                'status'           => 'Admitted',
                'created_by'       => $admittingUser->id,
            ]
        );

        // Admit patient 3 to ICU
        $admission3 = Admission::firstOrCreate(
            ['admission_number' => 'ADM-' . now()->year . '-000003'],
            [
                'hospital_id'      => $hospitalId,
                'patient_id'       => $patients[2]->id,
                'ward_id'          => $wardIds['ICU'],
                'bed_id'           => Bed::where('bed_number', 'ICU-01')->first()->id,
                'admitting_doctor_id' => $doctor->id,
                'attending_doctor_id' => $doctor->id,
                'admission_date'   => now()->subHours(8),
                'admission_type'   => 'Emergency',
                'admission_source' => 'Emergency Room',
                'admission_diagnosis' => 'Diabetic Ketoacidosis',
                'status'           => 'Admitted',
                'created_by'       => $admittingUser->id,
            ]
        );

        // ---- 4. Chart of Accounts (NHIA-aligned for Nigerian hospital) ----
        $coa = [
            // Assets
            ['account_code' => '1000', 'name' => 'Cash on Hand', 'type' => 'Asset', 'sub_type' => 'Current Asset', 'is_bank_account' => false],
            ['account_code' => '1010', 'name' => 'Main Bank Account - UBA', 'type' => 'Asset', 'sub_type' => 'Current Asset', 'is_bank_account' => true, 'bank_name' => 'United Bank for Africa', 'bank_account_number' => '1023456789'],
            ['account_code' => '1020', 'name' => 'Petty Cash', 'type' => 'Asset', 'sub_type' => 'Current Asset', 'is_bank_account' => false],
            ['account_code' => '1100', 'name' => 'Accounts Receivable - Patients', 'type' => 'Asset', 'sub_type' => 'Current Asset'],
            ['account_code' => '1110', 'name' => 'Accounts Receivable - Insurance/HMO', 'type' => 'Asset', 'sub_type' => 'Current Asset'],
            ['account_code' => '1200', 'name' => 'Drug Inventory', 'type' => 'Asset', 'sub_type' => 'Current Asset'],
            ['account_code' => '1300', 'name' => 'Medical Supplies Inventory', 'type' => 'Asset', 'sub_type' => 'Current Asset'],
            ['account_code' => '1500', 'name' => 'Hospital Buildings', 'type' => 'Asset', 'sub_type' => 'Fixed Asset'],
            ['account_code' => '1510', 'name' => 'Medical Equipment', 'type' => 'Asset', 'sub_type' => 'Fixed Asset'],
            ['account_code' => '1520', 'name' => 'Office Equipment', 'type' => 'Asset', 'sub_type' => 'Fixed Asset'],
            ['account_code' => '1530', 'name' => 'Motor Vehicles', 'type' => 'Asset', 'sub_type' => 'Fixed Asset'],
            // Liabilities
            ['account_code' => '2000', 'name' => 'Accounts Payable - Suppliers', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2100', 'name' => 'Salaries Payable', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2200', 'name' => 'Tax Payable - PAYE', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2210', 'name' => 'Tax Payable - VAT', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2300', 'name' => 'Pension Contribution Payable', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2400', 'name' => 'NHIF Deductions Payable', 'type' => 'Liability', 'sub_type' => 'Current Liability'],
            ['account_code' => '2500', 'name' => 'Long-term Loan - BoF', 'type' => 'Liability', 'sub_type' => 'Long-term Liability'],
            // Equity
            ['account_code' => '3000', 'name' => 'Capital - Government', 'type' => 'Equity', 'sub_type' => 'Capital'],
            ['account_code' => '3100', 'name' => 'Retained Earnings', 'type' => 'Equity', 'sub_type' => 'Retained Earnings'],
            // Revenue
            ['account_code' => '4000', 'name' => 'Consultation Fees', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4100', 'name' => 'Pharmacy Sales', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4200', 'name' => 'Laboratory Services Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4300', 'name' => 'Radiology Services Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4400', 'name' => 'Inpatient Accommodation Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4500', 'name' => 'Theatre / Procedure Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4600', 'name' => 'Maternity Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4700', 'name' => 'Insurance Claims Revenue', 'type' => 'Revenue', 'sub_type' => 'Operating Revenue'],
            ['account_code' => '4900', 'name' => 'Other Income', 'type' => 'Revenue', 'sub_type' => 'Other Revenue'],
            // Expenses
            ['account_code' => '5000', 'name' => 'Salaries & Wages', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5100', 'name' => 'Drug Purchases', 'type' => 'Expense', 'sub_type' => 'Cost of Goods Sold'],
            ['account_code' => '5200', 'name' => 'Medical Supplies Purchases', 'type' => 'Expense', 'sub_type' => 'Cost of Goods Sold'],
            ['account_code' => '5300', 'name' => 'Utilities - Electricity', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5310', 'name' => 'Utilities - Water', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5400', 'name' => 'Fuel & Diesel (Generator)', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5500', 'name' => 'Equipment Maintenance', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5600', 'name' => 'Building Maintenance', 'type' => 'Expense', 'sub_type' => 'Operating Expense'],
            ['account_code' => '5700', 'name' => 'Office Supplies', 'type' => 'Expense', 'sub_type' => 'Administrative Expense'],
            ['account_code' => '5800', 'name' => 'Travel & Transport', 'type' => 'Expense', 'sub_type' => 'Administrative Expense'],
            ['account_code' => '5900', 'name' => 'Staff Training', 'type' => 'Expense', 'sub_type' => 'Administrative Expense'],
        ];

        foreach ($coa as $a) {
            ChartOfAccount::firstOrCreate(
                ['account_code' => $a['account_code']],
                array_merge($a, [
                    'hospital_id' => $hospitalId,
                    'currency'    => 'NGN',
                    'is_active'   => true,
                ])
            );
        }

        // ---- 5. Budgets ----
        $fy = now()->year;
        $budgets = [
            ['name' => 'Salaries - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '5000')->first()->id, 'department_id' => null, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 250000000],
            ['name' => 'Drug Purchases - Pharmacy - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '5100')->first()->id, 'department_id' => 8, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 80000000],
            ['name' => 'Medical Supplies - Lab - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '5200')->first()->id, 'department_id' => 6, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 25000000],
            ['name' => 'Utilities - Electricity - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '5300')->first()->id, 'department_id' => null, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 15000000],
            ['name' => 'Generator Fuel - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '5400')->first()->id, 'department_id' => null, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 12000000],
            ['name' => 'Consultation Revenue Target - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '4000')->first()->id, 'department_id' => null, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 45000000],
            ['name' => 'Pharmacy Sales Target - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '4100')->first()->id, 'department_id' => 8, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 120000000],
            ['name' => 'Lab Revenue Target - FY ' . $fy, 'chart_of_account_id' => ChartOfAccount::where('account_code', '4200')->first()->id, 'department_id' => 6, 'period' => 'Annual', 'fiscal_year' => $fy, 'planned_amount' => 35000000],
        ];

        foreach ($budgets as $b) {
            Budget::firstOrCreate(
                ['name' => $b['name']],
                array_merge($b, [
                    'hospital_id' => $hospitalId,
                    'actual_amount' => 0,
                    'variance' => $b['planned_amount'],
                    'status' => 'Approved',
                    'created_by' => $admittingUser->id,
                ])
            );
        }

        // ---- 6. Appointment Types ----
        $apptTypes = [
            ['name' => 'New Consultation', 'code' => 'NEW-CONS', 'category' => 'New', 'default_duration_minutes' => 30, 'default_fee' => 3000, 'color' => '#0f5132'],
            ['name' => 'Follow-up Consultation', 'code' => 'FUP-CONS', 'category' => 'Follow-up', 'default_duration_minutes' => 15, 'default_fee' => 1500, 'color' => '#198754'],
            ['name' => 'Review Consultation', 'code' => 'RVW-CONS', 'category' => 'Review', 'default_duration_minutes' => 15, 'default_fee' => 1500, 'color' => '#6c757d'],
            ['name' => 'Specialist Consultation', 'code' => 'SPC-CONS', 'category' => 'Specialist', 'default_duration_minutes' => 45, 'default_fee' => 8000, 'color' => '#0dcaf0'],
            ['name' => 'Antenatal Visit', 'code' => 'ANC', 'category' => 'New', 'default_duration_minutes' => 20, 'default_fee' => 2000, 'color' => '#ffc107'],
            ['name' => 'Postnatal Visit', 'code' => 'PNC', 'category' => 'Follow-up', 'default_duration_minutes' => 20, 'default_fee' => 2000, 'color' => '#fd7e14'],
            ['name' => 'Telemedicine Consultation', 'code' => 'TELE', 'category' => 'Telemedicine', 'default_duration_minutes' => 20, 'default_fee' => 2000, 'color' => '#6610f2'],
            ['name' => 'Emergency Consultation', 'code' => 'EMR-CONS', 'category' => 'Emergency', 'default_duration_minutes' => 60, 'default_fee' => 5000, 'color' => '#dc3545'],
            ['name' => 'Minor Procedure', 'code' => 'PROC', 'category' => 'Procedure', 'default_duration_minutes' => 60, 'default_fee' => 15000, 'color' => '#fd7e14', 'requires_pre_auth' => true],
        ];

        foreach ($apptTypes as $at) {
            AppointmentType::firstOrCreate(
                ['code' => $at['code']],
                array_merge($at, [
                    'hospital_id' => $hospitalId,
                    'is_active'   => true,
                    'requires_pre_auth' => $at['requires_pre_auth'] ?? false,
                ])
            );
        }

        // ---- 7. Clinic Schedules ----
        $clinics = \App\Models\Clinic::all();
        $weekdayMap = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $newType = AppointmentType::where('code', 'NEW-CONS')->first();
        $fupType = AppointmentType::where('code', 'FUP-CONS')->first();
        $ancType = AppointmentType::where('code', 'ANC')->first();
        $spcType = AppointmentType::where('code', 'SPC-CONS')->first();

        foreach ($clinics->take(5) as $idx => $clinic) {
            foreach ($weekdayMap as $day) {
                ClinicSchedule::firstOrCreate(
                    [
                        'clinic_id' => $clinic->id,
                        'weekday'   => $day,
                        'appointment_type_id' => $newType->id,
                    ],
                    [
                        'hospital_id'        => $hospitalId,
                        'start_time'         => '08:00:00',
                        'end_time'           => '13:00:00',
                        'break_start'        => '10:30:00',
                        'break_end'          => '11:00:00',
                        'slot_duration_minutes' => 20,
                        'max_patients_per_slot' => 2,
                        'buffer_minutes_before'  => 5,
                        'buffer_minutes_after'   => 5,
                        'is_active'          => true,
                        'created_by'         => $admittingUser->id,
                    ]
                );
                ClinicSchedule::firstOrCreate(
                    [
                        'clinic_id' => $clinic->id,
                        'weekday'   => $day,
                        'appointment_type_id' => $fupType->id,
                    ],
                    [
                        'hospital_id'        => $hospitalId,
                        'start_time'         => '14:00:00',
                        'end_time'           => '17:00:00',
                        'slot_duration_minutes' => 15,
                        'max_patients_per_slot' => 3,
                        'buffer_minutes_before'  => 0,
                        'buffer_minutes_after'   => 5,
                        'is_active'          => true,
                        'created_by'         => $admittingUser->id,
                    ]
                );
            }
        }

        // ---- 8. Provider Schedules (assign Dr. Okoro to General Medicine) ----
        $doctor = User::where('email', 'dr.okoro@futhlafia.gov.ng')->first();
        $gmClinic = \App\Models\Clinic::first();
        foreach ($weekdayMap as $day) {
            ProviderSchedule::firstOrCreate(
                [
                    'user_id' => $doctor->id,
                    'clinic_id' => $gmClinic->id,
                    'weekday' => $day,
                ],
                [
                    'hospital_id'        => $hospitalId,
                    'start_time'         => '09:00:00',
                    'end_time'           => '16:00:00',
                    'break_start'        => '13:00:00',
                    'break_end'          => '14:00:00',
                    'slot_duration_minutes' => 20,
                    'max_patients_per_slot' => 1,
                    'effective_from'     => now()->startOfYear(),
                    'effective_until'    => now()->endOfYear(),
                    'is_active'          => true,
                    'created_by'         => $admittingUser->id,
                ]
            );
        }

        // ---- 9. Generate appointment slots for the next 7 days ----
        $this->generateSlotsForWeek($hospitalId);

        // ---- 10. Holidays (Nigerian public holidays) ----
        $year = now()->year;
        $holidays = [
            ['name' => 'New Year\'s Day', 'holiday_date' => "{$year}-01-01", 'type' => 'Public Holiday'],
            ['name' => 'Workers\' Day', 'holiday_date' => "{$year}-05-01", 'type' => 'Public Holiday'],
            ['name' => 'Democracy Day', 'holiday_date' => "{$year}-06-12", 'type' => 'National'],
            ['name' => 'Independence Day', 'holiday_date' => "{$year}-10-01", 'type' => 'National'],
            ['name' => 'Christmas Day', 'holiday_date' => "{$year}-12-25", 'type' => 'Religious'],
            ['name' => 'Boxing Day', 'holiday_date' => "{$year}-12-26", 'type' => 'Public Holiday'],
            ['name' => 'Eid al-Fitr (estimated)', 'holiday_date' => "{$year}-04-10", 'type' => 'Religious'],
            ['name' => 'Eid al-Adha (estimated)', 'holiday_date' => "{$year}-06-17", 'type' => 'Religious'],
            ['name' => 'Good Friday', 'holiday_date' => "{$year}-03-29", 'type' => 'Religious'],
            ['name' => 'Easter Monday', 'holiday_date' => "{$year}-04-01", 'type' => 'Religious'],
            ['name' => 'Mawlid (estimated)', 'holiday_date' => "{$year}-09-16", 'type' => 'Religious'],
        ];
        foreach ($holidays as $h) {
            Holiday::firstOrCreate(
                ['hospital_id' => $hospitalId, 'holiday_date' => $h['holiday_date']],
                array_merge($h, [
                    'is_working_day'  => false,
                    'affects_clinics' => true,
                    'created_by'      => $admittingUser->id,
                ])
            );
        }

        // ---- 11. Leave Requests ----
        $nurse = User::where('email', 'nurse.john@futhlafia.gov.ng')->first();
        LeaveRequest::firstOrCreate(
            ['leave_number' => 'LVR-' . now()->year . '-000001'],
            [
                'hospital_id'     => $hospitalId,
                'user_id'         => $nurse->id,
                'staff_id'        => \App\Models\Staff::where('user_id', $nurse->id)->first()->id ?? null,
                'leave_type'      => 'Annual',
                'start_date'      => now()->addDays(14)->startOfDay(),
                'end_date'        => now()->addDays(21)->startOfDay(),
                'days_requested'  => 7,
                'reason'          => 'Annual vacation - family commitments.',
                'handover_to'     => 'Nurse Amina (afternoon shift)',
                'handover_notes'  => 'All patients in Male Medical Ward stable. Bed 03 due for discharge tomorrow.',
                'status'          => 'Pending',
                'created_by'      => $nurse->id,
            ]
        );

        $pharmacist = User::where('email', 'pharm.musa@futhlafia.gov.ng')->first();
        LeaveRequest::firstOrCreate(
            ['leave_number' => 'LVR-' . now()->year . '-000002'],
            [
                'hospital_id'     => $hospitalId,
                'user_id'         => $pharmacist->id,
                'staff_id'        => \App\Models\Staff::where('user_id', $pharmacist->id)->first()->id ?? null,
                'leave_type'      => 'Sick',
                'start_date'      => now()->subDays(2)->startOfDay(),
                'end_date'        => now()->addDays(2)->startOfDay(),
                'days_requested'  => 4,
                'reason'          => 'Malaria treatment and recovery.',
                'status'          => 'Approved',
                'approved_by'     => $admittingUser->id,
                'approved_at'     => now()->subDays(2),
                'created_by'      => $pharmacist->id,
            ]
        );

        $this->command->info('Modules batch 2 seeded:');
        $this->command->info('- ' . Ward::count() . ' wards, ' . Bed::count() . ' beds, ' . Admission::count() . ' admissions');
        $this->command->info('- ' . ChartOfAccount::count() . ' chart-of-accounts, ' . Budget::count() . ' budgets');
        $this->command->info('- ' . AppointmentType::count() . ' appointment types, ' . ClinicSchedule::count() . ' clinic schedules, ' . ProviderSchedule::count() . ' provider schedules');
        $this->command->info('- ' . AppointmentSlot::count() . ' appointment slots generated');
        $this->command->info('- ' . Holiday::count() . ' holidays, ' . LeaveRequest::count() . ' leave requests');
    }

    private function generateSlotsForWeek(int $hospitalId): void
    {
        $today = Carbon::today();
        for ($i = 0; $i < 7; $i++) {
            $date = $today->copy()->addDays($i)->format('Y-m-d');
            $weekday = $today->copy()->addDays($i)->format('l');

            // Skip holidays
            if (Holiday::activeOn($hospitalId, $date)->isNotEmpty()) continue;

            // Skip Sundays
            if ($weekday === 'Sunday') continue;

            foreach (ClinicSchedule::with(['clinic', 'appointmentType'])->where('weekday', $weekday)->where('is_active', true)->get() as $schedule) {
                $slots = $schedule->generateSlotsForDate($date);
                foreach ($slots as $slot) {
                    AppointmentSlot::firstOrCreate(
                        [
                            'clinic_id'  => $slot['clinic_id'],
                            'slot_date'  => $slot['slot_date'],
                            'start_time' => $slot['start_time'],
                        ],
                        $slot
                    );
                }
            }
        }
    }
}
