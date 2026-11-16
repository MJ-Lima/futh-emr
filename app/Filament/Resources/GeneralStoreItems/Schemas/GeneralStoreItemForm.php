<?php

namespace App\Filament\Resources\GeneralStoreItems\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class GeneralStoreItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('GeneralStoreItem Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hospital_id')->label('Hospital')->required()->relationship('hospital', 'name')->searchable()->preload(),
                            Select::make('general_store_category_id')->label('Category')->relationship('general_store_category', 'name')->searchable()->preload(),
                            TextInput::make('name')->label('Item Name')->required(),
                            TextInput::make('code')->label('Code')->required(),
                            TextInput::make('barcode')->label('Barcode'),
                            TextInput::make('generic_name')->label('Generic Name'),
                            TextInput::make('brand')->label('Brand'),
                            TextInput::make('manufacturer')->label('Manufacturer'),
                            TextInput::make('supplier')->label('Supplier'),
                            TextInput::make('unit_of_measure')->label('Unit of Measure')->required(),
                            TextInput::make('pack_size')->label('Pack Size'),
                            TextInput::make('units_per_pack')->label('Units per Pack')->numeric(),
                            TextInput::make('cost_price')->label('Cost Price')->numeric(),
                            TextInput::make('selling_price')->label('Selling Price')->numeric(),
                            TextInput::make('reorder_level')->label('Reorder Level')->numeric(),
                            TextInput::make('reorder_quantity')->label('Reorder Qty')->numeric(),
                            Toggle::make('is_consumable')->label('Consumable'),
                            Toggle::make('is_reusable')->label('Reusable'),
                            Toggle::make('requires_cold_chain')->label('Cold Chain'),
                            Toggle::make('is_controlled')->label('Controlled'),
                            Toggle::make('tracks_expiry')->label('Tracks Expiry'),
                            Toggle::make('tracks_serial')->label('Tracks Serial'),
                            Textarea::make('description')->label('Description'),
                            Toggle::make('is_active')->label('Active'),
                        ]),
                    ]),
            ]);
    }
}
