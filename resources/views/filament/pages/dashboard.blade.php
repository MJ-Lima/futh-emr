<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">{{ $dashboardTitle }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $dashboardSubtitle }}</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $badgeColors = [
                        'danger' => '#dc2626', 'warning' => '#d97706', 'success' => '#059669',
                        'info' => '#0284c7', 'gray' => '#6b7280',
                    ];
                    $badgeHex = $badgeColors[$roleBadgeColor] ?? '#6b7280';
                @endphp
                <span style="background: {{ $badgeHex }}20; color: {{ $badgeHex }}; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600;">
                    {{ ucwords(str_replace('_', ' ', $role)) }}
                </span>
                <a href="/admin/reports-dashboard" style="background: #059669; color: white; padding: 8px 16px; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    Export Report
                </a>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <select style="border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; padding: 8px 12px;">
                <option>All Clinics</option>
                @foreach(\App\Models\Clinic::all() as $clinic)
                    <option value="{{ $clinic->id }}">{{ $clinic->name }}</option>
                @endforeach
            </select>
            <input type="date" style="border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; padding: 8px 12px;" value="{{ now()->subWeek()->format('Y-m-d') }}" />
            <span style="color: #9ca3af;">→</span>
            <input type="date" style="border-radius: 8px; border: 1px solid #d1d5db; font-size: 14px; padding: 8px 12px;" value="{{ now()->format('Y-m-d') }}" />
            <button style="background: #059669; color: white; border: none; border-radius: 8px; padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer;">
                Apply Filters
            </button>
            <button style="background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 16px; font-size: 14px; font-weight: 600; cursor: pointer;">
                Reset
            </button>
        </div>

        {{-- KPI Cards --}}
        @if (!empty($kpiCards))
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
            @php
                $colorMap = [
                    'blue'    => ['#3b82f6', '#dbeafe'],
                    'purple'  => ['#8b5cf6', '#ede9fe'],
                    'orange'  => ['#f97316', '#ffedd5'],
                    'green'   => ['#22c55e', '#dcfce7'],
                    'red'     => ['#ef4444', '#fee2e2'],
                    'amber'   => ['#f59e0b', '#fef3c7'],
                    'rose'    => ['#f43f5e', '#ffe4e6'],
                    'indigo'  => ['#6366f1', '#e0e7ff'],
                ];
            @endphp
            @foreach ($kpiCards as $card)
                @php
                    $hex = $colorMap[$card['color']] ?? ['#6b7280', '#f3f4f6'];
                    $iconColor = $hex[0];
                    $bgColor = $hex[1];
                @endphp
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: box-shadow 0.2s;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <div style="border-radius: 8px; padding: 8px; background: {{ $bgColor }};">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="{{ $iconColor }}" style="width: 20px; height: 20px;">
                                @switch($card['icon'])
                                    @case('heroicon-o-users')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        @break
                                    @case('heroicon-o-user-plus')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766" />
                                        @break
                                    @case('heroicon-o-clipboard-document-check')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.09-1.124.27A2.251 2.251 0 0 0 4.5 6.108V18a2.25 2.25 0 0 0 2.25 2.25h8.25a2.25 2.25 0 0 0 2.25-2.25V9.75c0-1.012-.668-1.867-1.586-2.15" />
                                        @break
                                    @case('heroicon-o-calendar-days')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        @break
                                    @case('heroicon-o-clock')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        @break
                                    @case('heroicon-o-check-circle')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        @break
                                    @case('heroicon-o-banknotes')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-.75 0v9c0 .621-.504 1.125-1.125 1.125H18.75M12 12.75h4.5m-4.5 0v-4.5m4.5 4.5v-4.5m-4.5 0H7.5m9.75 3.75H21M3 9.75h4.5m-4.5 0v6.75m13.5-6.75v6.75m0-6.75H21M3 16.5h4.5m-4.5 0v2.25a1.125 1.125 0 0 0 1.125 1.125h2.625m9.75-3.375v2.25c0 .621-.504 1.125-1.125 1.125H7.875m4.5-9.375V4.5M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75" />
                                        @break
                                    @case('heroicon-o-clipboard-document')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                        @break
                                    @case('heroicon-o-beaker')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M14.25 3.104c.251.023.501.05.75.082M14.25 3.104v5.714a2.25 2.25 0 0 0 .659 1.591L19 14.5M18.75 18a.75.75 0 0 0-.75.75V21a.75.75 0 0 0 .75.75h.75a.75.75 0 0 0 .75-.75v-1.5a.75.75 0 0 0-.75-.75h-.75Zm-9 0a.75.75 0 0 0-.75.75V21a.75.75 0 0 0 .75.75H18a.75.75 0 0 0 .75-.75v-1.5a.75.75 0 0 0-.75-.75H9.75ZM5.25 14.5h13.5M5.25 14.5 8.25 9.75M19.5 14.5 16.5 9.75M8.25 9.75V6.75a2.25 2.25 0 0 1 2.25-2.25h3a2.25 2.25 0 0 1 2.25 2.25v3" />
                                        @break
                                    @case('heroicon-o-camera')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        @break
                                    @case('heroicon-o-home-modern')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25M12 3v18m6-13.5v9.75m-12 0V7.5m6 13.5h6m-6 0v-9.75m0 9.75H6M3 7.5l4.5-4.5M3 7.5h18M21 7.5l-4.5-4.5M3 7.5v9.75m18-9.75v9.75" />
                                        @break
                                    @case('heroicon-o-rectangle-stack')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.596.237-1.137.622-1.537" />
                                        @break
                                    @case('heroicon-o-shield-check')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                        @break
                                    @case('heroicon-o-heart')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        @break
                                    @case('heroicon-o-exclamation-triangle')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        @break
                                    @case('heroicon-o-identification')
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                        @break
                                    @default
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625C9.75 8.004 10.254 7.5 10.875 7.5h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                @endswitch
                            </svg>
                        </div>
                        @if ($card['change'] != 0)
                            <span style="font-size: 12px; font-weight: 600; color: {{ $card['change'] > 0 ? '#16a34a' : '#dc2626' }};">
                                {{ $card['change'] > 0 ? '↑' : '↓' }} {{ abs($card['change']) }}%
                            </span>
                        @endif
                    </div>
                    <p style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">{{ $card['value'] }}</p>
                    <p style="font-size: 12px; color: #6b7280; margin: 4px 0 0 0;">{{ $card['label'] }}</p>
                    <p style="font-size: 12px; color: #9ca3af; margin: 2px 0 0 0;">{{ $card['period'] }}</p>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Doctor's Daily Worklist --}}
        @if (!empty($doctorWorklist))
        <div class="space-y-4">
            {{-- Worklist stat mini-cards --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;">
                <div style="background: #fef3c7; border-radius: 10px; padding: 12px 16px; border: 1px solid #fde68a;">
                    <div style="font-size: 11px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.04em;">Waiting for Doctor</div>
                    <div style="font-size: 24px; font-weight: 800; color: #92400e; margin-top: 2px;">{{ $doctorWorklist['waitingCount'] }}</div>
                </div>
                <div style="background: #fee2e2; border-radius: 10px; padding: 12px 16px; border: 1px solid #fecaca;">
                    <div style="font-size: 11px; font-weight: 600; color: #991b1b; text-transform: uppercase; letter-spacing: 0.04em;">Ward Rounds Due</div>
                    <div style="font-size: 24px; font-weight: 800; color: #991b1b; margin-top: 2px;">{{ $doctorWorklist['wardRoundsCount'] }}</div>
                </div>
                <div style="background: #dcfce7; border-radius: 10px; padding: 12px 16px; border: 1px solid #bbf7d0;">
                    <div style="font-size: 11px; font-weight: 600; color: #166534; text-transform: uppercase; letter-spacing: 0.04em;">My Inpatients</div>
                    <div style="font-size: 24px; font-weight: 800; color: #166534; margin-top: 2px;">{{ $doctorWorklist['myInpatientsCount'] }}</div>
                </div>
                <div style="background: #ede9fe; border-radius: 10px; padding: 12px 16px; border: 1px solid #ddd6fe;">
                    <div style="font-size: 11px; font-weight: 600; color: #5b21b6; text-transform: uppercase; letter-spacing: 0.04em;">Pending Labs</div>
                    <div style="font-size: 24px; font-weight: 800; color: #5b21b6; margin-top: 2px;">{{ $doctorWorklist['pendingLabsCount'] }}</div>
                </div>
                <div style="background: #dbeafe; border-radius: 10px; padding: 12px 16px; border: 1px solid #bfdbfe;">
                    <div style="font-size: 11px; font-weight: 600; color: #1e40af; text-transform: uppercase; letter-spacing: 0.04em;">My Consults Today</div>
                    <div style="font-size: 24px; font-weight: 800; color: #1e40af; margin-top: 2px;">{{ $doctorWorklist['myConsultationsToday'] }}</div>
                </div>
            </div>

            {{-- Two-column layout: Waiting patients + Ward rounds due --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                {{-- Patients waiting for doctor --}}
                <div style="background: white; border-radius: 12px; border: 1px solid #fde68a; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 14px; font-weight: 700; color: #92400e; margin: 0 0 10px 0;">⏰ Patients Waiting for Doctor ({{ $doctorWorklist['waitingCount'] }})</h3>
                    @if (!empty($doctorWorklist['waitingForDoctor']))
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach ($doctorWorklist['waitingForDoctor'] as $p)
                            <div style="display: flex; align-items: center; gap: 10px; padding: 6px 8px; border-radius: 8px; background: #fffbeb;">
                                <div style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; background: #fde68a; color: #92400e; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center;">{{ $p['queue'] }}</div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 600; color: #111827;">{{ $p['name'] }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $p['clinic'] }} &middot; Room {{ $p['room'] }} &middot; {{ $p['type'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="/admin/doctor-worklist" style="display: block; text-align: center; margin-top: 8px; font-size: 12px; font-weight: 600; color: #0f5132; text-decoration: none;">View all →</a>
                    @else
                        <p style="font-size: 13px; color: #9ca3af; text-align: center; padding: 12px;">No patients waiting. ✅</p>
                    @endif
                </div>

                {{-- Ward rounds due --}}
                <div style="background: white; border-radius: 12px; border: 1px solid #fecaca; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 14px; font-weight: 700; color: #991b1b; margin: 0 0 10px 0;">🏥 Ward Rounds Due Today ({{ $doctorWorklist['wardRoundsCount'] }})</h3>
                    @if (!empty($doctorWorklist['wardRoundsDue']))
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach ($doctorWorklist['wardRoundsDue'] as $p)
                            <div style="display: flex; align-items: center; gap: 10px; padding: 6px 8px; border-radius: 8px; background: #fef2f2;">
                                <div style="flex-shrink: 0; width: 28px; height: 28px; border-radius: 6px; background: #fee2e2; color: #991b1b; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center;">{{ $p['days'] }}d</div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 600; color: #111827;">{{ $p['name'] }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $p['ward'] }} / {{ $p['bed'] }} &middot; {{ $p['dx'] }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="/admin/ward-round" style="display: block; text-align: center; margin-top: 8px; font-size: 12px; font-weight: 600; color: #0f5132; text-decoration: none;">Go to Ward Round →</a>
                    @else
                        <p style="font-size: 13px; color: #9ca3af; text-align: center; padding: 12px;">All ward rounds done. ✅</p>
                    @endif
                </div>
            </div>

            {{-- Pending lab results --}}
            @if (!empty($doctorWorklist['pendingLabs']))
            <div style="background: white; border-radius: 12px; border: 1px solid #ddd6fe; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <h3 style="font-size: 14px; font-weight: 700; color: #5b21b6; margin: 0 0 10px 0;">🧪 Pending Lab Results ({{ $doctorWorklist['pendingLabsCount'] }})</h3>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    @foreach ($doctorWorklist['pendingLabs'] as $lab)
                    <div style="display: flex; align-items: center; justify-content: between; padding: 4px 8px; border-radius: 6px; background: #f5f3ff;">
                        <span style="font-size: 12px; font-weight: 600; color: #5b21b6;">{{ $lab['number'] }}</span>
                        <span style="font-size: 12px; color: #6b7280; margin-left: 8px;">— {{ $lab['name'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Quick Actions --}}
        @if (!empty($quickActions))
        <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <h3 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 12px 0;">Quick Actions</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @php
                    $actionColors = [
                        'success' => ['#22c55e', '#dcfce7', '#15803d'],
                        'info'    => ['#3b82f6', '#dbeafe', '#1d4ed8'],
                        'warning' => ['#f59e0b', '#fef3c7', '#b45309'],
                    ];
                @endphp
                @foreach ($quickActions as $action)
                    @php $ac = $actionColors[$action['color']] ?? $actionColors['info']; @endphp
                    <a href="{{ $action['url'] }}" style="display: inline-flex; align-items: center; gap: 8px; border-radius: 8px; border: 1px solid {{ $ac[0] }}40; padding: 8px 12px; font-size: 14px; font-weight: 500; color: {{ $ac[2] }}; text-decoration: none;">
                        {{ $action['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Charts Grid --}}
        @if (!empty($charts))
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
            @foreach ($charts as $chart)
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <h3 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">{{ $chart['title'] }}</h3>
                        @if (isset($chart['period']))
                            <span style="font-size: 12px; color: #9ca3af; background: #f3f4f6; padding: 2px 8px; border-radius: 4px;">{{ $chart['period'] }}</span>
                        @endif
                    </div>

                    @if ($chart['type'] === 'donut')
                        {{-- Donut Chart --}}
                        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                            <div style="position: relative; width: 128px; height: 128px;">
                                <svg style="width: 128px; height: 128px; transform: rotate(-90deg);" viewBox="0 0 36 36">
                                    @php
                                        $total = array_sum(array_column($chart['segments'], 'value'));
                                        $offset = 0;
                                    @endphp
                                    <circle cx="18" cy="18" r="15.915" fill="none" stroke="#f3f4f6" stroke-width="3" />
                                    @foreach ($chart['segments'] as $seg)
                                        @php
                                            $pct = $total > 0 ? ($seg['value'] / $total) * 100 : 0;
                                        @endphp
                                        <circle cx="18" cy="18" r="15.915" fill="none"
                                            stroke="{{ $seg['color'] }}"
                                            stroke-width="3"
                                            stroke-dasharray="{{ $pct }} {{100 - $pct}}"
                                            stroke-dashoffset="{{ $offset }}"
                                        />
                                        @php $offset -= $pct; @endphp
                                    @endforeach
                                </svg>
                                <div style="position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <span style="font-size: 20px; font-weight: 700; color: #111827;">{{ $chart['center_value'] }}</span>
                                    <span style="font-size: 12px; color: #9ca3af;">{{ $chart['center_label'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach ($chart['segments'] as $seg)
                                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $seg['color'] }};"></span>
                                        <span style="color: #6b7280;">{{ $seg['label'] }}</span>
                                    </div>
                                    <span style="font-weight: 600; color: #111827;">{{ number_format($seg['value']) }}</span>
                                </div>
                            @endforeach
                        </div>
                    @elseif ($chart['type'] === 'line')
                        {{-- Line Chart --}}
                        <div style="height: 160px;">
                            @php $maxVal = max(array_map(fn($d) => max($d['scheduled'], $d['completed']), $chart['data'])) ?: 1; @endphp
                            <svg style="width: 100%; height: 100%;" viewBox="0 0 300 150" preserveAspectRatio="none">
                                <line x1="0" y1="0" x2="300" y2="0" stroke="#f3f4f6" stroke-width="1" />
                                <line x1="0" y1="37" x2="300" y2="37" stroke="#f3f4f6" stroke-width="1" />
                                <line x1="0" y1="75" x2="300" y2="75" stroke="#f3f4f6" stroke-width="1" />
                                <line x1="0" y1="112" x2="300" y2="112" stroke="#f3f4f6" stroke-width="1" />
                                <line x1="0" y1="150" x2="300" y2="150" stroke="#f3f4f6" stroke-width="1" />
                                @php
                                    $points1 = ''; $points2 = '';
                                    foreach ($chart['data'] as $i => $d) {
                                        $x = ($i / (count($chart['data']) - 1)) * 300;
                                        $y1 = 150 - ($d['scheduled'] / $maxVal) * 140;
                                        $y2 = 150 - ($d['completed'] / $maxVal) * 140;
                                        $points1 .= "$x,$y1 "; $points2 .= "$x,$y2 ";
                                    }
                                @endphp
                                <polyline points="{{ trim($points1) }}" fill="none" stroke="#8b5cf6" stroke-width="2" />
                                <polyline points="{{ trim($points2) }}" fill="none" stroke="#22c55e" stroke-width="2" />
                            </svg>
                        </div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px;">
                            <div style="display: flex; gap: 12px; font-size: 12px;">
                                <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6;"></span>Scheduled</span>
                                <span style="display: flex; align-items: center; gap: 4px;"><span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>Completed</span>
                            </div>
                            <span style="font-size: 12px; color: #9ca3af;">{{ $chart['data'][0]['date'] }} - {{ $chart['data'][count($chart['data'])-1]['date'] }}</span>
                        </div>
                    @elseif ($chart['type'] === 'bar_horizontal')
                        {{-- Horizontal Bar Chart --}}
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            @php $maxVal = max(array_column($chart['data'], 'value')) ?: 1; @endphp
                            @foreach ($chart['data'] as $bar)
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; font-size: 12px; margin-bottom: 4px;">
                                        <span style="color: #6b7280;">{{ $bar['label'] }}</span>
                                        <span style="font-weight: 600; color: #111827;">{{ number_format($bar['value']) }}</span>
                                    </div>
                                    <div style="width: 100%; background: #f3f4f6; border-radius: 9999px; height: 10px;">
                                        <div style="background: #8b5cf6; height: 10px; border-radius: 9999px; width: {{ ($bar['value'] / $maxVal) * 100 }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif

        {{-- Bottom Section: Table + Side Widgets --}}
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px;">
            @if (!empty($bottomTable))
            <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <h3 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0;">{{ $bottomTable['title'] }}</h3>
                    <a href="/admin/clinics" style="font-size: 12px; color: #059669; text-decoration: none;">View all →</a>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; font-size: 14px; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; border-bottom: 1px solid #f3f4f6;">
                                @foreach ($bottomTable['columns'] as $col)
                                    <th style="padding: 8px 16px 8px 0;">{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bottomTable['rows'] as $row)
                                <tr style="border-bottom: 1px solid #f9fafb;">
                                    <td style="padding: 8px 16px 8px 0; font-weight: 500; color: #111827;">{{ $row['clinic'] }}</td>
                                    <td style="padding: 8px 16px 8px 0; color: #4b5563;">{{ $row['total_patients'] }}</td>
                                    <td style="padding: 8px 16px 8px 0; color: #8b5cf6; font-weight: 600;">{{ $row['new_patients'] }}</td>
                                    <td style="padding: 8px 16px 8px 0; color: #4b5563;">{{ $row['avg_wait'] }}</td>
                                    <td style="padding: 8px 16px 8px 0; color: #22c55e; font-weight: 600;">{{ $row['completed'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Side Widgets --}}
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach ($sideWidgets as $widget)
                    <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="border-radius: 8px; padding: 12px; background: #ecfdf5;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#059669" style="width: 24px; height: 24px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <p style="font-size: 12px; color: #6b7280; margin: 0;">{{ $widget['title'] }}</p>
                                <p style="font-size: 20px; font-weight: 700; color: #111827; margin: 0;">{{ $widget['value'] }}</p>
                                <p style="font-size: 12px; color: #9ca3af; margin: 0;">{{ $widget['subtitle'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (!empty($recentActivities))
                <div style="background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 14px; font-weight: 600; color: #374151; margin: 0 0 12px 0;">Recent Activities</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @foreach ($recentActivities as $activity)
                            <div style="display: flex; align-items: flex-start; gap: 12px;">
                                <div style="border-radius: 6px; padding: 6px; background: #f3f4f6;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-size: 14px; font-weight: 500; color: #111827; margin: 0;">{{ $activity['event'] }}</p>
                                    <p style="font-size: 12px; color: #6b7280; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $activity['detail'] }}</p>
                                </div>
                                <p style="font-size: 12px; color: #9ca3af; margin: 0; white-space: nowrap;">{{ $activity['time'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Responsive: stack columns on mobile --}}
    <style>
        @@media (max-width: 1024px) {
            .dashboard-bottom-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</x-filament-panels::page>
