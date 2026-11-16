<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consultation Note — {{ $note->note_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827; font-size: 11pt; line-height: 1.45; margin: 0;
        }
        .letterhead {
            display: flex; align-items: center; gap: 18px;
            padding-bottom: 12px; border-bottom: 3px solid #0f5132; margin-bottom: 14px;
        }
        .emblem {
            width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; letter-spacing: 0.5px; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0 0; font-size: 9.5pt; color: #6B7280; }

        .note-meta {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 14px; padding: 10px 14px; background: #F0FDF4;
            border-left: 4px solid #0f5132; border-radius: 4px;
        }
        .note-meta .left { font-size: 11pt; }
        .note-meta .right { text-align: right; font-size: 10pt; color: #374151; }

        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 14px; }
        .patient-block td { padding: 7px 10px; vertical-align: top; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 700; color: #6B7280; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px; width: 18%; background: #F9FAFB; }
        .patient-block .value { color: #111827; }

        h3.section {
            background: #0f5132; color: white; padding: 6px 10px;
            margin: 16px 0 8px 0; font-size: 10pt; font-weight: 600;
            border-radius: 4px 4px 0 0;
        }
        .section-body {
            border: 1px solid #E5E7EB; border-top: none;
            padding: 10px 12px; margin-bottom: 14px;
            border-radius: 0 0 4px 4px;
        }
        .section-body p { margin: 4px 0; }
        .section-body .empty { color: #9CA3AF; font-style: italic; }
        .complaint-badge {
            display: inline-block; background: #DCFCE7; color: #15803D;
            padding: 3px 10px; border-radius: 4px; margin: 2px 4px 2px 0; font-size: 10pt;
        }
        .dx-row {
            border-bottom: 1px dashed #E5E7EB; padding: 4px 0;
        }
        .dx-row:last-child { border-bottom: none; }
        .dx-type {
            display: inline-block; background: #DBEAFE; color: #1E40AF;
            padding: 1px 8px; border-radius: 3px; font-size: 8.5pt; font-weight: 600;
            margin-right: 6px;
        }

        .clinic-data-table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .clinic-data-table td { padding: 4px 8px; border: 1px solid #E5E7EB; font-size: 10pt; }
        .clinic-data-table .ck { font-weight: 600; background: #F9FAFB; width: 45%; color: #374151; }
        .clinic-data-table .cv { color: #111827; }

        .signatures { display: flex; gap: 60px; margin-top: 50px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 240px; }
        .sign-line .role { font-weight: 700; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }

        .watermark {
            position: fixed; top: 35%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80pt; font-weight: 800; color: rgba(220, 38, 38, 0.18);
            letter-spacing: 12px; z-index: 0; pointer-events: none; white-space: nowrap;
        }
        .content { position: relative; z-index: 1; }

        .footer {
            margin-top: 24px; padding-top: 10px; border-top: 1px solid #E5E7EB;
            text-align: center; font-size: 8.5pt; color: #6B7280;
        }

        @media print {
            .no-print { display: none; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        .print-buttons {
            position: fixed; top: 10px; right: 10px; z-index: 100;
            background: white; padding: 8px 12px; border: 1px solid #D1D5DB;
            border-radius: 6px; display: flex; gap: 8px;
        }
        .print-buttons a, .print-buttons button {
            padding: 6px 12px; background: #0f5132; color: white; text-decoration: none;
            border-radius: 4px; font-size: 10pt; cursor: pointer; border: none;
        }
    </style>
</head>
<body>

    @if($note->status === 'Draft')
    <div class="watermark">DRAFT</div>
    @endif

    <div class="print-buttons no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('consultation-notes.pdf', $note) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/consultation-notes/' . $note->id) }}">← Back</a>
    </div>

    <div class="content">

        <div class="letterhead">
            <div class="emblem">
                <svg viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                    <rect x="22" y="14" width="16" height="32" rx="2" fill="#0f5132"/>
                    <rect x="14" y="22" width="32" height="16" rx="2" fill="#0f5132"/>
                </svg>
            </div>
            <div class="letterhead-text">
                <h1>FEDERAL UNIVERSITY TEACHING HOSPITAL, LAFIA</h1>
                <h2>HEALTH IS WEALTH</h2>
                <p>{{ $note->clinic?->name ?? 'Clinical Department' }} &middot; Nasarawa State, Nigeria</p>
            </div>
        </div>

        <div class="note-meta">
            <div class="left">
                <strong>Consultation Note #:</strong> {{ $note->note_number }}<br>
                <strong>Status:</strong> {{ $note->status }}
            </div>
            <div class="right">
                <strong>Date:</strong> {{ $note->created_at?->format('d M Y, H:i') }}<br>
                <strong>Author:</strong> {{ $note->author?->name ?? '—' }}
                @if($note->countersigned_at)
                <br><strong>Counter-signed:</strong> {{ $note->countersigned_at->format('d M Y, H:i') }}
                @endif
            </div>
        </div>

        <table class="patient-block">
            <tr>
                <td class="label">Patient</td>
                <td class="value">{{ $note->patient->full_name }}</td>
                <td class="label">Hospital No</td>
                <td class="value">{{ $note->patient->hospital_no }}</td>
            </tr>
            <tr>
                <td class="label">Age / Sex</td>
                <td class="value">{{ $note->patient->age ?? '—' }} / {{ ucfirst($note->patient->gender ?? '') }}</td>
                <td class="label">Phone</td>
                <td class="value">{{ $note->patient->phonemain }}</td>
            </tr>
            @if($note->encounter)
            <tr>
                <td class="label">Encounter #</td>
                <td class="value">{{ $note->encounter->encounter_number }}</td>
                <td class="label">Visit Type</td>
                <td class="value">{{ $note->encounter->visit_type }}</td>
            </tr>
            @endif
            @if($note->clinic)
            <tr>
                <td class="label">Clinic</td>
                <td class="value" colspan="3">{{ $note->clinic->name }} ({{ $note->clinic->clinic_type }})</td>
            </tr>
            @endif
        </table>

        {{-- 1. Presenting Complaints --}}
        <h3 class="section">Presenting Complaints</h3>
        <div class="section-body">
            @if(!$presentingComplaints || $presentingComplaints->isEmpty())
                <p class="empty">No presenting complaints recorded.</p>
            @else
                @foreach($presentingComplaints as $pc)
                    <span class="complaint-badge">{{ $pc->name }}</span>
                @endforeach
                <p style="margin-top: 8px;">
                    @php
                        $durations = $presentingComplaints->map(fn($pc) =>
                            "<strong>{$pc->name}</strong>: {$pc->pivot->duration}" .
                            ($pc->pivot->description ? " — {$pc->pivot->description}" : '')
                        )->implode(' &middot; ');
                    @endphp
                    {!! $durations !!}
                </p>
            @endif
        </div>

        {{-- 2. ICD-10 Diagnoses --}}
        @if($encounterDiagnoses->isNotEmpty())
        <h3 class="section">Diagnoses (ICD-10)</h3>
        <div class="section-body">
            @foreach($encounterDiagnoses as $dx)
                <div class="dx-row">
                    <span class="dx-type">{{ $dx->type }}</span>
                    <strong>{{ $dx->icd10_code }}</strong> — {{ $dx->description }}
                    @if($dx->status)
                        <em style="color:#6B7280; font-size:9pt;">({{ $dx->status }})</em>
                    @endif
                </div>
            @endforeach
        </div>
        @endif

        {{-- 3. Physical Examination --}}
        <h3 class="section">Physical Examination</h3>
        <div class="section-body">
            <p><strong>General:</strong> {{ $note->physical_examination_general ?? '—' }}</p>
            <p><strong>Systemic:</strong> {{ $note->physical_examination_systemic ?? '—' }}</p>
            <p><strong>Clinic-Specific:</strong> {{ $note->physical_examination_specific ?? '—' }}</p>
        </div>

        {{-- 4. General History --}}
        <h3 class="section">General History</h3>
        <div class="section-body">
            <p><strong>HPI:</strong> {{ $note->history_presenting_illness ?? '—' }}</p>
            <p><strong>Past Medical:</strong> {{ $note->history_past_medical ?? '—' }}</p>
            <p><strong>Past Surgical:</strong> {{ $note->history_past_surgical ?? '—' }}</p>
            <p><strong>Drug Allergies:</strong> {{ $note->history_drug_allergies ?? '—' }}</p>
            <p><strong>Family History:</strong> {{ $note->history_family ?? '—' }}</p>
            <p><strong>Social History:</strong> {{ $note->history_social ?? '—' }}</p>
            @if($note->history_obstetric)
            <p><strong>Obstetric History:</strong> {{ $note->history_obstetric }}</p>
            @endif
            @if($note->history_developmental)
            <p><strong>Developmental History:</strong> {{ $note->history_developmental }}</p>
            @endif
        </div>

        {{-- 5. Clinic-Specific Template Fields --}}
        @if($note->clinic_specific_data && is_array($note->clinic_specific_data) && count($note->clinic_specific_data) > 0)
        <h3 class="section">Clinic-Specific Template ({{ $note->clinic?->clinic_type ?? '' }})</h3>
        <div class="section-body">
            @if($note->template)
                <p style="font-size:9pt; color:#6B7280;">Template: {{ $note->template->name }}</p>
            @endif
            <table class="clinic-data-table">
                @foreach($note->clinic_specific_data as $k => $v)
                <tr>
                    <td class="ck">{{ $k }}</td>
                    <td class="cv">{{ $v }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        {{-- 6. Doctor's Clinic Note --}}
        <h3 class="section">Doctor's Clinic Note</h3>
        <div class="section-body">
            <p><strong>Examination Findings:</strong> {{ $note->examination_findings ?? '—' }}</p>
            <p><strong>Assessment:</strong> {{ $note->assessment ?? '—' }}</p>
            <p><strong>Plan:</strong> {{ $note->plan ?? '—' }}</p>
            <p><strong>Additional Notes:</strong> {{ $note->doctor_notes ?? '—' }}</p>
        </div>

        {{-- Signatures --}}
        <div class="signatures">
            <div class="sign-line">
                <div class="role">Doctor's Signature</div>
                <div class="name">
                    {{ $note->author?->name ?? '_________________________' }}<br>
                    Date: {{ $note->created_at?->format('d M Y') ?? '___ / ___ / _____' }}
                </div>
            </div>
            @if($note->countersigned_by)
            <div class="sign-line">
                <div class="role">Counter-Signed by (Senior Doctor)</div>
                <div class="name">
                    {{ $note->countersigner?->name ?? '_________________________' }}<br>
                    Date: {{ $note->countersigned_at?->format('d M Y') ?? '___ / ___ / _____' }}
                </div>
            </div>
            @endif
        </div>

        <div class="footer">
            Consultation Note ID: {{ $note->note_number }} &middot;
            Generated {{ now()->format('d M Y, H:i') }} from FUTH Lafia EMR
            @if($note->status === 'Draft')
                <br><strong style="color:#B91C1C;">This is a DRAFT and not valid until finalized.</strong>
            @endif
        </div>
    </div>
</body>
</html>
