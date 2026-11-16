<?php

namespace App\Http\Controllers;

use App\Models\LabOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class LabOrderPrintController extends Controller
{
    /**
     * Browser-printable HTML report.
     * The view auto-renders the "UNVERIFIED" watermark when not yet Verified.
     */
    public function print(LabOrder $labOrder): Response
    {
        $labOrder->load([
            'patient',
            'destinationLab',
            'items.test.unit',
            'items.result.performer',
            'items.result.verifier',
            'requester',
            'receiver',
        ]);

        return response()->view('lab-orders.print', ['order' => $labOrder]);
    }

    /**
     * Downloadable PDF version of the same report.
     */
    public function pdf(LabOrder $labOrder): Response
    {
        $labOrder->load([
            'patient',
            'destinationLab',
            'items.test.unit',
            'items.result.performer',
            'items.result.verifier',
            'requester',
            'receiver',
        ]);

        $pdf = Pdf::loadView('lab-orders.print', ['order' => $labOrder]);
        $filename = 'Lab-Report-' . $labOrder->order_number . '.pdf';

        return $pdf->download($filename);
    }
}
