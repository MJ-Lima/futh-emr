<?php

namespace App\Filament\Resources\StoreRequisitions\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class StoreRequisitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('StoreRequisition Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('requesting_department_id')->label('Requesting Department')->required()->relationship('requesting_department', 'name')->searchable()->preload(),
                            Select::make('requesting_location_id')->label('Requesting Location')->relationship('requesting_location', 'name')->searchable()->preload(),
                            Select::make('source_location_id')->label('Source Store')->relationship('source_location', 'name')->searchable()->preload(),
                            DatePicker::make('required_date')->label('Required Date')->required(),
                            Select::make('priority')->label('Priority')->required()->options(['Routine' => 'Routine', 'Urgent' => 'Urgent', 'Emergency' => 'Emergency'])->searchable(),
                            Textarea::make('purpose')->label('Purpose'),
                            Textarea::make('notes')->label('Notes'),
                            Select::make('status')->label('Status')->required()->options(['Draft' => 'Draft', 'Pending' => 'Pending', 'Approved' => 'Approved', 'Partially Issued' => 'Partially Issued', 'Issued' => 'Issued', 'Received' => 'Received', 'Rejected' => 'Rejected', 'Cancelled' => 'Cancelled'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
