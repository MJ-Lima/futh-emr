<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReferralPrintController extends Controller
{
    public function print(Referral $referral): Response
    {
        $referral->load(['patient', 'encounter', 'admission', 'referrer', 'referredTo', 'hospital']);

        return response()->view('referrals.print', ['referral' => $referral]);
    }

    public function pdf(Referral $referral): Response
    {
        $referral->load(['patient', 'encounter', 'admission', 'referrer', 'referredTo', 'hospital']);

        $pdf = Pdf::loadView('referrals.print', ['referral' => $referral]);
        $filename = 'Referral-' . $referral->referral_number . '.pdf';

        return $pdf->download($filename);
    }
}
