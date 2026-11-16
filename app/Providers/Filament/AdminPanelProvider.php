<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->profile(isSimple: false)
            ->brandName('FUTH Lafia EMR')
            ->brandLogo(asset('futh-logo.jpeg'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('futh-logo.jpeg'))
            ->colors([
                'primary'  => Color::hex('#0f5132'),
                'success'  => Color::hex('#198754'),
                'warning'  => Color::hex('#f59e0b'),
                'danger'   => Color::hex('#dc2626'),
                'info'     => Color::hex('#0284c7'),
                'emerald'  => Color::Emerald,
            ])
            ->navigationGroups([
                'Front Desk',
                'Clinical',
                'Pharmacy',
                'Laboratory',
                'Radiology',
                'Inpatient',
                'Insurance',
                'General Stores',
                'Blood Bank',
                'Audit',
                'Accounts',
                'Administration',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->resources([
                \App\Filament\Resources\Roles\RoleResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                Widgets\StatsOverviewWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnforceCashierShift::class,
                    ])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('<link rel="stylesheet" href="' . asset('css/futh-emr-theme.css') . '"><meta name="futh-emr-version" content="2.0">')
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('<x-filament-branding />')
            );
    }
}
