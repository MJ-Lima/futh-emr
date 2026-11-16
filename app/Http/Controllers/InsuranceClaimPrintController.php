<?php

namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InsuranceClaimPrintController extends Controller
{
    /**
     * Browser-printable HTML report of an NHIA/insurance claim.
     */
    public function print(InsuranceClaim $insuranceClaim): Response
    {
        $insuranceClaim->load([
            'patient', 'encounter', 'policy.provider', 'policy.plan',
            'hospital', 'lines.service',
        ]);

        return response()->view('insurance-claims.print', ['claim' => $insuranceClaim]);
    }

    /**
     * Downloadable PDF version of the claim.
     */
    public function pdf(InsuranceClaim $insuranceClaim): Response
    {
        $insuranceClaim->load([
            'patient', 'encounter', 'policy.provider', 'policy.plan',
            'hospital', 'lines.service',
        ]);

        $pdf = Pdf::loadView('insurance-claims.print', ['claim' => $insuranceClaim]);
        $filename = 'NHIA-Claim-' . $insuranceClaim->claim_number . '.pdf';

        return $pdf->download($filename);
    }
}
