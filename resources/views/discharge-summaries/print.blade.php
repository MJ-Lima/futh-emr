<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discharge Summary — {{ $admission->admission_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; color: #111827; font-size: 11pt; line-height: 1.45; margin: 0; }
        .letterhead { display: flex; align-items: center; gap: 18px; padding-bottom: 12px; border-bottom: 3px solid #0f5132; margin-bottom: 14px; }
        .emblem { width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0; font-size: 9.5pt; color: #6B7280; }
        .discharge-title { text-align: center; font-size: 16pt; font-weight: 800; color: #0f5132; text-transform: uppercase; letter-spacing: 1px; margin: 10px 0; padding: 8px; background: #F0FDF4; border-radius: 4px; }
        .meta-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; padding: 10px 14px; background: #F0FDF4; border-left: 4px solid #0f5132; border-radius: 4px; }
        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 14px; }
        .patient-block td { padding: 7px 10px; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 700; color: #6B7280; font-size: 9pt; text-transform: uppercase; width: 22%; background: #F9FAFB; }
        h3.section { background: #0f5132; color: white; padding: 6px 10px; margin: 14px 0 0; font-size: 10pt; font-weight: 600; border-radius: 4px 4px 0 0; }
        .section-body { border: 1px solid #E5E7EB; border-top: none; padding: 10px 12px; margin-bottom: 14px; border-radius: 0 0 4px 4px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.data th { background: #ECFDF5; padding: 5px 8px; text-align: left; font-size: 9pt; font-weight: 600; color: #064E3B; border-bottom: 2px solid #0f5132; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10pt; }
        .signatures { display: flex; gap: 60px; margin-top: 40px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 240px; }
        .sign-line .role { font-weight: 700; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 8.5pt; color: #6B7280; }
        .no-print { display: none; }
        @media screen { .no-print { display: flex; position: fixed; top: 10px; right: 10px; z-index: 100; background: white; padding: 8px 12px; border: 1px solid #D1D5DB; border-radius: 6px; gap: 8px; } }
        .no-print a, .no-print button { padding: 6px 12px; background: #0f5132; color: white; text-decoration: none; border-radius: 4px; font-size: 10pt; cursor: pointer; border: none; }
        @media print { .no-print { display: none; } body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('discharge-summaries.pdf', $admission) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/admissions/' . $admission->id) }}">← Back</a>
    </div>

    <div class="letterhead">
        <div class="emblem"><svg viewBox="0 0 60 60"><rect x="22" y="14" width="16" height="32" rx="2" fill="#0f5132"/><rect x="14" y="22" width="32" height="16" rx="2" fill="#0f5132"/></svg></div>
        <div class="letterhead-text">
            <h1>FEDERAL UNIVERSITY TEACHING HOSPITAL, LAFIA</h1>
            <h2>HEALTH IS WEALTH</h2>
            <p>Inpatient Department — Discharge Summary &middot; Nasarawa State, Nigeria</p>
        </div>
    </div>

    <div class="discharge-title">Discharge Summary</div>

    <div class="meta-row">
        <div><strong>Admission #:</strong> {{ $admission->admission_number }}<br><strong>Length of Stay:</strong> {{ $admission->lengthOfStayDays() }} days</div>
        <div style="text-align:right"><strong>Admitted:</strong> {{ $admission->admission_date?->format('d M Y, H:i') }}<br><strong>Discharged:</strong> {{ $admission->discharge_date?->format('d M Y, H:i') ?? '—' }}</div>
    </div>

    <table class="patient-block">
        <tr>
            <td class="label">Patient</td><td>{{ $admission->patient->full_name }}</td>
            <td class="label">Hospital No</td><td>{{ $admission->patient->hospital_no }}</td>
        </tr>
        <tr>
            <td class="label">Age / Sex</td><td>{{ $admission->patient->age ?? '—' }} / {{ ucfirst($admission->patient->gender ?? '') }}</td>
            <td class="label">Phone</td><td>{{ $admission->patient->phonemain }}</td>
        </tr>
        <tr>
            <td class="label">Ward / Bed</td><td>{{ $admission->ward?->name ?? '—' }} / {{ $admission->bed?->bed_number ?? '—' }}</td>
            <td class="label">Admission Type</td><td>{{ $admission->admission_type }}</td>
        </tr>
        <tr>
            <td class="label">Admitting Doctor</td><td>{{ $admission->admittingDoctor?->name ?? '—' }}</td>
            <td class="label">Attending Doctor</td><td>{{ $admission->attendingDoctor?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Resident Doctor</td><td>{{ $admission->residentDoctor?->name ?? '—' }}</td>
            <td class="label">Discharged By</td><td>{{ $admission->dischargedBy?->name ?? '—' }}</td>
        </tr>
    </table>

    <h3 class="section">Admission Diagnosis & Reason</h3>
    <div class="section-body">
        <p><strong>Admission Diagnosis:</strong> {{ $admission->admission_diagnosis ?? '—' }}</p>
        <p><strong>Reason for Admission:</strong> {{ $admission->admission_reason ?? '—' }}</p>
        <p><strong>Source:</strong> {{ $admission->admission_source }}</p>
    </div>

    @if($admission->encounter && $admission->encounter->diagnoses->isNotEmpty())
    <h3 class="section">Diagnoses (ICD-10)</h3>
    <div class="section-body">
        <table class="data">
            <thead><tr><th>ICD-10</th><th>Description</th><th>Type</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($admission->encounter->diagnoses as $dx)
                <tr><td>{{ $dx->icd10_code }}</td><td>{{ $dx->description }}</td><td>{{ $dx->type }}</td><td>{{ $dx->status }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <h3 class="section">Clinical Course & Treatment</h3>
    <div class="section-body">
        <p><strong>Clinical Notes:</strong> {{ $admission->clinical_notes ?? '—' }}</p>
        @if($wardRoundNotes->isNotEmpty())
        <p><strong>Ward Round Summary ({{ $wardRoundNotes->count() }} notes):</strong></p>
        <table class="data">
            <thead><tr><th>Date</th><th>Type</th><th>Doctor</th><th>Assessment</th><th>Plan</th></tr></thead>
            <tbody>
                @foreach($wardRoundNotes as $wrn)
                <tr>
                    <td>{{ $wrn->round_date?->format('d M') }}</td>
                    <td>{{ $wrn->round_type }}</td>
                    <td>{{ $wrn->doctor?->name }}</td>
                    <td>{{ mb_substr($wrn->assessment ?? '—', 0, 60) }}</td>
                    <td>{{ mb_substr($wrn->plan ?? '—', 0, 60) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($labOrders->isNotEmpty())
    <h3 class="section">Investigations During Admission</h3>
    <div class="section-body">
        <table class="data">
            <thead><tr><th>Order #</th><th>Test</th><th>Result</th><th>Flag</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($labOrders as $lab)
                    @foreach($lab->items as $item)
                    <tr>
                        <td>{{ $lab->order_number }}</td>
                        <td>{{ $item->test_name }}</td>
                        <td>{{ $item->result?->value ?? '—' }}</td>
                        <td>{{ $item->result?->flag ?? '—' }}</td>
                        <td>{{ $item->status }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($prescriptions->isNotEmpty())
    <h3 class="section">Medications During Admission</h3>
    <div class="section-body">
        <table class="data">
            <thead><tr><th>Drug</th><th>Dose</th><th>Frequency</th><th>Duration</th><th>Qty</th></tr></thead>
            <tbody>
                @foreach($prescriptions as $rx)
                    @foreach($rx->items as $item)
                    <tr>
                        <td>{{ $item->drug_name }}</td>
                        <td>{{ $item->dose ?? '—' }}</td>
                        <td>{{ $item->frequency ?? '—' }}</td>
                        <td>{{ $item->duration ?? '—' }}</td>
                        <td>{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <h3 class="section">Discharge Information</h3>
    <div class="section-body">
        <p><strong>Discharge Diagnosis:</strong> {{ $admission->discharge_diagnosis ?? '—' }}</p>
        <p><strong>Reason for Discharge:</strong> {{ $admission->discharge_reason ?? '—' }}</p>
        <p><strong>Discharge Summary:</strong> {{ $admission->discharge_summary ?? '—' }}</p>
        <p><strong>Discharge Condition:</strong> {{ $admission->status }}</p>
    </div>

    <div class="signatures">
        <div class="sign-line">
            <div class="role">Attending Doctor</div>
            <div class="name">{{ $admission->attendingDoctor?->name ?? '_________________________' }}<br>Date: {{ $admission->discharge_date?->format('d M Y') ?? '___ / ___ / _____' }}</div>
        </div>
        <div class="sign-line">
            <div class="role">Resident Doctor</div>
            <div class="name">{{ $admission->residentDoctor?->name ?? '_________________________' }}<br>Date: {{ $admission->discharge_date?->format('d M Y') ?? '___ / ___ / _____' }}</div>
        </div>
        <div class="sign-line">
            <div class="role">Patient / Relative (Acknowledged)</div>
            <div class="name">_________________________<br>Date: ___ / ___ / _____</div>
        </div>
    </div>

    <div class="footer">Discharge Summary for Admission {{ $admission->admission_number }} &middot; Generated {{ now()->format('d M Y, H:i') }} from FUTH Lafia EMR</div>
</body>
</html>
