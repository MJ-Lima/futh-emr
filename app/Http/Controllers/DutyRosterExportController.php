<?php

namespace App\Http\Controllers;

use App\Models\DutyPosting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DutyRosterExportController extends Controller
{
    public function export(Request $request): Response
    {
        $postings = DutyPosting::with(['staff', 'user', 'hospital', 'department', 'clinic'])
            ->where('start_date', '<=', now()->addDays(30))
            ->where('end_date', '>=', now()->subDays(7))
            ->orderBy('start_date')->get();

        $rows = '';
        foreach ($postings as $p) {
            $rows .= '<tr>';
            $rows .= '<td>' . htmlspecialchars(($p->staff?->first_name . ' ' . $p->staff?->last_name) ?? $p->user?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->hospital?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->department?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->clinic?->name ?? '—') . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->shift) . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->role_in_unit) . '</td>';
            $rows .= '<td>' . $p->start_date?->format('d M Y') . '</td>';
            $rows .= '<td>' . $p->end_date?->format('d M Y') . '</td>';
            $rows .= '<td>' . htmlspecialchars($p->status) . '</td>';
            $rows .= '</tr>';
        }
        $date = now()->format('d M Y, H:i');
        $html = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"><head><meta charset=\"UTF-8\"><style>table{border-collapse:collapse;font-family:Calibri;font-size:10pt}th{background:#0f5132;color:white;border:1px solid #0f5132;padding:6px}td{border:1px solid #D1D5DB;padding:4px}</style></head><body><div style=\"font-size:14pt;font-weight:bold;color:#0f5132\">FUTH Lafia EMR — Staff Duty Roster</div><div style=\"font-size:10pt;color:#6B7280\">Generated: {$date}</div><table><thead><tr><th>Staff Name</th><th>Hospital</th><th>Department</th><th>Clinic</th><th>Shift</th><th>Role</th><th>Start Date</th><th>End Date</th><th>Status</th></tr></thead><tbody>{$rows}</tbody></table></body></html>";
        return response($html, 200, ['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Duty-Roster-' . now()->format('Y-m-d') . '.xls"']);
    }
}
