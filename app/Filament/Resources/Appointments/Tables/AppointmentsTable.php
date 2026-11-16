<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('appointment_number')->label('Appt #')->searchable()->weight('semibold')->copyable(),
                TextColumn::make('queue_number')->label('Queue #')->badge()->color('info')->placeholder('—'),
                TextColumn::make('patient.full_name')->label('Patient')->sortable()->searchable()
                    ->description(fn (Appointment $r): string => $r->patient->hospital_no ?? ''),
                TextColumn::make('clinic.name')->label('Clinic')->sortable(),
                TextColumn::make('visit_type')->label('Type')->badge()
                    ->color(fn ($s) => match ($s) {
                        'New'          => 'danger',
                        'Follow-up'    => 'success',
                        'Review'       => 'info',
                        'Telemedicine' => 'gray',
                        default        => 'gray',
                    }),
                TextColumn::make('appointment_date')->label('Date')->date()->sortable(),
                TextColumn::make('appointment_time')->label('Time')->time(),
                TextColumn::make('priority')->label('Priority')->badge()
                    ->color(fn ($s) => match ($s) {
                        'Emergency' => 'danger',
                        'Urgent'    => 'warning',
                        default      => 'gray',
                    }),
                TextColumn::make('card_fee_paid')->label('Card Fee')
                    ->icon(fn ($s, Appointment $r) => $r->requires_card_fee
                        ? ($s ? 'heroicon-m-check-circle' : 'heroicon-m-x-circle')
                        : 'heroicon-m-minus-circle')
                    ->iconColor(fn ($s, Appointment $r) => $r->requires_card_fee
                        ? ($s ? 'success' : 'danger')
                        : 'gray')
                    ->tooltip(fn (Appointment $r) => $r->requires_card_fee
                        ? ($r->card_fee_paid ? 'Paid ₦' . number_format($r->card_fee_amount) : 'Required, not paid')
                        : 'Not required (follow-up)'),
                TextColumn::make('vitals_status')->label('Vitals')->badge()
                    ->formatStateUsing(fn ($s) => Appointment::VITALS_STATUSES[$s] ?? $s ?? '—')
                    ->color(fn ($s) => Appointment::VITALS_STATUS_COLORS[$s] ?? 'gray')
                    ->placeholder('—'),
                TextColumn::make('consultationRoom.code')->label('Room')->badge()->placeholder('—')->color('warning'),
                TextColumn::make('status')->label('Status')->badge()
                    ->formatStateUsing(fn ($s) => Appointment::STATUSES[$s] ?? $s)
                    ->color(fn ($s) => Appointment::STATUS_COLORS[$s] ?? 'gray'),
            ])
            ->filters([
                SelectFilter::make('status')->options(Appointment::STATUSES),
                SelectFilter::make('visit_type')->options(Appointment::VISIT_TYPES),
                SelectFilter::make('clinic_id')->label('Clinic')->relationship('clinic', 'name'),
                SelectFilter::make('vitals_status')->label('Vitals')->options(Appointment::VITALS_STATUSES),
            ])
            ->recordActions([
                // Step 1: Pay Card Fee (only for NEW patients with unpaid card fee)
                Action::make('payCardFee')
                    ->label('Pay Card Fee')
                    ->icon('heroicon-m-credit-card')
                    ->color('warning')
                    ->button()
                    ->visible(fn (Appointment $r) => $r->requires_card_fee && !$r->card_fee_paid)
                    ->form([
                        TextInput::make('amount')->label('Card Fee Amount (₦)')
                            ->numeric()->default(1000)->minValue(0)->required(),
                        Select::make('payment_method')->label('Payment Method')
                            ->options(Appointment::PAYMENT_METHODS)->default('Cash')->required()->native(false),
                        TextInput::make('receipt_no')->label('Receipt No')
                            ->placeholder('Auto-generated if blank'),
                    ])
                    ->action(function (array $data, Appointment $r) {
                        $r->update([
                            'card_fee_paid' => true,
                            'card_fee_amount' => $data['amount'],
                            'card_fee_payment_method' => $data['payment_method'],
                            'card_fee_receipt_no' => $data['receipt_no'] ?: 'RCP-' . now()->format('Y') . '-' . str_pad((string) $r->id, 6, '0', STR_PAD_LEFT),
                            'card_fee_received_by' => auth()->id(),
                            'card_fee_paid_at' => now(),
                        ]);
                        Notification::make()->success()
                            ->title('Card fee paid: ₦' . number_format($data['amount']))
                            ->body('Patient can now proceed to the nurse queue.')
                            ->send();
                    }),

                // Step 2: Check In (move to "Checked In")
                Action::make('checkIn')
                    ->label('Check In Patient')
                    ->icon('heroicon-m-arrow-right-on-rectangle')
                    ->color('info')
                    ->button()
                    ->visible(fn (Appointment $r) => in_array($r->status, ['Scheduled', 'Confirmed']))
                    ->action(function (Appointment $r) {
                        $r->update([
                            'status' => 'Checked In',
                            'checked_in_at' => now(),
                            'queue_number' => $r->queue_number ?: Appointment::generateQueueNumber($r->clinic_id, $r->hospital_id),
                        ]);
                        Notification::make()->success()->title('Patient checked in. Queue #: ' . $r->fresh()->queue_number)->send();
                    }),

                // Step 3: Send to Nurse Queue (only after card fee is paid for new patients)
                Action::make('sendToNurse')
                    ->label('Send to Nurse')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->color('primary')
                    ->button()
                    ->visible(fn (Appointment $r) =>
                        in_array($r->status, ['Checked In']) && $r->vitals_status === 'Pending'
                        && (!$r->requires_card_fee || $r->card_fee_paid)
                    )
                    ->action(function (Appointment $r) {
                        $r->update(['status' => 'In Nurse Queue', 'vitals_status' => 'Pending']);
                        Notification::make()->success()
                            ->title('Patient sent to nurse queue at ' . $r->clinic->name)
                            ->send();
                    }),

                // Step 4: Complete Vitals + Auto-Allocate Room
                Action::make('completeVitals')
                    ->label('Complete Vitals')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->button()
                    ->visible(fn (Appointment $r) => $r->status === 'In Nurse Queue' && $r->vitals_status !== 'Completed')
                    ->requiresConfirmation()
                    ->modalDescription('Will mark vitals as completed and auto-allocate a consultation room based on queue number.')
                    ->action(function (Appointment $r) {
                        $r->update([
                            'vitals_status' => 'Completed',
                            'vitals_taken_at' => now(),
                            'nurse_id' => auth()->id(),
                        ]);

                        // Auto-allocate a consultation room
                        $room = \App\Models\ConsultationRoom::autoAllocateForAppointment($r);
                        if ($room) {
                            $r->update([
                                'status' => 'Waiting for Doctor',
                                'consultation_room_id' => $room->id,
                                'room_queue_position' => self::getNextRoomQueuePosition($room),
                                'room_allocated_at' => now(),
                            ]);
                            Notification::make()->success()
                                ->title('Vitals complete — allocated to Room ' . $room->code . ' (' . $room->name . ')')
                                ->body('Patient is now waiting for the doctor.')
                                ->send();
                        } else {
                            $r->update(['status' => 'Vitals Taken']);
                            Notification::make()->warning()
                                ->title('Vitals complete but no available consultation rooms at this clinic')
                                ->body('Add more consultation rooms for this clinic, or move the patient manually.')
                                ->send();
                        }
                    }),

                // Step 5: Start Consultation
                Action::make('startConsultation')
                    ->label('Start Consultation')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('success')
                    ->button()
                    ->visible(fn (Appointment $r) => $r->status === 'Waiting for Doctor')
                    ->requiresConfirmation()
                    ->modalDescription('Will create a new Encounter and mark this appointment as In Consultation.')
                    ->action(function (Appointment $r) {
                        $encounter = \App\Models\Encounter::create([
                            'hospital_id' => $r->hospital_id,
                            'patient_id' => $r->patient_id,
                            'clinic_id' => $r->clinic_id,
                            'provider_id' => $r->provider_id ?? auth()->id(),
                            'appointment_id' => $r->id,
                            'encounter_date' => now(),
                            'visit_type' => $r->visit_type,
                            'status' => 'In Progress',
                        ]);
                        
                        $r->update([
                            'status' => 'In Consultation',
                            'encounter_id' => $encounter->id,
                            'consultation_started_at' => now(),
                        ]);
                        
                        Notification::make()->success()
                            ->title('Consultation started — Encounter #' . $encounter->id)
                            ->body('You can now record clinical notes, diagnoses, prescriptions.')
                            ->send();
                    }),

                // Step 6: End Consultation
                Action::make('endConsultation')
                    ->label('End Consultation')
                    ->icon('heroicon-m-check-badge')
                    ->color('primary')
                    ->button()
                    ->visible(fn (Appointment $r) => $r->status === 'In Consultation')
                    ->requiresConfirmation()
                    ->modalDescription('Will mark the encounter as Completed and free up the consultation room.')
                    ->action(function (Appointment $r) {
                        if ($r->encounter) {
                            $r->encounter->update(['status' => 'Completed']);
                        }
                        
                        $r->update([
                            'status' => 'Completed',
                            'consultation_ended_at' => now(),
                        ]);
                        
                        Notification::make()->success()
                            ->title('Consultation ended')
                            ->body('Patient flow complete.')
                            ->send();
                    }),

                               // Quick Edit / View
                ViewAction::make(),
                EditAction::make(),
            ], position: RecordActionsPosition::BeforeColumns)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
     * Get the next room queue position (position 1 = first in line, 2 = second, etc.).
     */
    public static function getNextRoomQueuePosition(\App\Models\ConsultationRoom $room): int
    {
        $max = Appointment::where('consultation_room_id', $room->id)
            ->whereDate('appointment_date', today())
            ->max('room_queue_position');
        return ((int) $max) + 1;
    }
}
