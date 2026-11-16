<x-filament-panels::page>
    @php $data = $this->getTurnaroundData(); @endphp

    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Laboratory Turnaround Time Dashboard</h3>
            <p class="text-sm text-emerald-50">Track order processing time from order to result. Monitor average, min, and max turnaround times per lab and per priority.</p>
        </div>

        {{-- KPI cards --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Orders</div>
                <div class="text-2xl font-bold text-gray-700 mt-1">{{ $data['stats']['totalOrders'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-amber-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-amber-500 uppercase tracking-wide">Pending</div>
                <div class="text-2xl font-bold text-amber-700 mt-1">{{ $data['stats']['pending'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-blue-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-blue-500 uppercase tracking-wide">In Progress</div>
                <div class="text-2xl font-bold text-blue-700 mt-1">{{ $data['stats']['inProgress'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-emerald-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-emerald-500 uppercase tracking-wide">Completed</div>
                <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $data['stats']['completed'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-green-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-green-500 uppercase tracking-wide">Verified</div>
                <div class="text-2xl font-bold text-green-700 mt-1">{{ $data['stats']['verified'] }}</div>
            </div>
            <div class="bg-white rounded-xl border border-red-300 p-4 shadow-sm">
                <div class="text-xs font-semibold text-red-500 uppercase tracking-wide">Cancelled</div>
                <div class="text-2xl font-bold text-red-700 mt-1">{{ $data['stats']['cancelled'] }}</div>
            </div>
        </div>

        {{-- TAT summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Average TAT</div>
                <div class="text-3xl font-bold text-emerald-700 mt-2">{{ $data['avgTAT'] }} <span class="text-lg font-normal text-gray-500">hours</span></div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-white rounded-xl border border-green-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-green-600 uppercase tracking-wide">Fastest TAT</div>
                <div class="text-3xl font-bold text-green-700 mt-2">{{ $data['minTAT'] }} <span class="text-lg font-normal text-gray-500">hours</span></div>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-white rounded-xl border border-red-200 p-5 shadow-sm">
                <div class="text-xs font-semibold text-red-600 uppercase tracking-wide">Slowest TAT</div>
                <div class="text-3xl font-bold text-red-700 mt-2">{{ $data['maxTAT'] }} <span class="text-lg font-normal text-gray-500">hours</span></div>
            </div>
        </div>

        {{-- Two-column: TAT by Lab + TAT by Priority --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">TAT by Laboratory</h3>
                @if(!empty($data['tatByLab']))
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-200">
                        <th class="text-left py-1 text-xs text-gray-500 uppercase">Lab</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Orders</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Avg (hrs)</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Min</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Max</th>
                    </tr></thead>
                    <tbody>
                        @foreach($data['tatByLab'] as $lab => $stats)
                        <tr class="border-b border-gray-100">
                            <td class="py-1.5 font-medium">{{ $lab }}</td>
                            <td class="text-center py-1.5">{{ $stats['count'] }}</td>
                            <td class="text-center py-1.5 font-bold text-emerald-700">{{ $stats['avg'] }}</td>
                            <td class="text-center py-1.5 text-green-600">{{ $stats['min'] }}</td>
                            <td class="text-center py-1.5 text-red-600">{{ $stats['max'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400 text-sm text-center py-4">No completed orders with TAT data.</p>
                @endif
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="text-sm font-bold text-gray-800 mb-3">TAT by Priority</h3>
                @if(!empty($data['tatByPriority']))
                <table class="w-full text-sm">
                    <thead><tr class="border-b border-gray-200">
                        <th class="text-left py-1 text-xs text-gray-500 uppercase">Priority</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Orders</th>
                        <th class="text-center py-1 text-xs text-gray-500 uppercase">Avg (hrs)</th>
                    </tr></thead>
                    <tbody>
                        @foreach($data['tatByPriority'] as $priority => $stats)
                        <tr class="border-b border-gray-100">
                            <td class="py-1.5">
                                <span class="inline-block px-2 py-0.5 text-xs rounded {{ $priority === 'STAT' ? 'bg-red-100 text-red-700' : ($priority === 'Urgent' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700') }}">{{ $priority }}</span>
                            </td>
                            <td class="text-center py-1.5">{{ $stats['count'] }}</td>
                            <td class="text-center py-1.5 font-bold">{{ $stats['avg'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400 text-sm text-center py-4">No completed orders with priority TAT data.</p>
                @endif
            </div>
        </div>

        {{-- Recent orders table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Recent Lab Orders (with TAT)</h3>
            </div>
            @if(!empty($data['recentOrders']))
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Order #</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Patient</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Lab</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Priority</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Ordered</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Resulted</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">TAT (hrs)</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Tests</th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($data['recentOrders'] as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $order['order_number'] }}</td>
                        <td class="px-4 py-2">{{ $order['patient'] }}</td>
                        <td class="px-4 py-2 text-xs">{{ $order['lab'] }}</td>
                        <td class="px-4 py-2 text-center"><span class="inline-block px-2 py-0.5 text-xs rounded {{ $order['priority'] === 'STAT' ? 'bg-red-100 text-red-700' : ($order['priority'] === 'Urgent' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700') }}">{{ $order['priority'] }}</span></td>
                        <td class="px-4 py-2 text-xs">{{ $order['ordered_at'] }}</td>
                        <td class="px-4 py-2 text-xs">{{ $order['resulted_at'] ?? '—' }}</td>
                        <td class="px-4 py-2 text-center font-bold {{ $order['tat_hours'] && $order['tat_hours'] > 24 ? 'text-red-600' : 'text-emerald-600' }}">{{ $order['tat_hours'] ?? '—' }}</td>
                        <td class="px-4 py-2 text-center">{{ $order['tests_count'] }}</td>
                        <td class="px-4 py-2 text-center"><span class="inline-block px-2 py-0.5 text-xs rounded {{ in_array($order['status'], ['Completed','Verified']) ? 'bg-green-100 text-green-700' : ($order['status'] === 'Cancelled' || $order['status'] === 'Rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ $order['status'] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="p-10 text-center">
                <div class="text-gray-400 text-4xl mb-3">🧪</div>
                <h3 class="font-bold text-gray-800">No lab orders found</h3>
                <p class="text-gray-500 text-sm mt-1">Lab orders will appear here once created from the Laboratory module.</p>
            </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
