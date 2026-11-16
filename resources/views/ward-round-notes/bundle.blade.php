<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ward Round Notes — {{ $admission->admission_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; color: #111827; font-size: 10pt; line-height: 1.4; margin: 0; }
        .letterhead { display: flex; align-items: center; gap: 18px; padding-bottom: 10px; border-bottom: 3px solid #0f5132; margin-bottom: 12px; }
        .emblem { width: 60px; height: 60px; border: 2px solid #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .emblem svg { width: 38px; height: 38px; }
        .letterhead-text h1 { margin: 0; font-size: 16pt; color: #0f5132; font-weight: 700; }
        .letterhead-text h2 { margin: 0; font-size: 10pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .patient-info { background: #F0FDF4; border-left: 4px solid #0f5132; padding: 10px 14px; margin-bottom: 16px; border-radius: 4px; }
        .patient-info strong { color: #0f5132; }
        .note { border: 1px solid #E5E7EB; border-radius: 6px; margin-bottom: 12px; overflow: hidden; }
        .note-header { background: #0f5132; color: white; padding: 6px 10px; font-size: 9pt; font-weight: 600; display: flex; justify-content: space-between; }
        .note-body { padding: 10px 12px; }
        .note-body p { margin: 3px 0; font-size: 9.5pt; }
        .soap-label { font-weight: 700; color: #0f5132; }
        .vitals { display: flex; gap: 8px; flex-wrap: wrap; margin: 6px 0; }
        .vital { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 4px; padding: 3px 8px; font-size: 8.5pt; }
        .vital strong { color: #15803D; }
        .footer { margin-top: 20px; padding-top: 8px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 8pt; color: #6B7280; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="letterhead">
        <div class="emblem"><svg viewBox="0 0 60 60"><rect x="22" y="14" width="16" height="32" rx="2" fill="#0f5132"/><rect x="14" y="22" width="32" height="16" rx="2" fill="#0f5132"/></svg></div>
        <div class="letterhead-text">
            <h1>FEDERAL UNIVERSITY TEACHING HOSPITAL, LAFIA</h1>
            <h2>HEALTH IS WEALTH — Ward Round Notes Bundle</h2>
        </div>
    </div>

    <div class="patient-info">
        <strong>Patient:</strong> {{ $admission->patient->full_name }} &middot;
        <strong>Code:</strong> {{ $admission->patient->hospital_no }} &middot;
        <strong>Age/Sex:</strong> {{ $admission->patient->age ?? '—' }} / {{ ucfirst($admission->patient->gender ?? '') }}<br>
        <strong>Admission #:</strong> {{ $admission->admission_number }} &middot;
        <strong>Admitted:</strong> {{ $admission->admission_date?->format('d M Y') }} &middot;
        <strong>Ward:</strong> {{ $admission->ward?->name ?? '—' }} &middot;
        <strong>Dx:</strong> {{ $admission->admission_diagnosis ?? '—' }}
    </div>

    @if($notes->isEmpty())
        <p style="text-align:center; color:#9CA3AF; padding:40px;">No ward round notes recorded for this admission.</p>
    @else
        @foreach($notes as $i => $note)
            @if($i > 0 && $i % 3 == 0)<div class="page-break"></div>@endif
            <div class="note">
                <div class="note-header">
                    <span>{{ $note->note_number }} — {{ $note->round_type }} Round</span>
                    <span>{{ $note->round_date?->format('d M Y') }} at {{ is_string($note->round_time) ? substr($note->round_time, 0, 5) : $note->round_time?->format('H:i') }} — Dr. {{ $note->doctor?->name }}</span>
                </div>
                <div class="note-body">
                    @if($note->temperature || $note->systolic_bp || $note->heart_rate)
                    <div class="vitals">
                        @if($note->temperature)<span class="vital"><strong>T:</strong> {{ $note->temperature }}°C</span>@endif
                        @if($note->systolic_bp)<span class="vital"><strong>BP:</strong> {{ $note->systolic_bp }}/{{ $note->diastolic_bp }}</span>@endif
                        @if($note->heart_rate)<span class="vital"><strong>HR:</strong> {{ $note->heart_rate }}</span>@endif
                        @if($note->respiratory_rate)<span class="vital"><strong>RR:</strong> {{ $note->respiratory_rate }}</span>@endif
                        @if($note->spo2)<span class="vital"><strong>SpO₂:</strong> {{ $note->spo2 }}%</span>@endif
                        @if($note->blood_glucose)<span class="vital"><strong>Glucose:</strong> {{ $note->blood_glucose }}</span>@endif
                    </div>
                    @endif
                    <p><span class="soap-label">S:</span> {{ $note->subjective ?? '—' }}</p>
                    <p><span class="soap-label">O:</span> {{ $note->objective ?? '—' }}</p>
                    <p><span class="soap-label">A:</span> {{ $note->assessment ?? '—' }}</p>
                    <p><span class="soap-label">P:</span> {{ $note->plan ?? '—' }}</p>
                    @if($note->medications_changes)<p><strong>Meds:</strong> {{ $note->medications_changes }}</p>@endif
                    @if($note->nurse_action_required)<p><strong>Nurse Action:</strong> {{ $note->nurse_action_required }}</p>@endif
                </div>
            </div>
        @endforeach
    @endif

    <div class="footer">Bundle for Admission {{ $admission->admission_number }} — {{ $notes->count() }} notes &middot; Generated {{ now()->format('d M Y, H:i') }}</div>
</body>
</html>
