<?php

namespace App\Http\Controllers;

use App\Models\ConsultationNote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ConsultationNotePrintController extends Controller
{
    /**
     * Browser-printable HTML report of a consultation note.
     * Auto-renders "DRAFT" watermark when status = Draft.
     */
    public function print(ConsultationNote $consultationNote): Response
    {
        $consultationNote->load([
            'patient',
            'encounter.presentingComplaints',
            'clinic',
            'template',
            'author',
            'countersigner',
        ]);

        // Also load any ICD-10 diagnoses attached to the encounter (via the diagnoses table)
        $encounterDiagnoses = $consultationNote->encounter
            ? $consultationNote->encounter->diagnoses()->orderBy('type')->get()
            : collect();

        // Presenting complaints come from the encounter (the consultation note itself does not link directly)
        $presentingComplaints = $consultationNote->encounter?->presentingComplaints ?? collect();

        return response()->view('consultation-notes.print', [
            'note' => $consultationNote,
            'encounterDiagnoses' => $encounterDiagnoses,
            'presentingComplaints' => $presentingComplaints,
        ]);
    }

    /**
     * Downloadable PDF version of the consultation note.
     */
    public function pdf(ConsultationNote $consultationNote): Response
    {
        $consultationNote->load([
            'patient',
            'encounter.presentingComplaints',
            'clinic',
            'template',
            'author',
            'countersigner',
        ]);

        $encounterDiagnoses = $consultationNote->encounter
            ? $consultationNote->encounter->diagnoses()->orderBy('type')->get()
            : collect();

        $presentingComplaints = $consultationNote->encounter?->presentingComplaints ?? collect();

        $pdf = Pdf::loadView('consultation-notes.print', [
            'note' => $consultationNote,
            'encounterDiagnoses' => $encounterDiagnoses,
            'presentingComplaints' => $presentingComplaints,
        ]);

        $filename = 'Consultation-Note-' . $consultationNote->note_number . '.pdf';
        return $pdf->download($filename);
    }
}
