<?php

namespace App\Filament\Pages;

use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\GeneralStoreItem;
use App\Models\GeneralStoreStock;
use Filament\Pages\Page;

class InventoryValuationReport extends Page
{
    protected string $view = 'filament.pages.inventory-valuation-report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|\UnitEnum|null $navigationGroup = 'Accounts';

    protected static ?string $navigationLabel = 'Inventory Valuation';

    protected static ?string $title = 'Inventory Valuation Report';

    protected static ?int $navigationSort = 5;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'accountant', 'pharmacist', 'medical_administrator']);
    }

    public function getTitle(): string
    {
        return 'Inventory Valuation Report';
    }

    public function getValuationData(): array
    {
        // ---------------------------------------------------------------
        // Pharmacy drug inventory valuation
        // ---------------------------------------------------------------
        $drugs = Drug::where('status', true)->with(['batches' => function ($q) {
            $q->where('quantity_remaining', '>', 0)->where('is_quarantined', false);
        }])->get();

        $pharmacyItems = [];
        $pharmacyTotalCost = 0;
        $pharmacyTotalSell = 0;
        $pharmacyTotalItems = 0;
        $pharmacyTotalBatches = 0;

        foreach ($drugs as $drug) {
            $stock = $drug->totalStock();
            if ($stock <= 0) continue;

            $costValue = $stock * (float) $drug->cost_price;
            $sellValue = $stock * (float) $drug->selling_price;
            $pharmacyTotalCost += $costValue;
            $pharmacyTotalSell += $sellValue;
            $pharmacyTotalItems++;
            $pharmacyTotalBatches += $drug->batches->count();

            $pharmacyItems[] = [
                'name' => $drug->name,
                'generic' => $drug->generic_name ?? '—',
                'form' => $drug->dosage_form ?? '—',
                'strength' => $drug->strength ?? '—',
                'stock' => $stock,
                'cost_price' => (float) $drug->cost_price,
                'sell_price' => (float) $drug->selling_price,
                'cost_value' => $costValue,
                'sell_value' => $sellValue,
                'margin' => $sellValue - $costValue,
                'batches' => $drug->batches->count(),
            ];
        }

        // Sort by cost value descending
        usort($pharmacyItems, fn($a, $b) => $b['cost_value'] <=> $a['cost_value']);

        // ---------------------------------------------------------------
        // General stores inventory valuation
        // ---------------------------------------------------------------
        $storeItems = GeneralStoreItem::where('is_active', true)->with(['stocks.location'])->get();

        $storeValuation = [];
        $storeTotalCost = 0;
        $storeTotalItems = 0;

        foreach ($storeItems as $item) {
            $totalQty = $item->stocks->sum('quantity');
            if ($totalQty <= 0) continue;

            // We don't have a cost_price column on general_store_items, so use 0
            $unitCost = 0; // Would be from purchase records if available
            $value = $totalQty * $unitCost;
            $storeTotalCost += $value;
            $storeTotalItems++;

            $storeValuation[] = [
                'name' => $item->name,
                'code' => $item->code,
                'category' => $item->category?->name ?? '—',
                'unit' => $item->unit_of_measure ?? '—',
                'quantity' => $totalQty,
                'reorder_level' => $item->reorder_level,
                'unit_cost' => $unitCost,
                'value' => $value,
            ];
        }

        usort($storeValuation, fn($a, $b) => $b['value'] <=> $a['value']);

        // ---------------------------------------------------------------
        // Summary
        // ---------------------------------------------------------------
        $grandTotal = $pharmacyTotalCost + $storeTotalCost;
        $potentialRevenue = $pharmacyTotalSell + $storeTotalCost; // Store items don't have sell price
        $potentialMargin = $pharmacyTotalSell - $pharmacyTotalCost;

        return [
            'pharmacyItems' => $pharmacyItems,
            'pharmacyTotalCost' => $pharmacyTotalCost,
            'pharmacyTotalSell' => $pharmacyTotalSell,
            'pharmacyTotalItems' => $pharmacyTotalItems,
            'pharmacyTotalBatches' => $pharmacyTotalBatches,
            'pharmacyMargin' => $potentialMargin,
            'storeValuation' => $storeValuation,
            'storeTotalCost' => $storeTotalCost,
            'storeTotalItems' => $storeTotalItems,
            'grandTotal' => $grandTotal,
            'potentialRevenue' => $potentialRevenue,
        ];
    }
}
