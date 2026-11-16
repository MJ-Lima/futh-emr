<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Vital;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NurseQueue extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected string $view = 'filament.pages.nurse-queue';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Front Desk';

    protected static ?string $navigationLabel = 'Nurse Queue (Vitals)';

    protected static ?string $title = 'Nurse Queue — Take Vitals';

    protected static ?int $navigationSort = 2;

    public ?array $vitalsData = [];

    public function getTitle(): string
    {
        return 'Nurse Queue — Take Vitals';
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (!$user) return false;
        return $user->hasRole(['super_admin', 'nurse', 'doctor', 'medical_administrator']);
    }

    /**
     * Scope the table to the current nurse's clinic (or all clinics for super admin).
     */
    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        $today = today()->toDateString();

        $query = Appointment::query()
            ->where('appointment_date', $today)
            ->whereIn('status', ['Checked In', 'In Nurse Queue'])
            ->where('vitals_status', '!=', 'Completed');

        // Non-super-admins see only their clinic (via duty posting or default_clinic_id)
        if (!$user->hasRole(['super_admin', 'medical_administrator'])) {
            $clinicId = $user->default_clinic_id
                ?? ($user->currentDutyPosting?->clinic_id ?? null);
            if ($clinicId) {
                $query->where('clinic_id', $clinicId);
            }
        }

        return $query->orderBy('queue_number')->orderBy('appointment_time');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('queue_number')->label('Queue #')->badge()->color('info'),
                TextColumn::make('appointment_number')->label('Appt #')->searchable(),
                TextColumn::make('patient.full_name')->label('Patient')
                    ->description(fn (Appointment $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('clinic.name')->label('Clinic'),
                TextColumn::make('visit_type')->label('Type')->badge()
                    ->color(fn ($s) => $s === 'New' ? 'danger' : 'success'),
                TextColumn::make('priority')->label('Priority')->badge()
                    ->color(fn ($s) => $s === 'Emergency' ? 'danger' : ($s === 'Urgent' ? 'warning' : 'gray')),
                TextColumn::make('card_fee_paid')->label('Card Fee')
                    ->icon(fn ($s, Appointment $r) => $r->requires_card_fee
                        ? ($s ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                        : 'heroicon-m-minus-circle')
                    ->iconColor(fn ($s, Appointment $r) => $r->requires_card_fee
                        ? ($s ? 'success' : 'danger')
                        : 'gray'),
                TextColumn::make('vitals_status')->label('Vitals Status')->badge()
                    ->formatStateUsing(fn ($s) => Appointment::VITALS_STATUSES[$s] ?? $s)
                    ->color(fn ($s) => Appointment::VITALS_STATUS_COLORS[$s] ?? 'gray'),
            ])
            ->actions([
                Action::make('startVitals')
                    ->label('Start Vitals')
                    ->icon('heroicon-m-play-circle')
                    ->color('info')
                    ->button()
                    ->visible(fn (Appointment $r) =>
                        $r->status === 'In Nurse Queue' && $r->vitals_status === 'Pending'
                        && (!$r->requires_card_fee || $r->card_fee_paid))
                    ->action(function (Appointment $r) {
                        $r->update(['vitals_status' => 'In Progress', 'nurse_id' => auth()->id()]);
                        Notification::make()->success()->title('Vitals in progress for ' . $r->patient->full_name)->send();
                    }),
                Action::make('recordVitals')
                    ->label('Record Vitals')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('success')
                    ->button()
                    ->visible(fn (Appointment $r) => $r->vitals_status === 'In Progress')
                    ->url(fn (Appointment $r): string =>
                        \App\Filament\Resources\Vitals\VitalResource::getUrl('create') . '?appointment_id=' . $r->id,
                        shouldOpenInNewTab: true),
                Action::make('completeVitals')
                    ->label('Complete & Allocate Room')
                    ->icon('heroicon-m-check-circle')
                    ->color('primary')
                    ->button()
                    ->requiresConfirmation()
                    ->modalDescription('Will auto-allocate a consultation room based on queue position.')
                    ->visible(fn (Appointment $r) => $r->vitals_status === 'In Progress')
                    ->action(function (Appointment $r) {
                        $r->update([
                            'vitals_status' => 'Completed',
                            'vitals_taken_at' => now(),
                        ]);
                        $room = \App\Models\ConsultationRoom::autoAllocateForAppointment($r);
                        if ($room) {
                            $r->update([
                                'status' => 'Waiting for Doctor',
                                'consultation_room_id' => $room->id,
                                'room_queue_position' => Appointment::where('consultation_room_id', $room->id)
                                    ->whereDate('appointment_date', today())
                                    ->max('room_queue_position') + 1,
                                'room_allocated_at' => now(),
                            ]);
                            Notification::make()->success()
                                ->title('Allocated to Room ' . $room->code . ' — ' . $room->name)
                                ->send();
                        } else {
                            $r->update(['status' => 'Vitals Taken']);
                            Notification::make()->warning()
                                ->title('Vitals complete — no consultation rooms configured for this clinic')
                                ->body('Please add consultation rooms under Front Desk → Consultation Rooms.')
                                ->send();
                        }
                    }),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
