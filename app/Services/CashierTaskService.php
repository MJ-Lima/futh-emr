<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\AppointmentType;
use App\Models\CashierTask;
use App\Models\Encounter;
use App\Models\Invoice;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\RadiologyOrder;
use App\Models\RadiologyModality;
use App\Models\Service;
use App\Models\Vitals;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Auto-drops a CashierTask when any billable service is created. Each task
 * represents an outstanding payment the cashier needs to collect before the
 * patient can fully receive the service (lab work, imaging, dispensed drugs,
 * invoiced charges, appointment card fees, consultation fees, nursing fees).
 *
 * The service is conservative — it only creates a task when there is a
 * positive amount due. Tasks are never duplicated for the same source.
 */
class CashierTaskService
{
    /**
     * Auto-register model listeners. Call from AppServiceProvider::boot().
     */
    public static function registerListeners(): void
    {
        // New invoice with a positive balance
        Invoice::created(function (Invoice $invoice) {
            if ($invoice->balance_due > 0) {
                app(self::class)->createForInvoice($invoice);
            }
        });

        // New lab order — total cost = sum(item.price)
        LabOrder::created(function (LabOrder $order) {
            app(self::class)->createForLabOrder($order);
        });

        // New radiology order — use modality.price
        RadiologyOrder::created(function (RadiologyOrder $order) {
            app(self::class)->createForRadiologyOrder($order);
        });

        // New prescription — sum(drug.selling_price * quantity) as estimate
        Prescription::created(function (Prescription $rx) {
            app(self::class)->createForPrescription($rx);
        });

        // New appointment with an unpaid card fee
        Appointment::created(function (Appointment $apt) {
            app(self::class)->createForAppointmentCardFee($apt);
        });

        // New encounter — look up the consultation fee from the linked
        // appointment's type, or a consultation service in the catalog
        Encounter::created(function (Encounter $enc) {
            app(self::class)->createForConsultation($enc);
        });

        // Vitals recorded — nursing service fee
        Vitals::created(function (Vitals $vitals) {
            app(self::class)->createForNursingService($vitals);
        });
    }

    // ------------------------------------------------------------------
    //  Per-source create methods
    // ------------------------------------------------------------------

    public function createForInvoice(Invoice $invoice): ?CashierTask
    {
        if ($this->hasExistingTask('Invoice', $invoice->id)) {
            return null;
        }
        return $this->createTask([
            'hospital_id'    => $invoice->hospital_id,
            'patient_id'     => $invoice->patient_id,
            'encounter_id'   => $invoice->encounter_id,
            'source_type'    => 'Invoice',
            'source_id'      => $invoice->id,
            'invoice_id'     => $invoice->id,
            'description'    => 'Invoice ' . $invoice->invoice_number,
            'amount_due'     => $invoice->balance_due,
        ]);
    }

    public function createForLabOrder(LabOrder $order): ?CashierTask
    {
        if ($this->hasExistingTask('LabOrder', $order->id)) {
            return null;
        }
        $amount = $order->items()->sum('price') ?: 0;
        if ($amount <= 0) {
            return null;
        }
        return $this->createTask([
            'hospital_id'    => $order->hospital_id,
            'patient_id'     => $order->patient_id,
            'encounter_id'   => $order->encounter_id,
            'source_type'    => 'LabOrder',
            'source_id'      => $order->id,
            'description'    => 'Lab order ' . $order->order_number,
            'amount_due'     => $amount,
        ]);
    }

    public function createForRadiologyOrder(RadiologyOrder $order): ?CashierTask
    {
        if ($this->hasExistingTask('RadiologyOrder', $order->id)) {
            return null;
        }
        $modality = RadiologyModality::find($order->modality_id);
        $amount = $modality?->price ?: 0;
        if ($amount <= 0) {
            return null;
        }
        return $this->createTask([
            'hospital_id'    => $order->hospital_id,
            'patient_id'     => $order->patient_id,
            'encounter_id'   => $order->encounter_id,
            'source_type'    => 'RadiologyOrder',
            'source_id'      => $order->id,
            'description'    => 'Radiology: ' . $order->examination,
            'amount_due'     => $amount,
        ]);
    }

