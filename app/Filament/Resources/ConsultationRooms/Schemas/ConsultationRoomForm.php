<?php

namespace App\Filament\Resources\ConsultationRooms\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class ConsultationRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Consultation Room')
                ->icon('heroicon-m-home-modern')
                ->description('A physical room in a clinic where patients see the doctor')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('hospital_id')
                            ->label('Hospital')
                            ->relationship('hospital', 'name')
                            ->searchable()->preload()->native(false)->required(),
                        Select::make('clinic_id')
                            ->label('Clinic')
                            ->relationship('clinic', 'name')
                            ->searchable()->preload()->native(false)->required(),
                        TextInput::make('name')->label('Room Name')
                            ->required()->placeholder('e.g. Consultation Room 1'),
                        TextInput::make('code')->label('Room Code')
                            ->required()->placeholder('e.g. R1'),
                        TextInput::make('capacity')->label('Capacity')
                            ->numeric()->default(1)->minValue(1)->maxValue(10)
                            ->helperText('How many patients can be in this room at the same time (usually 1)'),
                        Select::make('assigned_doctor_id')
                            ->label('Default Doctor (optional)')
                            ->relationship('assignedDoctor', 'name')
                            ->searchable()->preload()->native(false)
                            ->helperText('Doctor typically stationed in this room'),
                        Toggle::make('is_active')->label('Active')->default(true),
                    ]),
                ]),
        ]);
    }
}
