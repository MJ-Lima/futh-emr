<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MortalityReviewExportController extends Controller
{
    public function export(Request $request): Response
    {
        $deaths = $this->buildQuery();
        $rows = '';
        foreach ($deaths as $d) {
            $rows .= '<tr>';
            $rows .= '<td>' . htmlspecialchars($d->admission_number) . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->patient?->full_name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->patient?->hospital_no ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->patient?->gender ?? '—') . '</td>';
            $rows .= '<td>' . ($d->patient?->age ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->ward?->name ?? '—') . '</td>';
            $rows .= '<td>' . $d->admission_date?->format('d M Y') . '</td>';
            $rows .= '<td>' . $d->discharge_date?->format('d M Y') . '</td>';
            $rows .= '<td>' . $d->lengthOfStayDays() . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->admission_diagnosis ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->discharge_diagnosis ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->discharge_reason ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->attendingDoctor?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($d->admission_type) . '</td>';
            $rows .= '</tr>';
        }
        $date = now()->format('d M Y, H:i');
        $count = $deaths->count();
        $html = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"><head><meta charset=\"UTF-8\"><style>table{border-collapse:collapse;font-family:Calibri;font-size:10pt}th{background:#0f5132;color:white;border:1px solid #0f5132;padding:6px}td{border:1px solid #D1D5DB;padding:4px}</style></head><body><div style=\"font-size:14pt;font-weight:bold;color:#0f5132\">FUTH Lafia EMR — Mortality Review Report</div><div style=\"font-size:10pt;color:#6B7280\">Generated: {$date} &middot; Total deaths: {$count}</div><table><thead><tr><th>Admission #</th><th>Patient</th><th>Code</th><th>Sex</th><th>Age</th><th>Ward</th><th>Admitted</th><th>Died</th><th>LOS (d)</th><th>Admission Dx</th><th>Discharge Dx</th><th>Reason</th><th>Attending Dr</th><th>Type</th></tr></thead><tbody>{$rows}</tbody></table></body></html>";
        return response($html, 200, ['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Mortality-Review-' . now()->format('Y-m-d') . '.xls"']);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $deaths = $this->buildQuery();
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Mortality-' . now()->format('Y-m-d') . '.csv"'];
        $callback = function () use ($deaths) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Admission #', 'Patient', 'Code', 'Sex', 'Age', 'Ward', 'Admitted', 'Died', 'LOS (days)', 'Admission Dx', 'Discharge Dx', 'Reason', 'Attending Dr', 'Type']);
            foreach ($deaths as $d) {
                fputcsv($file, [$d->admission_number, $d->patient?->full_name, $d->patient?->hospital_no, $d->patient?->gender, $d->patient?->age, $d->ward?->name, $d->admission_date?->format('d M Y'), $d->discharge_date?->format('d M Y'), $d->lengthOfStayDays(), $d->admission_diagnosis, $d->discharge_diagnosis, $d->discharge_reason, $d->attendingDoctor?->name, $d->admission_type]);
            }
            fclose($file);
        };
        return new StreamedResponse($callback, 200, $headers);
    }

    private function buildQuery()
    {
        return Admission::where('status', 'Death')->with(['patient', 'ward', 'attendingDoctor'])->latest('discharge_date')->get();
    }
}
