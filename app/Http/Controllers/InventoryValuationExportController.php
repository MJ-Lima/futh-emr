<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryValuationExportController extends Controller
{
    public function export(Request $request): Response
    {
        $items = $this->buildData();
        $rows = '';
        foreach ($items as $item) {
            $rows .= '<tr>';
            $rows .= '<td>' . htmlspecialchars($item['name']) . '</td>';
            $rows .= '<td>' . htmlspecialchars($item['generic']) . '</td>';
            $rows .= '<td>' . htmlspecialchars($item['form']) . '</td>';
            $rows .= '<td style="text-align:center">' . $item['stock'] . '</td>';
            $rows .= '<td style="text-align:right">' . number_format($item['cost_price'], 2) . '</td>';
            $rows .= '<td style="text-align:right">' . number_format($item['sell_price'], 2) . '</td>';
            $rows .= '<td style="text-align:right;font-weight:bold">' . number_format($item['cost_value'], 2) . '</td>';
            $rows .= '<td style="text-align:right;font-weight:bold">' . number_format($item['sell_value'], 2) . '</td>';
            $rows .= '<td style="text-align:right">' . number_format($item['margin'], 2) . '</td>';
            $rows .= '</tr>';
        }
        $totalCost = array_sum(array_column($items, 'cost_value'));
        $totalSell = array_sum(array_column($items, 'sell_value'));
        $totalMargin = $totalSell - $totalCost;
        $rows .= "<tr style='background:#F0FDF4;font-weight:bold'>";
        $rows .= '<td colspan="6" style="text-align:right">TOTAL</td>';
        $rows .= '<td style="text-align:right">₦' . number_format($totalCost, 2) . '</td>';
        $rows .= '<td style="text-align:right">₦' . number_format($totalSell, 2) . '</td>';
        $rows .= '<td style="text-align:right">₦' . number_format($totalMargin, 2) . '</td>';
        $rows .= '</tr>';

        $date = now()->format('d M Y, H:i');
        $html = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"><head><meta charset=\"UTF-8\"><style>table{border-collapse:collapse;font-family:Calibri;font-size:10pt}th{background:#0f5132;color:white;border:1px solid #0f5132;padding:6px}td{border:1px solid #D1D5DB;padding:4px}</style></head><body><div style=\"font-size:14pt;font-weight:bold;color:#0f5132\">FUTH Lafia EMR — Inventory Valuation Report</div><div style=\"font-size:10pt;color:#6B7280\">Generated: {$date}</div><table><thead><tr><th>Drug Name</th><th>Generic</th><th>Form</th><th>Stock Qty</th><th>Cost Price ₦</th><th>Sell Price ₦</th><th>Cost Value ₦</th><th>Sell Value ₦</th><th>Margin ₦</th></tr></thead><tbody>{$rows}</tbody></table></body></html>";

        return response($html, 200, ['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Inventory-Valuation-' . now()->format('Y-m-d') . '.xls"']);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $items = $this->buildData();
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="FUTH-Lafia-Inventory-Valuation-' . now()->format('Y-m-d') . '.csv"'];
        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['Drug Name', 'Generic', 'Form', 'Stock Qty', 'Cost Price (NGN)', 'Sell Price (NGN)', 'Cost Value (NGN)', 'Sell Value (NGN)', 'Margin (NGN)']);
            foreach ($items as $item) {
                fputcsv($file, [$item['name'], $item['generic'], $item['form'], $item['stock'], $item['cost_price'], $item['sell_price'], $item['cost_value'], $item['sell_value'], $item['margin']]);
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

        $items = [];
        foreach ($drugs as $drug) {
            $stock = $drug->totalStock();
            if ($stock <= 0) continue;
            $items[] = [
                'name' => $drug->name,
                'generic' => $drug->generic_name ?? '—',
                'form' => $drug->dosage_form ?? '—',
                'stock' => $stock,
                'cost_price' => (float) $drug->cost_price,
                'sell_price' => (float) $drug->selling_price,
                'cost_value' => $stock * (float) $drug->cost_price,
                'sell_value' => $stock * (float) $drug->selling_price,
                'margin' => $stock * ((float) $drug->selling_price - (float) $drug->cost_price),
            ];
        }
        usort($items, fn($a, $b) => $b['cost_value'] <=> $a['cost_value']);
        return $items;
    }
}
