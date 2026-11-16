<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Referral Letter — {{ $referral->referral_number }}</title>
    <style>
        @page { margin: 18mm 16mm; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827; font-size: 11pt; line-height: 1.55; margin: 0;
        }
        .letterhead {
            display: flex; align-items: center; gap: 18px;
            padding-bottom: 14px; border-bottom: 3px solid #0f5132;
            margin-bottom: 14px;
        }
        .emblem {
            width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; letter-spacing: 0.5px; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0 0; font-size: 9.5pt; color: #6B7280; }

        .ref-meta {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 16px; padding: 10px 14px; background: #F0FDF4;
            border-left: 4px solid #0f5132; border-radius: 4px;
        }
        .ref-meta .left { font-size: 11pt; }
        .ref-meta .right { text-align: right; font-size: 10pt; color: #374151; }

        .ref-header {
            margin-bottom: 18px;
        }
        .ref-header .to { font-weight: 700; font-size: 11.5pt; color: #0f5132; margin-bottom: 4px; }
        .ref-header .facility { font-size: 11pt; color: #111827; }
        .ref-header .re { margin-top: 12px; font-weight: 700; }
        .ref-header .patient-line { margin-top: 4px; font-size: 11pt; }

        .salutation { margin: 14px 0 10px 0; }

        .section {
            margin: 14px 0; padding: 12px 14px;
            border: 1px solid #E5E7EB; border-left: 4px solid #0f5132;
            border-radius: 4px;
        }
        .section h3 {
            margin: 0 0 8px 0; font-size: 10.5pt; color: #0f5132; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .section p { margin: 4px 0; font-size: 10.5pt; color: #111827; }

        .signatures { display: flex; gap: 80px; margin-top: 50px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 240px; }
        .sign-line .role { font-weight: 700; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }

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

    <div class="print-buttons no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('referrals.pdf', $referral) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/referrals/' . $referral->id) }}">← Back</a>
    </div>

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
            <p>Clinical Department &middot; Nasarawa State, Nigeria</p>
        </div>
    </div>

    <div class="ref-meta">
        <div class="left">
            <strong>Referral #:</strong> {{ $referral->referral_number }}<br>
            <strong>Type:</strong> {{ $referral->referral_type }} ({{ $referral->patient_category }})
        </div>
        <div class="right">
            <strong>Date:</strong> {{ $referral->referred_date?->format('d M Y') }}<br>
            <strong>Urgency:</strong> {{ $referral->urgency }}
        </div>
    </div>

    <div class="ref-header">
        <div class="to">To: @if($referral->destination_doctor){{ $referral->destination_doctor }}@else(The Consultant)@endif</div>
        @if($referral->destination_facility)
        <div class="facility">{{ $referral->destination_facility }}</div>
        @endif
        @if($referral->destination_department)
        <div class="facility">Department of {{ $referral->destination_department }}</div>
        @endif
        @if($referral->referredTo)
        <div class="facility">Internal: {{ $referral->referredTo->name }}</div>
        @endif
        <div class="re">RE: Referral of Patient — {{ $referral->patient->full_name }} ({{ $referral->patient->hospital_no }})</div>
        <div class="patient-line">Age/Sex: {{ $referral->patient->age ?? '—' }} / {{ ucfirst($referral->patient->gender ?? '') }}</div>
    </div>

    <p class="salutation">Dear Doctor,</p>

    <p>Thank you for accepting the referral of the above-named patient. The clinical details are summarized below.</p>

    @if($referral->clinical_summary)
    <div class="section">
        <h3>Clinical Summary</h3>
        <p>{{ $referral->clinical_summary }}</p>
    </div>
    @endif

    @if($referral->diagnosis)
    <div class="section">
        <h3>Diagnosis</h3>
        <p>{{ $referral->diagnosis }}</p>
    </div>
    @endif

    @if($referral->treatment_given)
    <div class="section">
        <h3>Treatment Given So Far</h3>
        <p>{{ $referral->treatment_given }}</p>
    </div>
    @endif

    @if($referral->investigations_done)
    <div class="section">
        <h3>Investigations Already Done</h3>
        <p>{{ $referral->investigations_done }}</p>
    </div>
    @endif

    @if($referral->reason_for_referral)
    <div class="section">
        <h3>Reason for Referral</h3>
        <p>{{ $referral->reason_for_referral }}</p>
    </div>
    @endif

    @if($referral->investigations_requested)
    <div class="section">
        <h3>Investigations / Action Requested</h3>
        <p>{{ $referral->investigations_requested }}</p>
    </div>
    @endif

    @if($referral->appointment_date)
    <p style="margin-top: 16px;">
        <strong>Requested appointment date:</strong> {{ $referral->appointment_date->format('d M Y') }}
    </p>
    @endif

    <p style="margin-top: 16px;">
        Kindly provide your feedback on the outcome of this referral. Should you require any further information,
        please do not hesitate to contact the referring officer.
    </p>

    <p style="margin-top: 16px;">Yours sincerely,</p>

    <div class="signatures">
        <div class="sign-line">
            <div class="role">Referring Medical Officer</div>
            <div class="name">
                {{ $referral->referrer?->name ?? '_________________________' }}<br>
                Name &amp; Signature<br>
                Date: {{ $referral->referred_date?->format('d M Y') ?? '___ / ___ / _____' }}
            </div>
        </div>
        <div class="sign-line">
            <div class="role">For: Medical Director</div>
            <div class="name">
                _________________________<br>
                FUTH Lafia<br>
                Date: ___ / ___ / _____
            </div>
        </div>
    </div>

    <div class="footer">
        Referral ID: {{ $referral->referral_number }} &middot;
        Generated {{ now()->format('d M Y, H:i') }} from FUTH Lafia EMR
    </div>
</body>
</html>
