<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>NHIA Claim — {{ $claim->claim_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; color: #111827; font-size: 11pt; line-height: 1.45; margin: 0; }
        .letterhead { display: flex; align-items: center; gap: 18px; padding-bottom: 12px; border-bottom: 3px solid #0f5132; margin-bottom: 14px; }
        .emblem { width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 { margin: 0; font-size: 18pt; color: #0f5132; font-weight: 700; }
        .letterhead-text h2 { margin: 0; font-size: 11pt; color: #374151; font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px; }
        .letterhead-text p { margin: 2px 0 0; font-size: 9.5pt; color: #6B7280; }

        .claim-meta { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; padding: 10px 14px; background: #F0FDF4; border-left: 4px solid #0f5132; border-radius: 4px; }
        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 14px; }
        .patient-block td { padding: 7px 10px; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 700; color: #6B7280; font-size: 9pt; text-transform: uppercase; width: 22%; background: #F9FAFB; }

        h3.section { background: #0f5132; color: white; padding: 6px 10px; margin: 14px 0 0; font-size: 10pt; font-weight: 600; border-radius: 4px 4px 0 0; }
        .section-body { border: 1px solid #E5E7EB; border-top: none; padding: 10px 12px; margin-bottom: 14px; border-radius: 0 0 4px 4px; }

        table.claim-lines { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.claim-lines th { background: #ECFDF5; padding: 6px 8px; text-align: left; font-size: 9.5pt; font-weight: 600; color: #064E3B; border-bottom: 2px solid #0f5132; }
        table.claim-lines td { padding: 6px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10pt; }
        .total-row { font-weight: 700; background: #F0FDF4; }

        .signatures { display: flex; gap: 60px; margin-top: 40px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 240px; }
        .sign-line .role { font-weight: 700; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }

        .status-badge { display: inline-block; padding: 3px 12px; border-radius: 4px; font-size: 10pt; font-weight: 600; }
        .status-Draft { background: #FEF3C7; color: #92400E; }
        .status-Submitted { background: #DBEAFE; color: #1E40AF; }
        .status-Approved { background: #D1FAE5; color: #065F46; }
        .status-Paid { background: #D1FAE5; color: #065F46; }
        .status-Rejected { background: #FEE2E2; color: #991B1B; }

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
        <a href="{{ route('insurance-claims.pdf', $claim) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/insurance-claims/' . $claim->id) }}">← Back</a>
    </div>

    <div class="letterhead">
        <div class="emblem"><svg viewBox="0 0 60 60"><rect x="22" y="14" width="16" height="32" rx="2" fill="#0f5132"/><rect x="14" y="22" width="32" height="16" rx="2" fill="#0f5132"/></svg></div>
        <div class="letterhead-text">
            <h1>FEDERAL UNIVERSITY TEACHING HOSPITAL, LAFIA</h1>
            <h2>HEALTH IS WEALTH</h2>
            <p>Insurance / NHIA Claims Department &middot; Nasarawa State, Nigeria</p>
        </div>
    </div>

    <div class="claim-meta">
        <div>
            <strong>Claim #:</strong> {{ $claim->claim_number }}<br>
            <strong>Type:</strong> {{ $claim->claim_type }}<br>
            <strong>Status:</strong> <span class="status-badge status-{{ str_replace(' ', '', $claim->status) }}">{{ $claim->status }}</span>
        </div>
        <div style="text-align:right">
            <strong>Service Dates:</strong> {{ $claim->service_date_start?->format('d M Y') }} — {{ $claim->service_date_end?->format('d M Y') }}<br>
            <strong>Submitted:</strong> {{ $claim->submitted_at?->format('d M Y, H:i') ?? 'Not yet' }}<br>
            <strong>Approved:</strong> {{ $claim->approved_at?->format('d M Y') ?? '—' }}<br>
            <strong>Paid:</strong> {{ $claim->paid_at?->format('d M Y') ?? '—' }}
        </div>
    </div>

    <table class="patient-block">
        <tr>
            <td class="label">Patient</td>
            <td>{{ $claim->patient->full_name }}</td>
            <td class="label">Hospital No</td>
            <td>{{ $claim->patient->hospital_no }}</td>
        </tr>
        <tr>
            <td class="label">Age / Sex</td>
            <td>{{ $claim->patient->age ?? '—' }} / {{ ucfirst($claim->patient->gender ?? '') }}</td>
            <td class="label">Phone</td>
            <td>{{ $claim->patient->phonemain }}</td>
        </tr>
        <tr>
            <td class="label">Insurance Provider</td>
            <td>{{ $claim->policy?->provider?->name ?? $claim->insuranceProvider?->name ?? '—' }}</td>
            <td class="label">Plan</td>
            <td>{{ $claim->policy?->plan?->name ?? $claim->insurancePlan?->name ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Policy Number</td>
            <td>{{ $claim->policy?->policy_number ?? '—' }}</td>
            <td class="label">Enrollee Code</td>
            <td>{{ $claim->policy?->enrollee_code ?? '—' }}</td>
        </tr>
        @if($claim->diagnosis_description)
        <tr>
            <td class="label">Diagnosis (ICD-10)</td>
            <td colspan="3">{{ $claim->diagnosis_icd10 ?? '—' }} — {{ $claim->diagnosis_description }}</td>
        </tr>
        @endif
        @if($claim->treatment_summary)
        <tr>
            <td class="label">Treatment Summary</td>
            <td colspan="3">{{ $claim->treatment_summary }}</td>
        </tr>
        @endif
    </table>

    {{-- Claim Lines --}}
    @if($claim->lines->isNotEmpty())
    <h3 class="section">Claim Line Items</h3>
    <div class="section-body" style="padding: 0;">
        <table class="claim-lines">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Type</th>
                    <th>Service Name / Description</th>
                    <th style="text-align:center">Qty</th>
                    <th style="text-align:right">Unit Price (₦)</th>
                    <th style="text-align:right">Amount Claimed (₦)</th>
                    <th style="text-align:right">Approved (₦)</th>
                    <th style="text-align:center">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $totalClaimed = 0; $totalApproved = 0; @endphp
                @foreach($claim->lines as $i => $line)
                @php
                    $totalClaimed += (float) $line->amount_claimed;
                    $totalApproved += (float) $line->amount_approved;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $line->service_type }}</td>
                    <td>{{ $line->service_name }}{{ $line->notes ? ' — <em style="color:#6B7280">' . $line->notes . '</em>' : '' }}</td>
                    <td style="text-align:center">{{ $line->quantity }}</td>
                    <td style="text-align:right">{{ number_format((float) $line->unit_price, 2) }}</td>
                    <td style="text-align:right">{{ number_format((float) $line->amount_claimed, 2) }}</td>
                    <td style="text-align:right">{{ number_format((float) $line->amount_approved, 2) }}</td>
                    <td style="text-align:center"><span class="status-badge status-{{ str_replace(' ', '', $line->status) }}">{{ $line->status }}</span></td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="5" style="text-align:right;font-weight:700">TOTAL</td>
                    <td style="text-align:right;font-weight:700">₦{{ number_format($totalClaimed, 2) }}</td>
                    <td style="text-align:right;font-weight:700">₦{{ number_format($totalApproved, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Financial summary --}}
    <h3 class="section">Financial Summary</h3>
    <div class="section-body">
        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; font-weight: 600;">Total Claimed</td>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; text-align: right;">₦{{ number_format((float) $claim->total_claimed, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; font-weight: 600;">Total Approved</td>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; text-align: right;">₦{{ number_format((float) $claim->total_approved, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; font-weight: 600;">Total Paid</td>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; text-align: right;">₦{{ number_format((float) $claim->total_paid, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; font-weight: 600;">Patient Copay</td>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; text-align: right;">₦{{ number_format((float) $claim->patient_copay, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; font-weight: 600;">Deduction</td>
                <td style="padding: 6px 10px; border: 1px solid #E5E7EB; text-align: right;">₦{{ number_format((float) $claim->deduction, 2) }}</td>
            </tr>
            @if($claim->rejection_reason)
            <tr>
                <td style="padding: 6px 10px; border: 1px solid #FEE2E2; font-weight: 600; color: #991B1B;">Rejection Reason</td>
                <td style="padding: 6px 10px; border: 1px solid #FEE2E2; color: #991B1B;">{{ $claim->rejection_reason }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    @if($claim->notes)
    <h3 class="section">Notes</h3>
    <div class="section-body"><p>{{ $claim->notes }}</p></div>
    @endif

    <div class="signatures">
        <div class="sign-line">
            <div class="role">Prepared By (Claims Officer)</div>
            <div class="name">_________________________<br>Date: ___ / ___ / _____</div>
        </div>
        <div class="sign-line">
            <div class="role">Medical Director (Approval)</div>
            <div class="name">_________________________<br>Date: ___ / ___ / _____</div>
        </div>
        <div class="sign-line">
            <div class="role">Insurance Provider (Acknowledgement)</div>
            <div class="name">_________________________<br>Date: ___ / ___ / _____</div>
        </div>
    </div>

    <div class="footer">Claim ID: {{ $claim->claim_number }} &middot; Generated {{ now()->format('d M Y, H:i') }} from FUTH Lafia EMR</div>
</body>
</html>
