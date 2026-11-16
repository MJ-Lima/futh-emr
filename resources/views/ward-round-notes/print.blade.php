<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ward Round Note — {{ $note->note_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; color: #111827; font-size: 11pt; line-height: 1.45; margin: 0; }
        .letterhead { display: flex; align-items: center; gap: 18px; padding-bottom: 12px; border-bottom: 3px solid #0f5132; margin-bottom: 14px; }
        .emblem { width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0; font-size: 9.5pt; color: #6B7280; }
        .note-meta { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; padding: 10px 14px; background: #F0FDF4; border-left: 4px solid #0f5132; border-radius: 4px; }
        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 14px; }
        .patient-block td { padding: 7px 10px; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 700; color: #6B7280; font-size: 9pt; text-transform: uppercase; width: 18%; background: #F9FAFB; }
        h3.section { background: #0f5132; color: white; padding: 6px 10px; margin: 14px 0 0; font-size: 10pt; font-weight: 600; border-radius: 4px 4px 0 0; }
        .section-body { border: 1px solid #E5E7EB; border-top: none; padding: 10px 12px; margin-bottom: 14px; border-radius: 0 0 4px 4px; }
        .vitals-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 8px; }
        .vital-card { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 6px; padding: 8px; text-align: center; }
        .vital-card .v { font-size: 1.1rem; font-weight: 700; color: #15803D; }
        .vital-card .l { font-size: 0.65rem; text-transform: uppercase; color: #6B7280; letter-spacing: 0.04em; }
        .soap-label { font-weight: 700; color: #0f5132; }
        .signatures { display: flex; gap: 60px; margin-top: 40px; padding-top: 18px; }
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
        <a href="{{ route('ward-round-notes.pdf', $note) }}">⬇ Download PDF</a>
        @if($note->admission_id)
        <a href="{{ route('ward-round-notes.bundle', $note->admission_id) }}">⬇ All Notes for Admission</a>
        @endif
        <a href="{{ url('/admin/ward-round-notes/' . $note->id) }}">← Back</a>
    </div>

    <div class="letterhead">
        <div class="emblem"><svg viewBox="0 0 60 60"><rect x="22" y="14" width="16" height="32" rx="2" fill="#0f5132"/><rect x="14" y="22" width="32" height="16" rx="2" fill="#0f5132"/></svg></div>
        <div class="letterhead-text">
            <h1>FEDERAL UNIVERSITY TEACHING HOSPITAL, LAFIA</h1>
            <h2>HEALTH IS WEALTH</h2>
            <p>Inpatient Department — Ward Round Note &middot; Nasarawa State, Nigeria</p>
        </div>
    </div>

    <div class="note-meta">
        <div><strong>Note #:</strong> {{ $note->note_number }}<br><strong>Round Type:</strong> {{ $note->round_type }}</div>
        <div style="text-align:right"><strong>Date:</strong> {{ $note->round_date?->format('d M Y') }}<br><strong>Time:</strong> {{ is_string($note->round_time) ? substr($note->round_time, 0, 5) : $note->round_time?->format('H:i') }}<br><strong>Doctor:</strong> {{ $note->doctor?->name }}</div>
    </div>

    <table class="patient-block">
        <tr><td class="label">Patient</td><td>{{ $note->patient->full_name }}</td><td class="label">Code</td><td>{{ $note->patient->hospital_no }}</td></tr>
        <tr><td class="label">Age / Sex</td><td>{{ $note->patient->age ?? '—' }} / {{ ucfirst($note->patient->gender ?? '') }}</td><td class="label">Phone</td><td>{{ $note->patient->phonemain }}</td></tr>
        @if($note->admission)
        <tr><td class="label">Admission #</td><td>{{ $note->admission->admission_number }}</td><td class="label">Admitted</td><td>{{ $note->admission->admission_date?->format('d M Y') }}</td></tr>
        <tr><td class="label">Admission Dx</td><td colspan="3">{{ $note->admission->admission_diagnosis ?? '—' }}</td></tr>
        @endif
        <tr><td class="label">Ward</td><td>{{ $note->ward?->name ?? '—' }}</td><td class="label">Bed</td><td>{{ $note->bed?->bed_number ?? '—' }}</td></tr>
    </table>

    @if($note->temperature || $note->systolic_bp || $note->heart_rate || $note->respiratory_rate || $note->spo2 || $note->blood_glucose || $note->weight_kg)
    <h3 class="section">Vital Signs</h3>
    <div class="section-body">
        <div class="vitals-grid">
            @if($note->temperature)<div class="vital-card"><div class="v">{{ $note->temperature }} °C</div><div class="l">Temperature</div></div>@endif
            @if($note->systolic_bp)<div class="vital-card"><div class="v">{{ $note->systolic_bp }}/{{ $note->diastolic_bp }}</div><div class="l">Blood Pressure</div></div>@endif
            @if($note->heart_rate)<div class="vital-card"><div class="v">{{ $note->heart_rate }}</div><div class="l">Pulse (bpm)</div></div>@endif
            @if($note->respiratory_rate)<div class="vital-card"><div class="v">{{ $note->respiratory_rate }}</div><div class="l">Resp Rate</div></div>@endif
            @if($note->spo2)<div class="vital-card"><div class="v">{{ $note->spo2 }}%</div><div class="l">SpO₂</div></div>@endif
            @if($note->blood_glucose)<div class="vital-card"><div class="v">{{ $note->blood_glucose }}</div><div class="l">Glucose mmol/L</div></div>@endif
            @if($note->weight_kg)<div class="vital-card"><div class="v">{{ $note->weight_kg }} kg</div><div class="l">Weight</div></div>@endif
        </div>
    </div>
    @endif

    <h3 class="section">SOAP Note</h3>
    <div class="section-body">
        <p><span class="soap-label">S — Subjective:</span> {{ $note->subjective ?? '—' }}</p>
        <p><span class="soap-label">O — Objective:</span> {{ $note->objective ?? '—' }}</p>
        <p><span class="soap-label">A — Assessment:</span> {{ $note->assessment ?? '—' }}</p>
        <p><span class="soap-label">P — Plan:</span> {{ $note->plan ?? '—' }}</p>
    </div>

    @if($note->investigations_ordered || $note->medications_changes || $note->plan_for_discharge || $note->nurse_action_required || $note->doctor_notes)
    <h3 class="section">Plan & Instructions</h3>
    <div class="section-body">
        @if($note->investigations_ordered)<p><strong>Investigations Ordered:</strong> {{ $note->investigations_ordered }}</p>@endif
        @if($note->medications_changes)<p><strong>Medications Changes:</strong> {{ $note->medications_changes }}</p>@endif
        @if($note->plan_for_discharge)<p><strong>Plan for Discharge:</strong> {{ $note->plan_for_discharge }}</p>@endif
        @if($note->nurse_action_required)<p><strong>Nurse Action Required:</strong> {{ $note->nurse_action_required }}</p>@endif
        @if($note->doctor_notes)<p><strong>Doctor's Notes:</strong> {{ $note->doctor_notes }}</p>@endif
    </div>
    @endif

    <div class="signatures">
        <div class="sign-line">
            <div class="role">Doctor's Signature</div>
            <div class="name">{{ $note->doctor?->name ?? '_________________________' }}<br>Date: {{ $note->round_date?->format('d M Y') ?? '___ / ___ / _____' }}</div>
        </div>
        <div class="sign-line">
            <div class="role">Nurse (Acknowledged)</div>
            <div class="name">_________________________<br>Date: ___ / ___ / _____</div>
        </div>
    </div>

    <div class="footer">Ward Round Note ID: {{ $note->note_number }} &middot; Generated {{ now()->format('d M Y, H:i') }} from FUTH Lafia EMR</div>
</body>
</html>