    public function createForPrescription(Prescription $rx): ?CashierTask
    {
        if ($this->hasExistingTask('Prescription', $rx->id)) {
            return null;
        }
        $amount = DB::table('prescription_items as pi')
            ->leftJoin('drugs as d', 'pi.drug_id', '=', 'd.id')
            ->where('pi.prescription_id', $rx->id)
            ->selectRaw('COALESCE(SUM(d.selling_price * pi.quantity), 0) as total')
            ->value('total') ?: 0;

        if ($amount <= 0) {
            return null;
        }
        return $this->createTask([
            'hospital_id'    => $rx->hospital_id,
            'patient_id'     => $rx->patient_id,
            'encounter_id'   => $rx->encounter_id,
            'source_type'    => 'Prescription',
            'source_id'      => $rx->id,
            'description'    => 'Prescription ' . $rx->prescription_number,
            'amount_due'     => $amount,
        ]);
    }

    /**
     * Card fee — charged when a new patient registers for an appointment.
     * Triggered by Appointment::created when card_fee_amount > 0 and
     * card_fee_paid is false.
     */
    public function createForAppointmentCardFee(Appointment $apt): ?CashierTask
    {
        $amount = (float) ($apt->card_fee_amount ?? 0);
        if ($amount <= 0 || (bool) ($apt->card_fee_paid ?? false)) {
            return null;
        }
        if ($this->hasExistingTask('AppointmentCardFee', $apt->id)) {
            return null;
        }
        return $this->createTask([
            'hospital_id'    => $apt->hospital_id,
            'patient_id'     => $apt->patient_id,
            'appointment_id' => $apt->id,
            'source_type'    => 'AppointmentCardFee',
            'source_id'      => $apt->id,
            'description'    => 'Card fee — Appointment ' . $apt->appointment_number,
            'amount_due'     => $amount,
        ]);
    }

    /**
     * Consultation fee — charged when an encounter is created (the patient
     * sees the doctor). The fee is looked up from the linked appointment's
     * appointment_type.default_fee, or from a Service in the catalog whose
     * category = 'Consultation' and name matches the visit_type.
     */
    public function createForConsultation(Encounter $enc): ?CashierTask
    {
        if ($this->hasExistingTask('Consultation', $enc->id)) {
            return null;
        }

        $amount = 0;
        $description = 'Consultation — ' . $enc->encounter_number;

        // 1) Try the linked appointment's appointment_type.default_fee
        if ($enc->appointment_id) {
            $apt = Appointment::with('appointmentType')->find($enc->appointment_id);
            if ($apt && $apt->appointmentType) {
                $amount = (float) $apt->appointmentType->default_fee;
                $description = 'Consultation (' . $apt->appointmentType->name . ')';
            }
        }

        // 2) Fallback: look up a Service by category = 'Consultation'
        if ($amount <= 0) {
            $visitType = $enc->visit_type;
            $service = $this->lookupConsultationService($enc->hospital_id, $visitType);
            if ($service) {
                $amount = (float) $service->standard_price;
                $description = 'Consultation (' . $service->name . ')';
            }
        }

        // 3) Final fallback: use a configurable default
        if ($amount <= 0) {
            $amount = (float) config('futh.cashier.default_consultation_fee', 0);
        }

        if ($amount <= 0) {
            return null;
        }

        return $this->createTask([
            'hospital_id'    => $enc->hospital_id,
            'patient_id'     => $enc->patient_id,
            'encounter_id'   => $enc->id,
            'appointment_id' => $enc->appointment_id,
            'source_type'    => 'Consultation',
            'source_id'      => $enc->id,
            'description'    => $description,
            'amount_due'     => $amount,
        ]);
    }

