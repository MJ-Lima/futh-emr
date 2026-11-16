<?php

namespace App\Filament\Pages;

use App\Models\Admission;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReportingTable extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.pages.reporting-table';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-table-cells';

    protected static string|\UnitEnum|null $navigationGroup = 'Clinical';

    protected static ?string $navigationLabel = 'Reporting Table';

    protected static ?string $title = 'Reporting Table — Scientific / Records Officer';

    protected static ?int $navigationSort = 15;

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        // Visible to: Super Admin, Medical Records Officer, Scientific Officer, Medical Administrator, Doctor
        return $user->hasRole(['super_admin', 'medical_records_officer', 'medical_administrator', 'doctor', 'scientific_officer']);
    }

    public function getTitle(): string
    {
        return 'Reporting Table — Scientific / Records Officer';
    }

    protected function getTableQuery(): Builder
    {
        return Admission::with([
            'patient',
            'encounter',
            'admittingDoctor',
            'attendingDoctor',
            'residentDoctor',
            'dischargedBy',
            'ward',
        ])->latest('admission_date');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('patient.full_name')->label('Patient Name')->searchable()
                    ->description(fn (Admission $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('patient.gender')->label('Sex')->formatStateUsing(fn ($s) => ucfirst($s ?? '')),
                TextColumn::make('patient.age')->label('Age')->placeholder('—'),
                TextColumn::make('patient.phonemain')->label('Phone')->placeholder('—')->toggleable(),
                TextColumn::make('admission_number')->label('Admission #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('admission_date')->label('Date of Admission')->dateTime('d M Y')->sortable(),
                TextColumn::make('discharge_date')->label('Date of Discharge')->dateTime('d M Y')->placeholder('—')->sortable(),
                TextColumn::make('admission_diagnosis')->label('Admission Diagnosis')->limit(40)->wrap()->toggleable(),
                TextColumn::make('discharge_diagnosis')->label('Discharge Diagnosis')->limit(40)->wrap()->placeholder('—')->toggleable(),
                TextColumn::make('discharge_reason')->label('Reason for Discharge')->limit(40)->wrap()->placeholder('—')->toggleable(),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($s) => match ($s) {
                        'Admitted' => 'warning',
                        'Discharged' => 'success',
                        'Transferred' => 'info',
                        'Absconded' => 'danger',
                        'Death' => 'danger',
                        'Cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('ward.name')->label('Ward')->placeholder('—')->toggleable(),
                TextColumn::make('admittingDoctor.name')->label('Admitting Doctor')->placeholder('—')->toggleable(),
                TextColumn::make('residentDoctor.name')->label('resident_doctor')->placeholder('—')->toggleable(),
                TextColumn::make('attendingDoctor.name')->label('Attending Doctor')->placeholder('—')->toggleable(),
                TextColumn::make('dischargedBy.name')->label('Discharged By')->placeholder('—')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'Admitted' => 'Admitted',
                    'Discharged' => 'Discharged',
                    'Transferred' => 'Transferred',
                    'Absconded' => 'Absconded',
                    'Death' => 'Death',
                    'Cancelled' => 'Cancelled',
                ]),
                SelectFilter::make('ward_id')->label('Ward')->relationship('ward', 'name'),
                Filter::make('admission_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Admitted from'),
                        \Filament\Forms\Components\DatePicker::make('to')->label('Admitted to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('admission_date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('admission_date', '<=', $data['to']));
                    }),
                Filter::make('discharge_date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Discharged from'),
                        \Filament\Forms\Components\DatePicker::make('to')->label('Discharged to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('discharge_date', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('discharge_date', '<=', $data['to']));
                    }),
            ])
            ->actions([
                \Filament\Actions\Action::make('viewAdmission')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Admission $r): string => \App\Filament\Resources\Admissions\AdmissionResource::getUrl('view', ['record' => $r])),
            ]);
    }
}
