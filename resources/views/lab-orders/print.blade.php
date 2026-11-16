<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Report — {{ $order->order_number }}</title>
    <style>
        @page { margin: 18mm 14mm; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827;
            font-size: 11pt;
            line-height: 1.45;
            margin: 0;
        }
        .letterhead {
            display: flex; align-items: center; gap: 18px;
            padding-bottom: 12px; border-bottom: 3px solid #0f5132;
            margin-bottom: 14px;
        }
        .emblem {
            width: 70px; height: 70px; border: 2px solid #0f5132; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .emblem svg { width: 44px; height: 44px; }
        .letterhead-text h1 {
            margin: 0; font-size: 18pt; color: #0f5132;
            font-weight: 700; letter-spacing: 0.5px;
        }
        .letterhead-text h2 {
            margin: 0; font-size: 11pt; color: #374151;
            font-weight: 600; text-transform: uppercase; letter-spacing: 1.4px;
        }
        .letterhead-text p { margin: 2px 0 0 0; font-size: 9.5pt; color: #6B7280; }

        .report-meta {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 12px; padding: 8px 12px; background: #F0FDF4; border-left: 4px solid #0f5132;
        }
        .report-meta .left { font-size: 11pt; }
        .report-meta .right { text-align: right; font-size: 10pt; color: #374151; }

        .patient-block { width: 100%; border: 1px solid #D1D5DB; border-collapse: collapse; margin-bottom: 14px; }
        .patient-block td { padding: 6px 10px; vertical-align: top; border: 1px solid #E5E7EB; }
        .patient-block .label { font-weight: 600; color: #6B7280; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.5px; width: 18%; background: #F9FAFB; }
        .patient-block .value { color: #111827; }

        h3.section {
            background: #0f5132; color: white; padding: 6px 10px;
            margin: 14px 0 0 0; font-size: 10pt; font-weight: 600;
            border-radius: 4px 4px 0 0;
        }
        table.results { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        table.results th {
            background: #ECFDF5; padding: 6px 8px; text-align: left;
            font-size: 9.5pt; font-weight: 600; color: #064E3B;
            border-bottom: 2px solid #0f5132;
        }
        table.results td { padding: 6px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10pt; }

        .flag-Normal { color: #15803D; font-weight: 600; }
        .flag-Low, .flag-High { color: #D97706; font-weight: 600; }
        .flag-Critical-Low, .flag-Critical-High, .flag-Abnormal { color: #B91C1C; font-weight: 700; }

        .signatures { display: flex; gap: 60px; margin-top: 36px; padding-top: 18px; flex-wrap: wrap; }
        .sign-line { border-top: 1px solid #374151; padding-top: 4px; font-size: 10pt; min-width: 220px; }
        .sign-line .role { font-weight: 600; color: #111827; }
        .sign-line .name { color: #6B7280; font-size: 9pt; margin-top: 2px; }

        .footer {
            margin-top: 24px; padding-top: 10px; border-top: 1px solid #E5E7EB;
            text-align: center; font-size: 8.5pt; color: #6B7280;
        }

        .watermark {
            position: fixed; top: 35%; left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80pt; font-weight: 800; color: rgba(220, 38, 38, 0.18);
            letter-spacing: 12px; z-index: 0; pointer-events: none; white-space: nowrap;
        }
        .content { position: relative; z-index: 1; }

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
        .print-buttons a:hover, .print-buttons button:hover { background: #064E3B; }
    </style>
</head>
<body>

    @if($order->status !== 'Verified')
    <div class="watermark">UNVERIFIED</div>
    @endif

    <div class="print-buttons no-print">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('lab-orders.pdf', $order) }}">⬇ Download PDF</a>
        <a href="{{ url('/admin/lab-orders/' . $order->id) }}">← Back</a>
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
                <p>Department of Medical Laboratory Services &middot; Nasarawa State, Nigeria</p>
            </div>
        </div>

        <div class="report-meta">
            <div class="left">
                <strong>Lab Order #:</strong> {{ $order->order_number }}<br>
                <strong>Lab:</strong> {{ $order->destinationLab?->name ?? '—' }}
            </div>
            <div class="right">
                <strong>Ordered:</strong> {{ $order->ordered_at?->format('d M Y, H:i') }}<br>
                <strong>Collected:</strong> {{ $order->collected_at?->format('d M Y, H:i') ?? '—' }}<br>
                <strong>Reported:</strong> {{ $order->resulted_at?->format('d M Y, H:i') ?? '—' }}
            </div>
        </div>

        <table class="patient-block">
            <tr>
                <td class="label">Patient</td>
                <td class="value">{{ $order->patient->full_name }}</td>
                <td class="label">Hospital #</td>
                <td class="value">{{ $order->patient->hospital_no }}</td>
            </tr>
            <tr>
                <td class="label">Age / Sex</td>
                <td class="value">{{ $order->patient->age ?? '—' }} / {{ ucfirst($order->patient->gender) }}</td>
                <td class="label">Phone</td>
                <td class="value">{{ $order->patient->phonemain }}</td>
            </tr>
            <tr>
                <td class="label">Specimen</td>
                <td class="value">{{ $order->specimen_source ?? '—' }}</td>
                <td class="label">Fasting</td>
                <td class="value">{{ $order->fasting_status ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Clinical Notes</td>
                <td class="value" colspan="3">{{ $order->clinical_notes ?? '—' }}</td>
            </tr>
        </table>

        @php
            $byUnit = $order->items->groupBy(fn($i) => $i->unit?->name ?? 'General');
        @endphp

        @foreach($byUnit as $unitName => $items)
            <h3 class="section">{{ $unitName }}</h3>
            <table class="results">
                <thead>
                    <tr>
                        <th>Test</th>
                        <th>Result</th>
                        <th>Unit</th>
                        <th>Reference Range</th>
                        <th>Flag</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $result = $item->result;
                            $flagClass = 'flag-' . str_replace(' ', '-', $result?->flag ?? 'Normal');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $item->test_name }}</strong>
                                @if($item->loinc_code)
                                    <div style="font-size:8pt;color:#6B7280;">LOINC: {{ $item->loinc_code }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $result?->value ?? '—' }}
                                @if($result?->interpretation)
                                    <div style="font-size:9pt;color:#6B7280;font-style:italic;">{{ $result->interpretation }}</div>
                                @endif
                            </td>
                            <td>{{ $result?->unit ?? '—' }}</td>
                            <td>{{ $result?->reference_range ?? '—' }}</td>
                            <td class="{{ $flagClass }}">{{ $result?->flag ?? '—' }}</td>
                        </tr>
                        @if($result?->comments)
                            <tr>
                                <td colspan="5" style="font-size:9pt;color:#374151;font-style:italic;padding-left:20px;">
                                    Comment: {{ $result->comments }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        @endforeach

        <div class="signatures">
            <div class="sign-line">
                <div class="role">Scientist Signature</div>
                <div class="name">
                    @php
                        $performer = $order->items->pluck('result.performer')->filter()->first();
                    @endphp
                    {{ $performer?->name ?? '_________________________' }}
                    <br>Date: {{ $order->items->pluck('result.performed_at')->filter()->first()?->format('d M Y') ?? '___ / ___ / _____' }}
                </div>
            </div>
            <div class="sign-line">
                <div class="role">Verified By (Senior Scientist)</div>
                <div class="name">
                    @if($order->status === 'Verified')
                        @php
                            $verifier = $order->items->pluck('result.verifier')->filter()->first();
                        @endphp
                        {{ $verifier?->name ?? '_________________________' }}
                        <br>Date: {{ $order->resulted_at?->format('d M Y') ?? '___ / ___ / _____' }}
                    @else
                        _________________________<br>Date: ___ / ___ / _____
                    @endif
                </div>
            </div>
        </div>

        <div class="footer">
            Computer-generated report from FUTH Lafia EMR. Report ID: {{ $order->order_number }} &middot;
            Generated {{ now()->format('d M Y, H:i') }}
            @if($order->status !== 'Verified')
                <br><strong style="color:#B91C1C;">This report is UNVERIFIED and not valid for clinical use until signed by a senior scientist.</strong>
            @endif
        </div>
    </div>
</body>
</html>