    /**
     * Nursing service fee — charged when vitals are recorded (the nurse has
     * rendered nursing services). The fee is looked up from an active Service
     * whose name contains 'nursing'. If none is found, a configurable default
     * is used.
     */
    public function createForNursingService(Vitals $vitals): ?CashierTask
    {
        if ($this->hasExistingTask('NursingService', $vitals->id)) {
            return null;
        }

        $amount = 0;
        $description = 'Nursing service — Vitals ' . $vitals->recorded_at?->format('d M Y H:i');

        // Look up an active nursing service in the catalog
        $service = Service::where('hospital_id', $vitals->hospital_id)
            ->where('status', 'Active')
            ->where('name', 'LIKE', '%nursing%')
            ->orWhere('name', 'LIKE', '%Nursing%')
            ->first();

        if ($service) {
            $amount = (float) $service->standard_price;
            $description = 'Nursing service (' . $service->name . ')';
        }

        // Fallback: configurable default
        if ($amount <= 0) {
            $amount = (float) config('futh.cashier.default_nursing_fee', 0);
        }

        if ($amount <= 0) {
            return null;
        }

        return $this->createTask([
            'hospital_id'    => $vitals->hospital_id,
            'patient_id'     => $vitals->patient_id,
            'encounter_id'   => $vitals->encounter_id,
            'source_type'    => 'NursingService',
            'source_id'      => $vitals->id,
            'description'    => $description,
            'amount_due'     => $amount,
        ]);
    }

    // ------------------------------------------------------------------
    //  Payment recording
    // ------------------------------------------------------------------

    /**
     * Mark a cashier task as paid (called by the cashier collection action).
     */
    public function markPaid(CashierTask $task, array $data): void
    {
        DB::transaction(function () use ($task, $data) {
            $task->update([
                'status'             => $data['amount_paid'] >= $task->amount_due ? 'paid' : 'short_paid',
                'amount_paid'        => $data['amount_paid'],
                'paid_at'            => now(),
                'payment_method'     => $data['payment_method'] ?? null,
                'receipt_number'     => $data['receipt_number'] ?? null,
                'assigned_cashier_id' => $data['cashier_id'] ?? Auth::id(),
                'notes'              => $data['notes'] ?? $task->notes,
            ]);

            // If the source is an invoice, also update the invoice
            if ($task->source_type === 'Invoice' && $task->invoice_id) {
                $invoice = Invoice::find($task->invoice_id);
                if ($invoice) {
                    $invoice->increment('amount_paid', $task->amount_paid);
                    $invoice->decrement('balance_due', $task->amount_paid);
                    if ($invoice->balance_due <= 0) {
                        $invoice->update(['status' => 'Paid']);
                    } elseif ($invoice->amount_paid > 0) {
                        $invoice->update(['status' => 'Partially Paid']);
                    }
                }
            }

            // If the source is an appointment card fee, mark the appointment
            if ($task->source_type === 'AppointmentCardFee' && $task->appointment_id) {
                $apt = Appointment::find($task->appointment_id);
                if ($apt) {
                    $apt->update([
                        'card_fee_paid'             => true,
                        'card_fee_paid_at'          => now(),
                        'card_fee_payment_method'   => $data['payment_method'] ?? null,
                        'card_fee_receipt_no'       => $data['receipt_number'] ?? $task->task_number,
                        'card_fee_received_by'      => Auth::id(),
                    ]);
                }
            }
        });
    }

    // ------------------------------------------------------------------
    //  Helpers
    // ------------------------------------------------------------------

    /**
     * Look up a consultation Service by visit_type. Maps the encounter's
     * visit_type enum to matching service names in the catalog.
     */
    protected function lookupConsultationService(int $hospitalId, string $visitType): ?Service
    {
        $nameMap = [
            'Outpatient'   => 'New Consultation',
            'Emergency'    => 'Emergency Consultation',
            'Inpatient'    => 'New Consultation',
            'Telemedicine'  => 'Telemedicine Consultation',
            'Follow-up'     => 'Follow-up Consultation',
        ];

        $name = $nameMap[$visitType] ?? 'New Consultation';

        return Service::where('hospital_id', $hospitalId)
            ->where('status', 'Active')
            ->where('category', 'Consultation')
            ->where('name', $name)
            ->first()
            ?? Service::where('hospital_id', $hospitalId)
                ->where('status', 'Active')
                ->where('category', 'Consultation')
                ->first();
    }

    protected function hasExistingTask(string $sourceType, int $sourceId): bool
    {
        return CashierTask::where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->where('status', '!=', 'cancelled')
            ->exists();
    }

    protected function createTask(array $attributes): CashierTask
    {
        return CashierTask::create(array_merge([
            'status'      => 'pending',
            'amount_paid' => 0,
        ], $attributes));
    }
}
