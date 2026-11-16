<?php

namespace App\Filament\Resources\PatientPortalUsers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientPortalUserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Portal account')
                ->schema([
                    TextEntry::make('username'),
                    TextEntry::make('email'),
                    TextEntry::make('phone'),
                    TextEntry::make('status')->badge()->color(fn ($state) => match ($state) {
                        'active'    => 'success',
                        'pending'   => 'warning',
                        'suspended' => 'danger',
                        'locked'    => 'danger',
                        default      => 'gray',
                    }),
                ])->columns(4),

            Section::make('Linked patient')
                ->schema([
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('patient.full_name')->label('Patient name'),
                    TextEntry::make('patient.email')->label('Patient email'),
                    TextEntry::make('patient.hospital.name')->label('Hospital'),
                    TextEntry::make('patient.verification_status')->label('Patient verification')->badge(),
                ])->columns(4),

            Section::make('Activity')
                ->schema([
                    TextEntry::make('email_verified_at')->dateTime()->placeholder('Not verified'),
                    TextEntry::make('approved_at')->dateTime()->placeholder('Not approved'),
                    TextEntry::make('approvedBy.name')->label('Approved by')->placeholder('—'),
                    TextEntry::make('last_login_at')->dateTime()->placeholder('Never'),
                    TextEntry::make('last_login_ip')->placeholder('—'),
                    TextEntry::make('created_at')->dateTime(),
                ])->columns(3),
        ]);
    }
}
