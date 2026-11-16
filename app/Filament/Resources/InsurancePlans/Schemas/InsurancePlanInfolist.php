<?php

namespace App\Filament\Resources\InsurancePlans\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class InsurancePlanInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('InsurancePlan Information')
                    ->schema([
                        TextEntry::make('code')->label('Code'),
                        TextEntry::make('name')->label('Plan Name'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('tier')->label('Tier'),
                        TextEntry::make('coverage_type')->label('Coverage'),
                        TextEntry::make('annual_premium')->label('Annual Premium'),
                        TextEntry::make('annual_benefit_limit')->label('Benefit Limit'),
                        TextEntry::make('status')->label('Status'),
                    ])
                    ->columns(2),
            ]);
    }
}
