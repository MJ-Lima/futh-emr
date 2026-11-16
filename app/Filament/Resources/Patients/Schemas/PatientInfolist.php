<?php

namespace App\Filament\Resources\Patients\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hospital_no'),
                TextEntry::make('firstname'),
                TextEntry::make('middlename'),
                TextEntry::make('lastname'),
                TextEntry::make('dob')
                    ->date(),
                TextEntry::make('gender'),
                TextEntry::make('bloodgroup')
                    ->placeholder('-'),
                TextEntry::make('genotype')
                    ->placeholder('-'),
                TextEntry::make('marital')
                    ->placeholder('-'),
                TextEntry::make('religion')
                    ->placeholder('-'),
                TextEntry::make('occupation')
                    ->placeholder('-'),
                TextEntry::make('phonemain'),
                TextEntry::make('phonealt')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('address')
                    ->columnSpanFull(),
                TextEntry::make('city'),
                TextEntry::make('states'),
                TextEntry::make('country'),
                TextEntry::make('postalcode')
                    ->placeholder('-'),
                TextEntry::make('nokname'),
                TextEntry::make('relationship'),
                TextEntry::make('nokphone'),
                TextEntry::make('nokaddress')
                    ->columnSpanFull(),
                TextEntry::make('hereabout')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-'),
                IconEntry::make('status')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
