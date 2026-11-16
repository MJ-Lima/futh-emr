<?php

namespace App\Filament\Resources\PreAuthorizations\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class PreAuthorizationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('PreAuthorization Information')
                    ->schema([
                        TextEntry::make('authorization_number')->label('Auth #'),
                        TextEntry::make('patient.full_name')->label('Patient'),
                        TextEntry::make('provider.name')->label('Provider'),
                        TextEntry::make('service_type')->label('Service'),
                        TextEntry::make('estimated_cost')->label('Est. Cost'),
                        TextEntry::make('approved_amount')->label('Approved'),
                        TextEntry::make('requested_date')->label('Requested')->date(),
                        TextEntry::make('priority')->label('Priority'),
                    ])
                    ->columns(2),
            ]);
    }
}
