<?php

namespace App\Http\Controllers;

use App\Models\MedicalCertificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class MedicalCertificatePrintController extends Controller
{
    public function print(MedicalCertificate $medicalCertificate): Response
    {
        $medicalCertificate->load(['patient', 'encounter', 'admission', 'issuer', 'hospital']);

        if ($medicalCertificate->status === 'Issued') {
            $medicalCertificate->update(['status' => 'Printed']);
        }

        return response()->view('medical-certificates.print', ['certificate' => $medicalCertificate]);
    }

    public function pdf(MedicalCertificate $medicalCertificate): Response
    {
        $medicalCertificate->load(['patient', 'encounter', 'admission', 'issuer', 'hospital']);

        $pdf = Pdf::loadView('medical-certificates.print', ['certificate' => $medicalCertificate]);
        $filename = 'Medical-Certificate-' . $medicalCertificate->certificate_number . '.pdf';

        return $pdf->download($filename);
    }
}
