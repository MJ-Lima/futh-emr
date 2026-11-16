<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Creates point-of-service bills for the "Bill a Service" desk.
 *
 * Flow: an authorised user (per-service allowed_roles) adds a service for a
 * registered patient OR a walk-in customer (gas plant oxygen, internship,
 * research, application form, ID card …). This service creates the Invoice
 * + InvoiceItem; the existing CashierTaskService listener (registered in
 * AppServiceProvider) then auto-creates a CashierTask for every invoice
 * with a positive balance, which appears at the cashier point for payment
 * collection.
 */
class ServiceBillingService
{
    /**
     * @param  array{
     *     service_id: int,
     *     patient_id?: int|null,
     *     customer_name?: string|null,
     *     customer_phone?: string|null,
     *     quantity?: int,
     *     unit_price?: float|numeric-string|null,
     *     payment_mode?: string|null,
     *     insurance_covered?: float|numeric-string|null,
     *     notes?: string|null,
     * }  $data
     * @return Invoice
     */
    public function createBill(array $data): Invoice
    {
        return DB::transaction(function () use ($data) {
            $service = Service::where('status', 'Active')->findOrFail($data['service_id']);
            $patient = ! empty($data['patient_id'])
                ? Patient::findOrFail($data['patient_id'])
                : null;

            // A walk-in bill requires the service to allow walk-in customers.
            if ($patient === null && ! $service->allows_walk_in) {
                throw new \InvalidArgumentException(
                    "'{$service->name}' can only be billed to a registered patient."
                );
            }

            // Enforce the per-service allowed_roles restriction.
            $user    = Auth::user();
            $roles   = $user?->roles()->pluck('name')->all() ?? [];
            if ($user && ! $user->hasRole('super_admin') && ! $service->isBillableBy($roles)) {
                throw new \InvalidArgumentException(
                    'You are not permitted to bill this service.'
                );
            }

            $quantity   = max(1, (int) ($data['quantity'] ?? 1));
            $paymentMode = $this->resolvePaymentMode($data['payment_mode'] ?? null, $patient);

            $unitPrice = isset($data['unit_price']) && (float) $data['unit_price'] > 0
                ? (float) $data['unit_price']
                : (float) $service->priceFor(Service::schemeForPaymentMode($paymentMode));

            $total            = round($unitPrice * $quantity, 2);
            $insuranceCovered = min((float) ($data['insurance_covered'] ?? 0), $total);
            $patientPortion   = round($total - $insuranceCovered, 2);

            $invoice = Invoice::create([
                'hospital_id'        => $patient?->hospital_id ?? $user?->hospital_id ?? 1,
                'patient_id'         => $patient?->id,
                'customer_name'      => $patient ? null : ($data['customer_name'] ?? 'Walk-in customer'),
                'customer_phone'     => $patient ? null : ($data['customer_phone'] ?? null),
                'payment_mode'       => $paymentMode,
                'invoice_date'       => now(),
                'subtotal'           => $total,
                'tax_amount'         => 0,
                'discount_amount'    => 0,
                'insurance_covered'  => $insuranceCovered,
                'patient_portion'    => $patientPortion,
                'total_amount'       => $total,
                'amount_paid'        => 0,
                'balance_due'        => $patientPortion,
                'status'             => 'Pending',
                'billed_by'          => $user?->id,
                'notes'              => $data['notes'] ?? null,
            ]);

            InvoiceItem::create([
                'invoice_id'          => $invoice->id,
                'service_id'          => $service->id,
                'service_type'        => $this->invoiceItemType($service->category),
                'description'         => $service->name,
                'quantity'            => $quantity,
                'unit_price'          => $unitPrice,
                'discount_percentage' => 0,
                'discount_amount'     => 0,
                'tax_percentage'      => 0,
                'tax_amount'          => 0,
                'insurance_covered'   => $insuranceCovered,
                'patient_portion'     => $patientPortion,
                'total_amount'        => $total,
                'notes'               => $service->code,
            ]);

            // Re-derive totals from items (idempotent). balance_due is
            // already correct at creation, so the CashierTaskService
            // listener (Invoice::created) has already queued the cashier
            // task for any positive balance.
            $invoice->recalculate();

            return $invoice->refresh();
        });
    }

    /**
     * Default payment mode from the patient's scheme: NHIA patients →
     * NHIA, NASHIA patients → NASHIA, private → Cash, etc.
     */
    protected function resolvePaymentMode(?string $mode, ?Patient $patient): string
    {
        $valid = ['Cash', 'Insurance', 'NHIA', 'NASHIA', 'Corporate', 'Charity', 'Mixed'];

        if ($mode && in_array($mode, $valid, true)) {
            return $mode;
        }

        return match ($patient?->patient_type) {
            'nhia'           => 'NHIA',
            'nashia'         => 'NASHIA',
            'corporate'      => 'Corporate',
            'retainership'   => 'Insurance',
            'other_insurance'=> 'Insurance',
            default          => 'Cash',
        };
    }

    /**
     * Map the service category onto the invoice_items.service_type enum.
     */
    protected function invoiceItemType(?string $category): string
    {
        return match ($category) {
            'Consultation', 'Procedure', 'Laboratory', 'Radiology', 'Pharmacy',
            'Accommodation', 'Consumable' => $category,
            default => 'Other',
        };
    }
}
