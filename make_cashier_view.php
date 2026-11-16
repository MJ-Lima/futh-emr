<?php
/**
 * Creates the missing cashier.blade.php view.
 * Run: php make_cashier_view.php
 */

 $viewContent = <<<'BLADE'
<x-filament-panels::page>
    @php
        $data = $this->getCashierData();
        $recentReceipts = $data['recentReceipts'];
        $totalToday = $data['totalToday'];
        $countToday = $data['countToday'];
        $cashToday = $data['cashToday'];
        $cardToday = $data['cardToday'];
        $insuranceToday = $data['insuranceToday'];
        $rules = $data['rules'];
    @endphp

    <div class="space-y-6">
        <div>
            <h2 class="text-xl font-bold">Cashier Dashboard</h2>
            <p class="text-sm text-gray-500">Collect payments and issue receipts</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-filament::card>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Total Today</p>
                    <p class="text-2xl font-bold text-green-600">N{{ number_format((float)$totalToday, 2) }}</p>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Receipts Today</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($countToday) }}</p>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Cash Today</p>
                    <p class="text-2xl font-bold text-amber-600">N{{ number_format((float)$cashToday, 2) }}</p>
                </div>
            </x-filament::card>
            <x-filament::card>
                <div class="p-4">
                    <p class="text-sm text-gray-500">Insurance Today</p>
                    <p class="text-2xl font-bold text-purple-600">N{{ number_format((float)$insuranceToday, 2) }}</p>
                </div>
            </x-filament::card>
        </div>

        <x-filament::card>
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">Recent Receipts (Today)</h3>
                @if($recentReceipts->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-gray-600">
                                    <th class="py-2 px-3">Receipt #</th>
                                    <th class="py-2 px-3">Patient</th>
                                    <th class="py-2 px-3">Amount</th>
                                    <th class="py-2 px-3">Method</th>
                                    <th class="py-2 px-3">Status</th>
                                    <th class="py-2 px-3">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentReceipts as $receipt)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-3 font-mono">{{ $receipt->receipt_number ?? '-' }}</td>
                                        <td class="py-2 px-3">{{ $receipt->patient?->full_name ?? '-' }}</td>
                                        <td class="py-2 px-3 font-semibold">N{{ number_format((float)$receipt->amount, 2) }}</td>
                                        <td class="py-2 px-3">{{ $receipt->payment_method ?? '-' }}</td>
                                        <td class="py-2 px-3">{{ $receipt->status ?? '-' }}</td>
                                        <td class="py-2 px-3">{{ $receipt->receipt_date?->format('H:i') ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No receipts issued today yet.</p>
                @endif
            </div>
        </x-filament::card>

        @if($rules->isNotEmpty())
            <x-filament::card>
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-4">Active Service Payment Rules</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b text-left text-gray-600">
                                    <th class="py-2 px-3">Service</th>
                                    <th class="py-2 px-3">Category</th>
                                    <th class="py-2 px-3">Amount</th>
                                    <th class="py-2 px-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rules as $rule)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-3">{{ $rule->name ?? '-' }}</td>
                                        <td class="py-2 px-3">{{ $rule->category ?? '-' }}</td>
                                        <td class="py-2 px-3 font-semibold">N{{ number_format((float)($rule->amount ?? 0), 2) }}</td>
                                        <td class="py-2 px-3">Active</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>
BLADE;

 $dir = __DIR__ . '/resources/views/filament/pages';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

 $path = $dir . '/cashier.blade.php';
file_put_contents($path, $viewContent);

echo "Created: $path\n";
echo "Size: " . filesize($path) . " bytes\n";
echo "First line: " . fgets(fopen($path, 'r')) . "\n";