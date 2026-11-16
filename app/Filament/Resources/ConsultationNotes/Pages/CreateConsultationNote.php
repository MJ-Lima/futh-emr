<?php

namespace App\Filament\Resources\ConsultationNotes\Pages;

use App\Filament\Resources\ConsultationNotes\ConsultationNoteResource;
use App\Models\ConsultationNote;
use App\Models\Diagnosis;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateConsultationNote extends CreateRecord
{
    protected static string $resource = ConsultationNoteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert the clinic_specific_data_input textarea into a JSON object
        if (isset($data['clinic_specific_data_input']) && is_string($data['clinic_specific_data_input'])) {
            $lines = explode("\n", $data['clinic_specific_data_input']);
            $arr = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if (str_contains($line, ':')) {
                    [$k, $v] = explode(':', $line, 2);
                    $arr[trim($k)] = trim($v);
                }
            }
            $data['clinic_specific_data'] = $arr;
            unset($data['clinic_specific_data_input']);
        }

        // Save ICD-10 diagnoses as new diagnoses on the encounter
        if (!empty($data['new_diagnosis_icd10'])) {
            $this->afterCreateHook = function (ConsultationNote $record) use ($data) {
                foreach ($data['new_diagnosis_icd10'] as $code) {
                    $icd = \App\Models\Icd10Diagnosis::where('code', $code)->first();
                    if ($icd) {
                        Diagnosis::create([
                            'patient_id' => $record->patient_id,
                            'encounter_id' => $record->encounter_id,
                            'recorded_by' => auth()->id() ?: 1,
                            'icd10_code' => $icd->code,
                            'description' => $icd->description,
                            'type' => 'Working',
                            'status' => 'Active',
                        ]);
                    }
                }
            };
        }
        unset($data['new_diagnosis_icd10']);

        return $data;
    }

    protected ?\Closure $afterCreateHook = null;

    protected function afterCreate(): void
    {
        if ($this->afterCreateHook) {
            ($this->afterCreateHook)($this->record);
        }
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('view', ['record' => $this->record]);
    }
}
