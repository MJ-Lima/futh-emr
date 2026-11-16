<?php

namespace App\Filament\Resources\SavedReports\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
class SavedReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('SavedReport Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('report_template_id')->label('Report Template')->relationship('report_template', 'name')->searchable()->preload(),
                            TextInput::make('title')->label('Title')->required(),
                            Select::make('category')->label('Category')->required()->options(['Patient' => 'Patient', 'Clinical' => 'Clinical', 'Financial' => 'Financial', 'Pharmacy' => 'Pharmacy', 'Laboratory' => 'Laboratory', 'Radiology' => 'Radiology', 'Insurance' => 'Insurance', 'HR' => 'HR', 'Inventory' => 'Inventory', 'Audit' => 'Audit', 'Mortality' => 'Mortality', 'Morbidity' => 'Morbidity', 'Infection' => 'Infection', 'Quality' => 'Quality', 'Operational' => 'Operational', 'Other' => 'Other'])->searchable(),
                            DatePicker::make('period_from')->label('Period From'),
                            DatePicker::make('period_to')->label('Period To'),
                            Select::make('department_id')->label('Department')->relationship('department', 'name')->searchable()->preload(),
                            Select::make('clinic_id')->label('Clinic')->relationship('clinic', 'name')->searchable()->preload(),
                            Select::make('user_id')->label('User')->relationship('user', 'name')->searchable()->preload(),
                            Select::make('format')->label('Format')->required()->options(['HTML' => 'HTML', 'PDF' => 'PDF', 'Excel' => 'Excel', 'CSV' => 'CSV', 'JSON' => 'JSON'])->searchable(),
                            Select::make('status')->label('Status')->required()->options(['Generating' => 'Generating', 'Completed' => 'Completed', 'Failed' => 'Failed', 'Archived' => 'Archived'])->searchable(),
                        ]),
                    ]),
            ]);
    }
}
