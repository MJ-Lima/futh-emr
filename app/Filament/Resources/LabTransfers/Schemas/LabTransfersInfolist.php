<?php

namespace App\Filament\Resources\LabTransfers\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
class LabTransfersInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Lab Transfers Information')->schema([
                TextEntry::make('id')->label('ID'),
                TextColumn::make('labOrder.order_number')->label('Order #')->searchable()->weight('semibold'),
                TextColumn::make('fromLab.name')->label('From')->placeholder('—'),
                TextColumn::make('toLab.name')->label('To'),
                TextColumn::make('reason')->label('Reason')->searchable()->limit(40),
                TextColumn::make('status')->label('Status')->badge()->color('warning'),
                TextColumn::make('transferred_at')->label('Transferred')->dateTime('d M, H:i'),
            ])->columns(2),
        ]);
    }
}
