<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportingTableExportController extends Controller
{
    /**
     * Export admissions reporting table to Excel (HTML-table-based .xls file).
     * Excel opens this natively with all formatting preserved.
     */
    public function export(Request $request): Response
    {
        $query = $this->buildQuery($request);
        $admissions = $query->get();

        $html = $this->buildHtml($admissions);

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Admissions-Report-' . now()->format('Y-m-d') . '.xls"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export as CSV (plain text, for systems that prefer CSV).
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = $this->buildQuery($request);
        $admissions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Admissions-Report-' . now()->format('Y-m-d') . '.csv"',
            'Cache-Control' => 'max-age=0',
        ];

        $callback = function () use ($admissions) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'Admission #', 'Patient Name', 'Patient Code', 'Sex', 'Age', 'Phone',
                'Date of Admission', 'Date of Discharge', 'Length of Stay (days)',
                'Admission Diagnosis', 'Discharge Diagnosis', 'Reason for Discharge',
                'Discharge Summary', 'Status', 'Ward', 'Bed',
                'Admitting Doctor', 'Attending Doctor', 'Resident Doctor', 'Discharged By',
                'Admission Type', 'Admission Source',
            ]);

            // Data rows
            foreach ($admissions as $a) {
                fputcsv($file, [
                    $a->admission_number,
                    $a->patient?->full_name,
                    $a->patient?->hospital_no,
                    $a->patient?->gender,
                    $a->patient?->age,
                    $a->patient?->phonemain,
                    $a->admission_date?->format('d M Y'),
                    $a->discharge_date?->format('d M Y'),
                    $a->lengthOfStayDays(),
                    $a->admission_diagnosis,
                    $a->discharge_diagnosis,
                    $a->discharge_reason,
                    $a->discharge_summary,
                    $a->status,
                    $a->ward?->name,
                    $a->bed?->bed_number,
                    $a->admittingDoctor?->name,
                    $a->attendingDoctor?->name,
                    $a->residentDoctor?->name,
                    $a->dischargedBy?->name,
                    $a->admission_type,
                    $a->admission_source,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Build the query from request filters.
     */
    private function buildQuery(Request $request)
    {
        $query = Admission::with([
            'patient', 'ward', 'bed',
            'admittingDoctor', 'attendingDoctor', 'residentDoctor', 'dischargedBy',
        ])->latest('admission_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('admission_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('admission_date', '<=', $request->to);
        }
        if ($request->filled('discharge_from')) {
            $query->whereDate('discharge_date', '>=', $request->discharge_from);
        }
        if ($request->filled('discharge_to')) {
            $query->whereDate('discharge_date', '<=', $request->discharge_to);
        }

        return $query;
    }

    /**
     * Build the HTML table for Excel.
     */
    private function buildHtml($admissions): string
    {
        $rows = '';
        foreach ($admissions as $a) {
            $los = $a->lengthOfStayDays();
            $statusColor = match ($a->status) {
                'Admitted' => '#FEF3C7',
                'Discharged' => '#D1FAE5',
                'Death' => '#FEE2E2',
                default => '#F3F4F6',
            };
            $rows .= <<<HTML
            <tr>
                <td style="font-weight:bold">{$a->admission_number}</td>
                <td>{$this->esc($a->patient?->full_name)}</td>
                <td>{$this->esc($a->patient?->hospital_no)}</td>
                <td>{$this->esc($a->patient?->gender)}</td>
                <td>{$this->esc($a->patient?->age)}</td>
                <td>{$this->esc($a->patient?->phonemain)}</td>
                <td>{$a->admission_date?->format('d M Y')}</td>
                <td>{$a->discharge_date?->format('d M Y')}</td>
                <td style="text-align:center">{$los}</td>
                <td>{$this->esc($a->admission_diagnosis)}</td>
                <td>{$this->esc($a->discharge_diagnosis)}</td>
                <td>{$this->esc($a->discharge_reason)}</td>
                <td>{$this->esc($a->discharge_summary)}</td>
                <td style="background:{$statusColor};text-align:center;font-weight:bold">{$a->status}</td>
                <td>{$this->esc($a->ward?->name)}</td>
                <td>{$this->esc($a->bed?->bed_number)}</td>
                <td>{$this->esc($a->admittingDoctor?->name)}</td>
                <td>{$this->esc($a->attendingDoctor?->name)}</td>
                <td>{$this->esc($a->residentDoctor?->name)}</td>
                <td>{$this->esc($a->dischargedBy?->name)}</td>
                <td>{$this->esc($a->admission_type)}</td>
                <td>{$this->esc($a->admission_source)}</td>
            </tr>
HTML;
        }

        $date = now()->format('d M Y, H:i');
        $count = $admissions->count();

        return <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta charset="UTF-8">
<style>
    table { border-collapse: collapse; font-family: Calibri, Arial, sans-serif; font-size: 10pt; }
    th { background: #0f5132; color: white; border: 1px solid #0f5132; padding: 6px 8px; text-align: left; font-weight: 600; }
    td { border: 1px solid #D1D5DB; padding: 4px 8px; }
    .title { font-size: 14pt; font-weight: bold; color: #0f5132; margin-bottom: 4px; }
    .subtitle { font-size: 10pt; color: #6B7280; margin-bottom: 12px; }
</style>
</head>
<body>
<div class="title">FUTH Lafia EMR — Admissions Report</div>
<div class="subtitle">Generated: {$date} &middot; Total records: {$count}</div>
<table>
    <thead>
        <tr>
            <th>Admission #</th>
            <th>Patient Name</th>
            <th>Hospital No</th>
            <th>Sex</th>
            <th>Age</th>
            <th>Phone</th>
            <th>Date of Admission</th>
            <th>Date of Discharge</th>
            <th>LOS (days)</th>
            <th>Admission Diagnosis</th>
            <th>Discharge Diagnosis</th>
            <th>Reason for Discharge</th>
            <th>Discharge Summary</th>
            <th>Status</th>
            <th>Ward</th>
            <th>Bed</th>
            <th>Admitting Doctor</th>
            <th>Attending Doctor</th>
            <th>Resident Doctor</th>
            <th>Discharged By</th>
            <th>Admission Type</th>
            <th>Admission Source</th>
        </tr>
    </thead>
    <tbody>
        {$rows}
    </tbody>
</table>
</body>
</html>
HTML;
    }

    private function esc(?string $v): string
    {
        return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
    }
}
