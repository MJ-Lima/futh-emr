<?php

namespace App\Http\Controllers;

use App\Models\DispensingLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DispensingLogExportController extends Controller
{
    public function export(Request $request): Response
    {
        $logs = DispensingLog::with(['patient', 'drug', 'drugBatch', 'dispensingPoint', 'user'])
            ->latest('dispensed_at')->get();

        $rows = '';
        foreach ($logs as $log) {
            $rows .= '<tr>';
            $rows .= '<td>' . htmlspecialchars($log->dispensed_at?->format('d M Y, H:i') ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->patient?->full_name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->patient?->hospital_no ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->drug?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->drugBatch?->batch_number ?? '—') . '</td>';
            $rows .= '<td style="text-align:center">' . $log->quantity_dispensed . '</td>';
            $rows .= '<td style="text-align:center">' . $log->balance_after . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->dispensingPoint?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->user?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($log->dosage_instructions ?? '—') . '</td>';
            $rows .= '</tr>';
        }

        $date = now()->format('d M Y, H:i');
        $count = $logs->count();
        $html = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
<head><meta charset=\"UTF-8\"><style>table{border-collapse:collapse;font-family:Calibri;font-size:10pt}th{background:#0f5132;color:white;border:1px solid #0f5132;padding:6px 8px}td{border:1px solid #D1D5DB;padding:4px 8px}</style></head>
<body>
<div style=\"font-size:14pt;font-weight:bold;color:#0f5132\">FUTH Lafia EMR — Pharmacy Dispensing Log Report</div>
<div style=\"font-size:10pt;color:#6B7280\">Generated: {$date} &middot; Total records: {$count}</div>
<table><thead><tr>
<th>Date/Time</th><th>Patient</th><th>Hospital No</th><th>Drug</th><th>Batch #</th>
<th>Qty Dispensed</th><th>Balance After</th><th>Dispensing Point</th><th>Dispensed By</th><th>Instructions</th>
</tr></thead><tbody>{$rows}</tbody></table>
</body></html>";

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Dispensing-Log-' . now()->format('Y-m-d') . '.xls"',
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $logs = DispensingLog::with(['patient', 'drug', 'drugBatch', 'dispensingPoint', 'user'])
            ->latest('dispensed_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Dispensing-Log-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Date/Time', 'Patient Name', 'Patient Code', 'Drug', 'Batch #', 'Qty Dispensed', 'Balance After', 'Dispensing Point', 'Dispensed By', 'Instructions']);
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->dispensed_at?->format('d M Y, H:i'),
                    $log->patient?->full_name,
                    $log->patient?->hospital_no,
                    $log->drug?->name,
                    $log->drugBatch?->batch_number,
                    $log->quantity_dispensed,
                    $log->balance_after,
                    $log->dispensingPoint?->name,
                    $log->user?->name,
                    $log->dosage_instructions,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
