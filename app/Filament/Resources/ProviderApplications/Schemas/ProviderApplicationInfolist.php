<?php

namespace App\Filament\Resources\ProviderApplications\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProviderApplicationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Application')
                ->schema([
                    TextEntry::make('application_number')->badge()->color('gray'),
                    TextEntry::make('account_type')->badge()->color(fn ($state) => $state === 'doctor' ? 'success' : 'info'),
                    TextEntry::make('status')->badge()->color(fn ($state) => match ($state) {
                        'pending'      => 'warning',
                        'under_review' => 'warning',
                        'approved'     => 'success',
                        'rejected'     => 'danger',
                        default        => 'gray',
                    }),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(4),

            Section::make('Applicant')
                ->schema([
                    TextEntry::make('title')->label('Title'),
                    TextEntry::make('applicant_name')->label('Name'),
                    TextEntry::make('email'),
                    TextEntry::make('phone'),
                ])->columns(4),

            Section::make('Doctor credentials')
                ->schema([
                    TextEntry::make('professional_registration_number')->label('Reg. number'),
                    TextEntry::make('specialty'),
                    TextEntry::make('qualification'),
                    TextEntry::make('years_of_experience')->label('Years experience'),
                    TextEntry::make('bio')->columnSpanFull(),
                ])->columns(4)
                ->visible(fn ($record) => $record->account_type === 'doctor'),

            Section::make('Facility')
                ->schema([
                    TextEntry::make('facility_name')->label('Name'),
                    TextEntry::make('facility_type')->label('Type'),
                    TextEntry::make('facility_license_number')->label('License number'),
                    TextEntry::make('facility_address')->columnSpanFull(),
                    TextEntry::make('facility_city')->label('City'),
                    TextEntry::make('facility_state')->label('State'),
                    TextEntry::make('facility_country')->label('Country'),
                    TextEntry::make('website')->label('Website'),
                ])->columns(3)
                ->visible(fn ($record) => in_array($record->account_type, ['hospital', 'clinic'])),

            Section::make('Services offered')
                ->schema([
                    TextEntry::make('services_offered')->bulleted()->columnSpanFull(),
                ])->visible(fn ($record) => ! empty($record->services_offered)),

            Section::make('Review')
                ->schema([
                    TextEntry::make('reviewedBy.name')->label('Reviewed by'),
                    TextEntry::make('reviewed_at')->dateTime(),
                    TextEntry::make('review_notes')->columnSpanFull(),
                    TextEntry::make('user_id')->label('Created user ID')->placeholder('—'),
                    TextEntry::make('hospital_id')->label('Created hospital ID')->placeholder('—'),
                ])->columns(3),
        ]);
    }
}
