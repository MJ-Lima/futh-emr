<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-700 to-emerald-600 rounded-xl p-5 text-white shadow-md">
            <h3 class="text-xl font-bold mb-1">Dashboard Widget Settings</h3>
            <p class="text-sm text-emerald-50">Control which dashboard sections appear for each user role. Changes are saved to the database and take effect immediately.</p>
        </div>

        {{-- Role selector --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Select a Role to Configure</h3>
            <div class="flex flex-wrap gap-2">
                @foreach (\App\Models\DashboardWidgetSetting::ROLES as $role)
                <button wire:click="selectRole('{{ $role }}')"
                    class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $selectedRole === $role ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ ucwords(str_replace('_', ' ', $role)) }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- Widget toggles --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Dashboard Widgets for {{ ucwords(str_replace('_', ' ', $selectedRole)) }}</h3>
                <p class="text-xs text-gray-500 mt-1">Toggle each widget on or off. Click Save to apply changes.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach (\App\Models\DashboardWidgetSetting::WIDGETS as $key => $widget)
                <div class="px-5 py-4 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $widget['label'] }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ $widget['description'] }}</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="widgetVisibility.{{ $key }}" class="sr-only peer" />
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
                @endforeach
            </div>
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center gap-3">
                <button wire:click="save" class="px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700">
                    💾 Save Settings
                </button>
                <button wire:click="resetDefaults" class="px-5 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300" onclick="return confirm('Reset all widgets for this role to defaults?')">
                    🔄 Reset to Defaults
                </button>
            </div>
        </div>

        {{-- Current state preview --}}
        <div class="bg-emerald-50 rounded-xl border border-emerald-200 p-4">
            <h3 class="text-sm font-bold text-emerald-800 mb-2">Current Configuration for {{ ucwords(str_replace('_', ' ', $selectedRole)) }}</h3>
            <div class="flex flex-wrap gap-2">
                @foreach (\App\Models\DashboardWidgetSetting::WIDGETS as $key => $widget)
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $widgetVisibility[$key] ?? false ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-400 line-through' }}">
                    {{ $widget['label'] }}
                </span>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
