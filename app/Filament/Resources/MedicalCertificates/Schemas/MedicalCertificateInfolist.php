<?php

namespace App\Filament\Resources\MedicalCertificates\Schemas;


use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use App\Models\MedicalCertificate;
class MedicalCertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Certificate Details')
                ->icon('heroicon-m-document-check')
                ->schema([
                    TextEntry::make('certificate_number')->label('Certificate #')->badge()->color('info')->copyable(),
                    TextEntry::make('certificate_type')->label('Type')->badge(),
                    TextEntry::make('patient_category')->label('Category')->badge()
                        ->color(fn ($state) => match ($state) {
                            'Inpatient'  => 'warning',
                            'Emergency'  => 'danger',
                            default      => 'success',
                        }),
                    TextEntry::make('status')->label('Status')->badge()
                        ->color(fn ($state) => MedicalCertificate::STATUS_COLORS[$state] ?? 'gray'),
                    TextEntry::make('issued_date')->label('Issued')->date('d M Y'),
                    TextEntry::make('valid_from')->label('Valid From')->date('d M Y')->placeholder('—'),
                    TextEntry::make('valid_until')->label('Valid Until')->date('d M Y')->placeholder('—'),
                    TextEntry::make('rest_days')->label('Rest Days')->placeholder('—'),
                    TextEntry::make('issued_at_location')->label('Issued At')->placeholder('—'),
                ])->columns(3),

            Section::make('Patient & Issuer')
                ->icon('heroicon-m-user')
                ->schema([
                    TextEntry::make('patient.full_name')->label('Patient Name'),
                    TextEntry::make('patient.hospital_no')->label('Hospital No'),
                    TextEntry::make('patient.age')->label('Age')->placeholder('—'),
                    TextEntry::make('patient.gender')->label('Gender')->formatStateUsing(fn ($s) => ucfirst($s ?? '')),
                    TextEntry::make('issuer.name')->label('Issued By'),
                    TextEntry::make('encounter_id')->label('Encounter #')->placeholder('—'),
                    TextEntry::make('admission_id')->label('Admission #')->placeholder('—'),
                ])->columns(3),

            Section::make('Clinical Content')
                ->icon('heroicon-m-clipboard-document')
                ->schema([
                    TextEntry::make('diagnosis')->label('Diagnosis')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('clinical_findings')->label('Clinical Findings')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('treatment_given')->label('Treatment Given')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('recommendations')->label('Recommendations')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('restrictions')->label('Restrictions')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('notes')->label('Notes')->placeholder('—')->columnSpanFull(),
                ]),
        ]);
    }
}
