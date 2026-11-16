<?php

namespace App\Filament\Resources\Clinics\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use App\Models\Clinic;
class ClinicInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hospital.name')
                    ->label('Hospital'),
                TextEntry::make('name'),
                TextEntry::make('code')
                    ->placeholder('-'),
                IconEntry::make('status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Clinic $record): bool => $record->trashed()),
            ]);
    }
}
