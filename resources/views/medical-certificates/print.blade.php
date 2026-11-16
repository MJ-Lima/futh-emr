<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medical Certificate — {{ $certificate->certificate_number }}</title>
    <style>
        @page { margin: 18mm 16mm; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827; font-size: 11pt; line-height: 1.5; margin: 0;
        }
        .letterhead {
            display: flex; align-items: center; gap: 18px;
            padding-bottom: 14px; border-bottom: 3px solid #0f5132;
            margin-bottom: 18px;
        }
        .emblem {
            width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; letter-spacing: 0.5px; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0 0; font-size: 9.5pt; color: #6B7280; }

        h1.certificate-title {
            text-align: center; color: #0f5132; font-size: 22pt; font-weight: 800;
            margin: 14px 0 4px 0; text-transform: uppercase; letter-spacing: 1px;
        }
        .certificate-subtitle { text-align: center; font-size: 11pt; color: #6B7280; margin-bottom: 18px; font-style: italic; }

        .meta-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 14px; padding: 10px 14px; background: #F0FDF4;
            border-left: 4px solid #0f5132; border-radius: 4px;
        }
        .meta-row .left { font-size: 11pt; }
        .meta-row .right { text-align: right; font-size: 10pt; color: #374151; }

        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 18px; }
        .patient-block td { padding: 7px 10px; vertical-align: top; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 700; color: #6B7280; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px; width: 22%; background: #F9FAFB; }
        .patient-block .value { color: #111827; }

        .section {
            margin: 14px 0; padding: 12px 14px;
            border: 1px solid #E5E7EB; border-left: 4px solid #0f5132;
            border-radius: 4px;
        }
        .section h3 {
            margin: 0 0 8px 0; font-size: 11pt; color: #0f5132; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        .section p { margin: 4px 0; font-size: 10.5pt; color: #111827; }
        .section .empty { color: #9CA3AF; font-style: italic; }

        .signatures { display: flex; gap: 80px; margin-top: 60px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 240px; }
        .sign-line .role { font-weight: 700; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }
        .stamp-box {
            margin-top: 50px; padding-top: 8px; text-align: center;
            border: 1px dashed #9CA3AF; border-radius: 6px;
            width: 200px; height: 100px; margin-left: auto; margin-right: 40px;
            display: flex; align-items: center; justify-content: center;
            color: #9CA3AF; font-size: 9pt; font-style: italic;
        }

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

    @if($certificate->status === 'Draft')
    <div class="watermark">DRAFT</div>
    @endif

    <div class="print-buttons no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('medical-certificates.pdf', $certificate) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/medical-certificates/' . $certificate->id) }}">← Back</a>
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
                <p>Medical Records Department &middot; Nasarawa State, Nigeria</p>
            </div>
        </div>

        <h1 class="certificate-title">{{ $certificate->certificate_type }}</h1>
        <p class="certificate-subtitle">
            Issued under the authority of the Medical Director, FUTH Lafia
        </p>

        <div class="meta-row">
            <div class="left">
                <strong>Certificate #:</strong> {{ $certificate->certificate_number }}<br>
                <strong>Patient Category:</strong> {{ $certificate->patient_category }}
            </div>
            <div class="right">
                <strong>Issued:</strong> {{ $certificate->issued_date?->format('d M Y') }}<br>
                @if($certificate->valid_from && $certificate->valid_until)
                <strong>Valid:</strong> {{ $certificate->valid_from->format('d M Y') }} — {{ $certificate->valid_until->format('d M Y') }}
                @endif
            </div>
        </div>

        <table class="patient-block">
            <tr>
                <td class="label">Patient Name</td>
                <td class="value">{{ $certificate->patient->full_name }}</td>
                <td class="label">Hospital #</td>
                <td class="value">{{ $certificate->patient->hospital_no }}</td>
            </tr>
            <tr>
                <td class="label">Age / Sex</td>
                <td class="value">{{ $certificate->patient->age ?? '—' }} / {{ ucfirst($certificate->patient->gender ?? '') }}</td>
                <td class="label">Phone</td>
                <td class="value">{{ $certificate->patient->phonemain }}</td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td class="value" colspan="3">{{ $certificate->patient->address }}</td>
            </tr>
        </table>

        @if($certificate->diagnosis)
        <div class="section">
            <h3>Diagnosis</h3>
            <p>{{ $certificate->diagnosis }}</p>
        </div>
        @endif

        @if($certificate->clinical_findings)
        <div class="section">
            <h3>Clinical Findings</h3>
            <p>{{ $certificate->clinical_findings }}</p>
        </div>
        @endif

        @if($certificate->treatment_given)
        <div class="section">
            <h3>Treatment Given</h3>
            <p>{{ $certificate->treatment_given }}</p>
        </div>
        @endif

        @if($certificate->certificate_type === 'Sick Leave' && $certificate->rest_days)
        <div class="section">
            <h3>Sick Leave Recommendation</h3>
            <p>The above-named patient is advised to take <strong>{{ $certificate->rest_days }} day(s)</strong> off work to recover, effective from <strong>{{ $certificate->valid_from?->format('d M Y') ?? $certificate->issued_date->format('d M Y') }}</strong>.</p>
        </div>
        @endif

        @if($certificate->recommendations)
        <div class="section">
            <h3>Recommendations</h3>
            <p>{{ $certificate->recommendations }}</p>
        </div>
        @endif

        @if($certificate->restrictions)
        <div class="section">
            <h3>Activity Restrictions</h3>
            <p>{{ $certificate->restrictions }}</p>
        </div>
        @endif

        <div class="signatures">
            <div class="sign-line">
                <div class="role">Issuing Medical Officer</div>
                <div class="name">
                    {{ $certificate->issuer?->name ?? '_________________________' }}<br>
                    Name &amp; Signature<br>
                    Date: {{ $certificate->issued_date?->format('d M Y') ?? '___ / ___ / _____' }}
                </div>
            </div>
            <div class="sign-line">
                <div class="role">Medical Director</div>
                <div class="name">
                    _________________________<br>
                    FUTH Lafia<br>
                    Date: ___ / ___ / _____
                </div>
            </div>
        </div>

        <div class="stamp-box">Hospital Stamp</div>

        <div class="footer">
            This certificate is issued by FUTH Lafia EMR. Certificate ID: {{ $certificate->certificate_number }} &middot;
            Generated {{ now()->format('d M Y, H:i') }}
            @if($certificate->status === 'Draft')
                <br><strong style="color:#B91C1C;">This is a DRAFT and not valid until issued and stamped.</strong>
            @endif
        </div>
    </div>
</body>
</html>
