<?php

namespace App\Filament\Resources\ProviderApplications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProviderApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Review')
                ->schema([
                    Select::make('status')
                        ->options([
                            'pending'       => 'Pending',
                            'under_review'  => 'Under Review',
                            'approved'      => 'Approved',
                            'rejected'      => 'Rejected',
                            'withdrawn'    => 'Withdrawn',
                        ])
                        ->required(),
                    Textarea::make('review_notes')
                        ->label('Review notes')
                        ->rows(3)
                        ->helperText('Visible to administrators. Sent to the applicant on rejection.'),
                ]),
        ]);
    }
}
