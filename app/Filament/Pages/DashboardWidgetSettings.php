<?php

namespace App\Filament\Pages;

use App\Models\DashboardWidgetSetting;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class DashboardWidgetSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.dashboard-widget-settings';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Dashboard Widget Settings';

    protected static ?string $title = 'Dashboard Widget Settings — Control What Each Role Sees';

    protected static ?int $navigationSort = 30;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'medical_administrator']);
    }

    public ?string $selectedRole = 'super_admin';
    public array $widgetVisibility = [];

    public function mount(): void
    {
        DashboardWidgetSetting::seedDefaults();
        $this->loadRoleSettings($this->selectedRole);
    }

    public function loadRoleSettings(string $role): void
    {
        $this->selectedRole = $role;
        foreach (array_keys(DashboardWidgetSetting::WIDGETS) as $key) {
            $this->widgetVisibility[$key] = DashboardWidgetSetting::isWidgetVisible($role, $key);
        }
    }

    public function selectRole(string $role): void
    {
        $this->loadRoleSettings($role);
    }

    public function save(): void
    {
        foreach ($this->widgetVisibility as $key => $visible) {
            DashboardWidgetSetting::updateOrCreate(
                ['role_name' => $this->selectedRole, 'widget_key' => $key],
                ['is_visible' => (bool) $visible]
            );
        }
        Notification::make()->success()->title('Dashboard widget settings saved for ' . $this->selectedRole)->send();
    }

    public function resetDefaults(): void
    {
        DashboardWidgetSetting::where('role_name', $this->selectedRole)->delete();
        DashboardWidgetSetting::seedDefaults();
        $this->loadRoleSettings($this->selectedRole);
        Notification::make()->success()->title('Reset to defaults for ' . $this->selectedRole)->send();
    }
}
