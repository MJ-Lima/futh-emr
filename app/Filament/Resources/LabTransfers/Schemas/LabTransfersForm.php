<?php

namespace App\Filament\Resources\LabTransfers\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class LabTransfersForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lab Transfers Details')->schema([
                Grid::make(2)->schema([
                    Select::make('lab_order_id')->label('Lab Order')->required()
                        ->relationship('labOrder', 'order_number')->searchable()->preload()->native(false),
                    Select::make('from_laboratory_id')->label('From Lab')
                        ->relationship('fromLab', 'name')->native(false),
                    Select::make('to_laboratory_id')->label('To Lab')->required()
                        ->relationship('toLab', 'name')->native(false),
                    Select::make('transferred_by')->label('Transferred By')
                        ->relationship('transferredBy', 'name')->native(false),
                    TextInput::make('reason')->label('Reason')->required(),
                    Select::make('status')->label('Status')->required()->options([
                        'Pending' => 'Pending', 'Accepted' => 'Accepted',
                        'Rejected' => 'Rejected', 'Cancelled' => 'Cancelled',
                    ])->native(false),
                    Textarea::make('notes')->rows(2)->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
