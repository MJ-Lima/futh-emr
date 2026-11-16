<x-filament-panels::page>
    
    <div class="space-y-6">
        {{-- Header --}}
        <div class="rounded-2xl bg-gradient-to-r from-emerald-700 via-emerald-600 to-green-500 p-6 text-white shadow-lg">
            <h2 class="text-2xl font-bold tracking-tight">Hospital Reports & Statistics Dashboard</h2>
            <p class="mt-1 text-emerald-50">Federal University Teaching Hospital, Lafia — Comprehensive analytics across all departments, units, and users</p>
            <p class="mt-1 text-emerald-100 text-sm">Generated: {{ now()->format('M d, Y H:i') }} (Africa/Lagos)</p>
        </div>

        {{-- Top-level KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4">
                <p class="text-xs text-emerald-700 uppercase tracking-wide">Patients</p>
                <p class="text-2xl font-bold text-emerald-900">{{ number_format($patientStats['total'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">+{{ $patientStats['new_this_month'] ?? 0 }} this month</p>
            </div>
            <div class="rounded-xl bg-info-50 border border-info-200 p-4">
                <p class="text-xs text-info-700 uppercase tracking-wide">Encounters</p>
                <p class="text-2xl font-bold text-info-900">{{ number_format($encounterStats['total'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $encounterStats['today'] ?? 0 }} today</p>
            </div>
            <div class="rounded-xl bg-success-50 border border-success-200 p-4">
                <p class="text-xs text-success-700 uppercase tracking-wide">Revenue (Year)</p>
                <p class="text-xl font-bold text-success-900">₦{{ number_format((float) ($financialStats['revenue_year'] ?? 0), 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">₦{{ number_format((float) ($financialStats['revenue_today'] ?? 0), 0) }} today</p>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                <p class="text-xs text-amber-700 uppercase tracking-wide">Bed Occupancy</p>
                <p class="text-2xl font-bold text-amber-900">{{ $wardStats['occupancy_pct'] ?? 0 }}%</p>
                <p class="text-xs text-gray-500 mt-1">{{ $wardStats['beds_occupied'] ?? 0 }}/{{ $wardStats['beds'] ?? 0 }} beds</p>
            </div>
            <div class="rounded-xl bg-rose-50 border border-rose-200 p-4">
                <p class="text-xs text-rose-700 uppercase tracking-wide">Insurance Claims</p>
                <p class="text-2xl font-bold text-rose-900">{{ number_format($insuranceStats['pending_claims'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $insuranceStats['paid_claims'] ?? 0 }} paid</p>
            </div>
            <div class="rounded-xl bg-purple-50 border border-purple-200 p-4">
                <p class="text-xs text-purple-700 uppercase tracking-wide">Staff</p>
                <p class="text-2xl font-bold text-purple-900">{{ number_format($hrStats['staff'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $hrStats['on_leave'] ?? 0 }} on leave</p>
            </div>
        </div>


        {{-- Charts Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Patient Gender Distribution</h3>
                <canvas id="genderChart" height="220"></canvas>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Visit Types</h3>
                <canvas id="encounterChart" height="220"></canvas>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Monthly Trends ({{ now()->format('Y') }})</h3>
                <canvas id="trendChart" height="220"></canvas>
            </div>
        </div>

        {{-- Monthly Trends --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700">12-Month Activity Trends</h3>
                <p class="text-xs text-gray-500">Patients registered, encounters, and revenue by month</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-100">
                            <th class="py-2 pr-4">Month</th>
                            <th class="py-2 pr-4">New Patients</th>
                            <th class="py-2 pr-4">Encounters</th>
                            <th class="py-2 pr-4">Revenue (₦)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthlyTrends as $trend)
                            <tr class="border-b border-gray-50 last:border-0">
                                <td class="py-2 pr-4 font-medium text-gray-900">{{ $trend['month'] ?? 0 }}</td>
                                <td class="py-2 pr-4 text-emerald-700 font-semibold">{{ number_format($trend['patients'] ?? 0) }}</td>
                                <td class="py-2 pr-4 text-info-700 font-semibold">{{ number_format($trend['encounters'] ?? 0) }}</td>
                                <td class="py-2 pr-4 text-success-700 font-semibold">₦{{ number_format((float) ($trend['revenue'] ?? 0), 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Departmental Statistics --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Departmental Statistics</h3>
                    <p class="text-xs text-gray-500">Staff distribution by department</h3>
                </div>
                @if (!empty($departmentStats))
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-100">
                                    <th class="py-2 pr-4">Department</th>
                                    <th class="py-2 pr-4">Code</th>
                                    <th class="py-2 pr-4">Staff</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentStats as $dept)
                                    <tr class="border-b border-gray-50 last:border-0">
                                        <td class="py-2 pr-4 font-medium text-gray-900">{{ $dept['name'] ?? 0 }}</td>
                                        <td class="py-2 pr-4 text-gray-600">{{ $dept['code'] ?? 0 }}</td>
                                        <td class="py-2 pr-4"><x-filament::badge color="success">{{ $dept['staff_count'] ?? 0 }}</x-filament::badge></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No department data available.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">User Activity</h3>
                    <p class="text-xs text-gray-500">Active users and their roles</h3>
                </div>
                @if (!empty($userStats))
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 border-b border-gray-100">
                                    <th class="py-2 pr-4">User</th>
                                    <th class="py-2 pr-4">Role</th>
                                    <th class="py-2 pr-4">Last Login</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userStats as $u)
                                    <tr class="border-b border-gray-50 last:border-0">
                                        <td class="py-2 pr-4">
                                            <p class="font-medium text-gray-900">{{ $u['name'] ?? 0 }}</p>
                                            <p class="text-xs text-gray-500">{{ $u['email'] ?? 0 }}</p>
                                        </td>
                                        <td class="py-2 pr-4"><x-filament::badge color="info">{{ $u['role'] ?? 0 }}</x-filament::badge></td>
                                        <td class="py-2 pr-4 text-gray-600 text-xs">{{ $u['last_login'] ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">No user activity data.</p>
                @endif
            </div>
        </div>

        {{-- Detailed Module Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Patient Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Patient Demographics</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Total Patients</span><span class="font-bold">{{ $patientStats['total'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Male</span><span class="font-bold text-info-700">{{ $patientStats['male'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Female</span><span class="font-bold text-rose-700">{{ $patientStats['female'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>New (This Month)</span><span class="font-bold text-emerald-700">{{ $patientStats['new_this_month'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Verified (Biometric)</span><span class="font-bold text-success-700">{{ $patientStats['verified'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending Verification</span><span class="font-bold text-amber-700">{{ $patientStats['pending_verification'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Encounter Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Encounters by Type</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Total</span><span class="font-bold">{{ $encounterStats['total'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Outpatient</span><span class="font-bold">{{ $encounterStats['outpatient'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Emergency</span><span class="font-bold text-rose-700">{{ $encounterStats['emergency'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Inpatient</span><span class="font-bold text-amber-700">{{ $encounterStats['inpatient'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Telemedicine</span><span class="font-bold text-info-700">{{ $encounterStats['telemedicine'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Completed</span><span class="font-bold text-success-700">{{ $encounterStats['completed'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Financial Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Financial Summary</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Revenue (Today)</span><span class="font-bold text-success-700">₦{{ number_format((float) ($financialStats['revenue_today'] ?? 0), 0) }}</span></li>
                    <li class="flex justify-between"><span>Revenue (Month)</span><span class="font-bold text-success-700">₦{{ number_format((float) ($financialStats['revenue_month'] ?? 0), 0) }}</span></li>
                    <li class="flex justify-between"><span>Revenue (Year)</span><span class="font-bold text-success-700">₦{{ number_format((float) ($financialStats['revenue_year'] ?? 0), 0) }}</span></li>
                    <li class="flex justify-between"><span>Outstanding</span><span class="font-bold text-rose-700">₦{{ number_format((float) ($financialStats['outstanding'] ?? 0), 0) }}</span></li>
                    <li class="flex justify-between"><span>Total Assets</span><span class="font-bold">₦{{ number_format((float) ($financialStats['total_assets'] ?? 0), 0) }}</span></li>
                    <li class="flex justify-between"><span>Total Liabilities</span><span class="font-bold">₦{{ number_format((float) ($financialStats['total_liabilities'] ?? 0), 0) }}</span></li>
                </ul>
            </div>

            {{-- Pharmacy Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Pharmacy</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Total Drugs</span><span class="font-bold">{{ $pharmacyStats['total_drugs'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Low Stock</span><span class="font-bold text-amber-700">{{ $pharmacyStats['low_stock'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Prescriptions</span><span class="font-bold">{{ $pharmacyStats['prescriptions'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending Dispensing</span><span class="font-bold text-rose-700">{{ $pharmacyStats['pending_dispensing'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Dispensed</span><span class="font-bold text-success-700">{{ $pharmacyStats['dispensed'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Lab Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Laboratory</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Total Orders</span><span class="font-bold">{{ $labStats['total_orders'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending</span><span class="font-bold text-amber-700">{{ $labStats['pending'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>In Progress</span><span class="font-bold text-info-700">{{ $labStats['in_progress'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Completed</span><span class="font-bold text-success-700">{{ $labStats['completed'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Verified</span><span class="font-bold text-emerald-700">{{ $labStats['verified'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Radiology Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Radiology</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Total Orders</span><span class="font-bold">{{ $radiologyStats['total_orders'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending</span><span class="font-bold text-amber-700">{{ $radiologyStats['pending'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Completed</span><span class="font-bold text-success-700">{{ $radiologyStats['completed'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Verified</span><span class="font-bold text-emerald-700">{{ $radiologyStats['verified'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Insurance Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Insurance</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Providers</span><span class="font-bold">{{ $insuranceStats['providers'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Plans</span><span class="font-bold">{{ $insuranceStats['plans'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Active Policies</span><span class="font-bold text-success-700">{{ $insuranceStats['active_policies'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending Claims</span><span class="font-bold text-amber-700">{{ $insuranceStats['pending_claims'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Approved Claims</span><span class="font-bold text-info-700">{{ $insuranceStats['approved_claims'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Paid Claims</span><span class="font-bold text-success-700">{{ $insuranceStats['paid_claims'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Blood Bank Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Blood Bank</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Donors</span><span class="font-bold">{{ $bloodBankStats['donors'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Donations</span><span class="font-bold">{{ $bloodBankStats['donations'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Units Available</span><span class="font-bold text-success-700">{{ $bloodBankStats['units_available'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Units Transfused</span><span class="font-bold text-info-700">{{ $bloodBankStats['units_transfused'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Pending Requests</span><span class="font-bold text-amber-700">{{ $bloodBankStats['pending_requests'] ?? 0 }}</span></li>
                </ul>
            </div>

            {{-- Audit Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4"><h3 class="text-sm font-semibold text-gray-700">Audit</h3></div>
                <ul class="space-y-2 text-sm">
                    <li class="flex justify-between"><span>Medical Audits</span><span class="font-bold">{{ $auditStats['medical_audits'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Internal Audits</span><span class="font-bold">{{ $auditStats['internal_audits'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Open Findings</span><span class="font-bold text-amber-700">{{ $auditStats['open_findings'] ?? 0 }}</span></li>
                    <li class="flex justify-between"><span>Critical Findings</span><span class="font-bold text-rose-700">{{ $auditStats['critical_findings'] ?? 0 }}</span></li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        new Chart(document.getElementById("genderChart"), {
            type: "doughnut",
            data: {
                labels: ["Male", "Female"],
                datasets: [{
                    data: @json($genderChartData),
                    backgroundColor: ["#3b82f6", "#ec4899"],
                    borderWidth: 2, borderColor: "#fff"
                }]
            },
            options: { responsive: true, plugins: { legend: { position: "bottom", labels: { padding: 15 } } } }
        });

        new Chart(document.getElementById("encounterChart"), {
            type: "bar",
            data: {
                labels: ["Outpatient", "Inpatient", "Emergency", "Telemedicine"],
                datasets: [{
                    label: "Encounters",
                    data: @json($encounterChartData),
                    backgroundColor: ["#10b981", "#3b82f6", "#f59e0b", "#8b5cf6"],
                    borderRadius: 6
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });

        new Chart(document.getElementById("trendChart"), {
            type: "line",
            data: {
                labels: @json($trendLabels),
                datasets: [
                    { label: "New Patients", data: @json($trendPatients), borderColor: "#10b981", backgroundColor: "rgba(16,185,129,0.1)", fill: true, tension: 0.3 },
                    { label: "Encounters", data: @json($trendEncounters), borderColor: "#3b82f6", backgroundColor: "rgba(59,130,246,0.1)", fill: true, tension: 0.3 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: "bottom", labels: { padding: 15 } } }, scales: { y: { beginAtZero: true } } }
        });
    });
    </script>

</x-filament-panels::page>
