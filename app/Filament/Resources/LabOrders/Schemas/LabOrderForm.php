<?php

namespace App\Filament\Resources\LabOrders\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LabOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lab Order Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('patient_id')->label('Patient')->required()
                                ->relationship('patient', 'hospital_no')
                                ->getOptionLabelFromRecordUsing(fn ($r) => "{$r->lastname} {$r->firstname} — {$r->hospital_no}")
                                ->searchable(['firstname', 'lastname', 'hospital_no', 'phonemain'])
                                ->preload()
                                ->native(false)
                                ->columnSpanFull(),
                            Select::make('encounter_id')->label('Encounter')
                                ->relationship('encounter', 'id')
                                ->searchable()
                                ->preload()
                                ->native(false),
                            Select::make('destination_lab_id')
                                ->label('Destination Lab')
                                ->relationship('destinationLab', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->native(false)
                                ->helperText('Choose which lab will process this order'),
                            Select::make('priority')->label('Priority')->required()
                                ->options([
                                    'Routine' => 'Routine', 'Urgent' => 'Urgent',
                                    'STAT' => 'STAT', 'ASAP' => 'ASAP',
                                ])
                                ->default('Routine')
                                ->native(false),
                            DateTimePicker::make('ordered_at')->label('Ordered At')->required()->default(now()),
                            TextInput::make('specimen_source')->label('Specimen Source')
                                ->placeholder('e.g. Venous blood, Mid-stream urine'),
                            TextInput::make('fasting_status')->label('Fasting Status')
                                ->placeholder('Fasting / Random / 2-hr post-prandial'),
                            Textarea::make('clinical_notes')->label('Clinical Notes')->rows(3)->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Ordered Tests')
                    ->description('Add one or more lab tests to this order. Each test will be routed to its lab unit (e.g. Haematology, Microbiology).')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->label('Test Items')
                            ->schema([
                                Select::make('lab_test_id')
                                    ->label('Lab Test')
                                    ->relationship('test', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) {
                                        $test = \App\Models\LabTest::find($state);
                                        if ($test) {
                                            $set('test_name', $test->name);
                                            $set('loinc_code', $test->loinc_code);
                                            $set('price', $test->price);
                                            $set('lab_unit_id', $test->lab_unit_id);
                                        }
                                    })
                                    ->columnSpan(2),
                                TextInput::make('test_name')->hidden()->label('Test Name'),
                                TextInput::make('loinc_code')->label('LOINC')->disabled(),
                                TextInput::make('price')->label('Price ₦')->numeric()->disabled(),
                                Select::make('lab_unit_id')
                                    ->label('Lab Unit')
                                    ->relationship('unit', 'name')
                                    ->native(false),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
