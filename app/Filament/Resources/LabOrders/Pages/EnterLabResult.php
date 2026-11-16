<?php

namespace App\Filament\Resources\LabOrders\Pages;

use App\Filament\Resources\LabOrders\LabOrderResource;
use App\Models\LabOrder;
use App\Models\LabResult;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class EnterLabResult extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = LabOrderResource::class;

    protected string $view = 'filament.resources.lab-orders.pages.enter-lab-result';

    public LabOrder $record;

    public ?array $results = [];

    public function mount(LabOrder $record): void
    {
        $this->record = $record->load(['items.test.unit', 'items.result', 'patient', 'destinationLab']);

        foreach ($this->record->items as $item) {
            $this->results[$item->id] = [
                'value'           => $item->result?->value ?? '',
                'value_numeric'   => $item->result?->value_numeric ?? '',
                'unit'            => $item->result?->unit ?? $item->test?->unit ?? '',
                'reference_range' => $item->result?->reference_range ?? $item->test?->reference_range ?? '',
                'flag'            => $item->result?->flag ?? 'Normal',
                'interpretation'  => $item->result?->interpretation ?? '',
                'comments'        => $item->result?->comments ?? '',
            ];
        }
    }

    public function getTitle(): string
    {
        return "Enter Result · {$this->record->order_number}";
    }

    public function form(Form $form): Form
    {
        $sections = [];

        foreach ($this->record->items as $item) {
            $test = $item->test;
            $description = implode(' · ', array_filter([
                $test?->loinc_code ? "LOINC: {$test->loinc_code}" : null,
                $test?->specimen_type ? "Specimen: {$test->specimen_type}" : null,
                $item->unit?->name ? "Unit: {$item->unit->name}" : null,
            ]));

            $sections[] = Section::make($item->test_name)
                ->description($description)
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make("results.{$item->id}.value")
                            ->label('Result Value')
                            ->required()
                            ->placeholder('e.g. 12.5 or Positive'),
                        TextInput::make("results.{$item->id}.value_numeric")
                            ->label('Numeric Value')
                            ->numeric()
                            ->placeholder('For graphing / trending'),
                        TextInput::make("results.{$item->id}.unit")
                            ->label('Unit')
                            ->placeholder('e.g. g/dL, mg/L, x10^9/L'),
                    ]),
                    Grid::make(2)->schema([
                        TextInput::make("results.{$item->id}.reference_range")
                            ->label('Reference Range')
                            ->placeholder($test?->reference_range ?? 'e.g. 4.0 - 11.0'),
                        ToggleButtons::make("results.{$item->id}.flag")
                            ->label('Flag')
                            ->options([
                                'Normal'         => 'Normal',
                                'Low'            => 'Low',
                                'High'           => 'High',
                                'Critical Low'   => 'Critical Low',
                                'Critical High'  => 'Critical High',
                                'Abnormal'       => 'Abnormal',
                            ])
                            ->colors([
                                'Normal'         => 'success',
                                'Low'            => 'warning',
                                'High'           => 'warning',
                                'Critical Low'   => 'danger',
                                'Critical High'  => 'danger',
                                'Abnormal'       => 'danger',
                            ])
                            ->inline()
                            ->default('Normal'),
                    ]),
                    Textarea::make("results.{$item->id}.interpretation")
                        ->label('Interpretation / Microscopy Narrative')
                        ->rows(2)
                        ->placeholder('e.g. No parasites seen. Normal red cell morphology.'),
                    Textarea::make("results.{$item->id}.comments")
                        ->label('Comments')
                        ->rows(2),
                ]);
        }

        return $form->schema($sections);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save All Results')
                ->submit('saveResults')
                ->color('success')
                ->icon('heroicon-m-check'),
            Action::make('saveAndComplete')
                ->label('Save & Mark Complete')
                ->submit('saveAndComplete')
                ->color('primary')
                ->icon('heroicon-m-check-badge'),
            Action::make('cancel')
                ->label('Cancel')
                ->url(fn () => LabOrderResource::getUrl('view', ['record' => $this->record]))
                ->color('gray'),
        ];
    }

    public function saveResults(): void
    {
        $this->saveResultsInternal(markComplete: false);
        Notification::make()->success()->title('Results saved')->send();
    }

    public function saveAndComplete(): void
    {
        $this->saveResultsInternal(markComplete: true);
        Notification::make()->success()->title('Results saved — order marked Completed')->send();
        $this->redirect(LabOrderResource::getUrl('view', ['record' => $this->record]));
    }

    protected function saveResultsInternal(bool $markComplete): void
    {
        DB::transaction(function () use ($markComplete) {
            foreach ($this->results as $itemId => $data) {
                $item = $this->record->items()->find($itemId);
                if (!$item) {
                    continue;
                }

                LabResult::updateOrCreate(
                    ['lab_order_item_id' => $item->id],
                    array_merge($data, [
                        'lab_test_id' => $item->lab_test_id,
                        'performed_by' => auth()->id(),
                        'performed_at' => now(),
                    ])
                );

                $item->update(['status' => 'Completed']);
            }

            if ($markComplete) {
                $this->record->update(['status' => 'Completed']);
            } elseif ($this->record->status === 'Sample Collected') {
                $this->record->update(['status' => 'In Progress']);
            }
        });
    }
}
