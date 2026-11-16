<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Order summary banner --}}
        <div class="rounded-xl bg-white p-4 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $record->order_number }}</h3>
                    <p class="text-sm text-gray-600">
                        Patient: <strong>{{ $record->patient->full_name }}</strong>
                        ({{ $record->patient->hospital_no }})
                        &middot; Lab: <strong>{{ $record->destinationLab?->name }}</strong>
                        &middot; Priority: <strong>{{ $record->priority }}</strong>
                    </p>
                </div>
                @php
                    $colorClass = match($record->status) {
                        'Verified' => 'bg-green-100 text-green-700',
                        'Completed' => 'bg-blue-100 text-blue-700',
                        'In Progress' => 'bg-amber-100 text-amber-700',
                        'Sample Collected' => 'bg-sky-100 text-sky-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $colorClass }}">
                    {{ $record->status }}
                </span>
            </div>
        </div>

        <form wire:submit="saveResults">
            {{ $this->form }}

            <div class="flex items-center gap-3 mt-6">
                <x-filament::button type="submit" color="success" icon="heroicon-m-check">
                    Save All Results
                </x-filament::button>
                <x-filament::button type="button" wire:click="saveAndComplete" color="primary" icon="heroicon-m-check-badge">
                    Save &amp; Mark Complete
                </x-filament::button>
                <x-filament::button type="button" :url="fn() => \App\Filament\Resources\LabOrders\LabOrderResource::getUrl('view', ['record' => $record])" color="gray">
                    Cancel
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
