<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DischargeSummaryPrintController extends Controller
{
    /**
     * Browser-printable HTML discharge summary.
     */
    public function print(Admission $admission): Response
    {
        $admission->load([
            'patient', 'ward', 'bed',
            'admittingDoctor', 'attendingDoctor', 'residentDoctor', 'dischargedBy',
            'encounter.diagnoses', 'dischargeSummary',
        ]);

        // Load ward round notes for this admission
        $wardRoundNotes = \App\Models\WardRoundNote::where('admission_id', $admission->id)
            ->with(['doctor'])
            ->orderBy('round_date')
            ->orderBy('round_time')
            ->get();

        // Load lab orders during this admission
        $labOrders = \App\Models\LabOrder::where('patient_id', $admission->patient_id)
            ->whereBetween('ordered_at', [$admission->admission_date, $admission->discharge_date ?? now()])
            ->with(['items.test', 'items.result'])
            ->get();

        // Load prescriptions during this admission
        $prescriptions = \App\Models\Prescription::where('patient_id', $admission->patient_id)
            ->whereBetween('prescribed_at', [$admission->admission_date, $admission->discharge_date ?? now()])
            ->with(['items.drug'])
            ->get();

        return response()->view('discharge-summaries.print', [
            'admission' => $admission,
            'wardRoundNotes' => $wardRoundNotes,
            'labOrders' => $labOrders,
            'prescriptions' => $prescriptions,
        ]);
    }

    /**
     * Downloadable PDF version.
     */
    public function pdf(Admission $admission): Response
    {
        $admission->load([
            'patient', 'ward', 'bed',
            'admittingDoctor', 'attendingDoctor', 'residentDoctor', 'dischargedBy',
            'encounter.diagnoses', 'dischargeSummary',
        ]);

        $wardRoundNotes = \App\Models\WardRoundNote::where('admission_id', $admission->id)
            ->with(['doctor'])
            ->orderBy('round_date')
            ->orderBy('round_time')
            ->get();

        $labOrders = \App\Models\LabOrder::where('patient_id', $admission->patient_id)
            ->whereBetween('ordered_at', [$admission->admission_date, $admission->discharge_date ?? now()])
            ->with(['items.test', 'items.result'])
            ->get();

        $prescriptions = \App\Models\Prescription::where('patient_id', $admission->patient_id)
            ->whereBetween('prescribed_at', [$admission->admission_date, $admission->discharge_date ?? now()])
            ->with(['items.drug'])
            ->get();

        $pdf = Pdf::loadView('discharge-summaries.print', [
            'admission' => $admission,
            'wardRoundNotes' => $wardRoundNotes,
            'labOrders' => $labOrders,
            'prescriptions' => $prescriptions,
        ]);

        $filename = 'Discharge-Summary-' . $admission->admission_number . '.pdf';
        return $pdf->download($filename);
    }
}
