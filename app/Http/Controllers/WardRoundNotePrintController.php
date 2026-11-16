<?php

namespace App\Http\Controllers;

use App\Models\WardRoundNote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WardRoundNotePrintController extends Controller
{
    /**
     * Browser-printable HTML report of a ward round note.
     */
    public function print(WardRoundNote $wardRoundNote): Response
    {
        $wardRoundNote->load(['patient', 'admission', 'ward', 'bed', 'doctor', 'encounter']);

        return response()->view('ward-round-notes.print', ['note' => $wardRoundNote]);
    }

    /**
     * Downloadable PDF version of the ward round note.
     */
    public function pdf(WardRoundNote $wardRoundNote): Response
    {
        $wardRoundNote->load(['patient', 'admission', 'ward', 'bed', 'doctor', 'encounter']);

        $pdf = Pdf::loadView('ward-round-notes.print', ['note' => $wardRoundNote]);
        $filename = 'Ward-Round-Note-' . $wardRoundNote->note_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download ALL ward round notes for a given admission as a single PDF
     * (useful for handing over or for the patient's chart).
     */
    public function admissionBundle($admissionId): Response
    {
        $admission = \App\Models\Admission::with(['patient', 'ward', 'bed'])->findOrFail($admissionId);
        $notes = WardRoundNote::where('admission_id', $admissionId)
            ->with(['doctor'])
            ->orderBy('round_date')
            ->orderBy('round_time')
            ->get();

        $pdf = Pdf::loadView('ward-round-notes.bundle', [
            'admission' => $admission,
            'notes' => $notes,
        ]);
        $filename = 'Ward-Round-Notes-' . $admission->admission_number . '.pdf';

        return $pdf->download($filename);
    }
}
