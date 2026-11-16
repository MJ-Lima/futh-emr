<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Cashier extends Page
{
    protected string $view = 'filament.pages.cashier';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-banknotes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Front Desk';
    }

    public static function getNavigationLabel(): string
    {
        return 'Cashier / Payments';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole([
            'super_admin', 'accountant', 'medical_records_officer',
            'insurance_officer', 'medical_administrator', 'cashier',
        ]) ?? false;
    }

    public function mount(): void
    {
        redirect()->to('/admin/appointments');
    }
}