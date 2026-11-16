<?php

namespace App\Filament\Pages;

use App\Models\Drug;
use App\Models\DrugBatch;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Response;

class StockReorderReport extends Page
{
    protected string $view = 'filament.pages.stock-reorder-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static string|\UnitEnum|null $navigationGroup = 'Pharmacy';

    protected static ?string $navigationLabel = 'Stock Reorder Report';

    protected static ?string $title = 'Stock Reorder Report — Low Stock Alerts';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'pharmacist', 'medical_administrator', 'accountant']);
    }

    public function getTitle(): string
    {
        return 'Stock Reorder Report — Low Stock Alerts';
    }

    /**
     * Get all drugs at or below their reorder level.
     */
    public function getLowStockDrugs(): array
    {
        $drugs = Drug::where('status', true)
            ->with(['batches' => function ($q) {
                $q->where('quantity_remaining', '>', 0)
                  ->where('is_quarantined', false)
                  ->orderBy('expiry_date');
            }, 'category', 'hospital'])
            ->get();

        $lowStock = [];
        $outOfStock = [];
        $expiringSoon = [];

        foreach ($drugs as $drug) {
            $stock = $drug->totalStock();
            $reorderLevel = (float) $drug->reorder_level;

            // Out of stock
            if ($stock <= 0) {
                $outOfStock[] = [
                    'drug' => $drug,
                    'stock' => 0,
                    'reorder_level' => $reorderLevel,
                    'reorder_qty' => (float) $drug->reorder_quantity,
                    'status' => 'OUT OF STOCK',
                    'status_color' => 'red',
                    'batches' => $drug->batches,
                ];
            }
            // Low stock (at or below reorder level but > 0)
            elseif ($stock <= $reorderLevel) {
                $lowStock[] = [
                    'drug' => $drug,
                    'stock' => $stock,
                    'reorder_level' => $reorderLevel,
                    'reorder_qty' => (float) $drug->reorder_quantity,
                    'status' => 'LOW STOCK',
                    'status_color' => 'amber',
                    'batches' => $drug->batches,
                ];
            }

            // Check for expiring soon batches (within 90 days)
            foreach ($drug->batches as $batch) {
                if ($batch->expiry_date && $batch->expiry_date->isFuture() && $batch->expiry_date->diffInDays(now()) <= 90) {
                    $expiringSoon[] = [
                        'drug_name' => $drug->name,
                        'batch_number' => $batch->batch_number,
                        'expiry_date' => $batch->expiry_date,
                        'quantity_remaining' => $batch->quantity_remaining,
                        'days_to_expiry' => $batch->expiry_date->diffInDays(now()),
                    ];
                }
            }
        }

        return [
            'outOfStock' => $outOfStock,
            'lowStock' => $lowStock,
            'expiringSoon' => $expiringSoon,
            'totalDrugs' => $drugs->count(),
            'outOfStockCount' => count($outOfStock),
            'lowStockCount' => count($lowStock),
            'expiringSoonCount' => count($expiringSoon),
        ];
    }
}
