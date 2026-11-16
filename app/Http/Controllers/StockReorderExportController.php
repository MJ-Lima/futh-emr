<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockReorderExportController extends Controller
{
    /**
     * Export low-stock + out-of-stock drugs to Excel (.xls HTML).
     */
    public function export(Request $request): Response
    {
        $data = $this->buildData();
        $html = $this->buildHtml($data);

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Stock-Reorder-Report-' . now()->format('Y-m-d') . '.xls"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $data = $this->buildData();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Stock-Reorder-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Status', 'Drug Name', 'Generic Name', 'Form', 'Strength', 'Current Stock', 'Reorder Level', 'Reorder Qty', 'Cost Price (₦)', 'Est. Reorder Cost (₦)', 'Supplier', 'Manufacturer']);

            foreach ($data['all'] as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    private function buildData(): array
    {
        $drugs = Drug::where('status', true)->with(['batches' => function ($q) {
            $q->where('quantity_remaining', '>', 0)->where('is_quarantined', false);
        }])->get();

        $all = [];
        foreach ($drugs as $drug) {
            $stock = $drug->totalStock();
            $reorderLevel = (float) $drug->reorder_level;
            if ($stock <= $reorderLevel) {
                $status = $stock <= 0 ? 'OUT OF STOCK' : 'LOW STOCK';
                $all[] = [
                    $status,
                    $drug->name,
                    $drug->generic_name ?? '—',
                    $drug->dosage_form ?? '—',
                    $drug->strength ?? '—',
                    $stock,
                    $reorderLevel,
                    (float) $drug->reorder_quantity,
                    number_format((float) $drug->cost_price, 2),
                    number_format((float) $drug->reorder_quantity * (float) $drug->cost_price, 2),
                    $drug->supplier ?? '—',
                    $drug->manufacturer ?? '—',
                ];
            }
        }

        return ['all' => $all];
    }

    private function buildHtml(array $data): string
    {
        $rows = '';
        foreach ($data['all'] as $r) {
            $bgColor = $r[0] === 'OUT OF STOCK' ? '#FEE2E2' : '#FEF3C7';
            $rows .= "<tr><td style='background:{$bgColor};font-weight:bold;text-align:center'>{$r[0]}</td>";
            for ($i = 1; $i < count($r); $i++) {
                $rows .= "<td>" . htmlspecialchars((string)$r[$i]) . "</td>";
            }
            $rows .= "</tr>";
        }

        $date = now()->format('d M Y, H:i');
        $count = count($data['all']);
        return "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
<head><meta charset=\"UTF-8\"><style>
table { border-collapse: collapse; font-family: Calibri; font-size: 10pt; }
th { background: #0f5132; color: white; border: 1px solid #0f5132; padding: 6px 8px; }
td { border: 1px solid #D1D5DB; padding: 4px 8px; }
</style></head>
<body>
<div style=\"font-size:14pt; font-weight:bold; color:#0f5132;\">FUTH Lafia EMR — Stock Reorder Report</div>
<div style=\"font-size:10pt; color:#6B7280;\">Generated: {$date} &middot; Total items needing reorder: {$count}</div>
<table>
<thead><tr>
<th>Status</th><th>Drug Name</th><th>Generic Name</th><th>Form</th><th>Strength</th>
<th>Current Stock</th><th>Reorder Level</th><th>Reorder Qty</th>
<th>Cost Price (₦)</th><th>Est. Reorder Cost (₦)</th><th>Supplier</th><th>Manufacturer</th>
</tr></thead>
<tbody>{$rows}</tbody>
</table>
</body></html>";
    }
}
