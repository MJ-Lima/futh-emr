<?php

use App\Http\Controllers\ConsultationNotePrintController;
use App\Http\Controllers\DischargeSummaryPrintController;
use App\Http\Controllers\DispensingLogExportController;
use App\Http\Controllers\DutyRosterExportController;
use App\Http\Controllers\InsuranceClaimPrintController;
use App\Http\Controllers\InventoryValuationExportController;
use App\Http\Controllers\LabOrderPrintController;
use App\Http\Controllers\MedicalCertificatePrintController;
use App\Http\Controllers\MortalityReviewExportController;
use App\Http\Controllers\ReferralPrintController;
use App\Http\Controllers\ReportingTableExportController;
use App\Http\Controllers\StockReorderExportController;
use App\Http\Controllers\WardRoundNotePrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Print / PDF / Export routes (must be authenticated)
Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    // Lab orders
    Route::get('/lab-orders/{labOrder}/print', [LabOrderPrintController::class, 'print'])->name('lab-orders.print');
    Route::get('/lab-orders/{labOrder}/pdf', [LabOrderPrintController::class, 'pdf'])->name('lab-orders.pdf');

    // Medical certificates
    Route::get('/medical-certificates/{medicalCertificate}/print', [MedicalCertificatePrintController::class, 'print'])->name('medical-certificates.print');
    Route::get('/medical-certificates/{medicalCertificate}/pdf', [MedicalCertificatePrintController::class, 'pdf'])->name('medical-certificates.pdf');

    // Referrals
    Route::get('/referrals/{referral}/print', [ReferralPrintController::class, 'print'])->name('referrals.print');
    Route::get('/referrals/{referral}/pdf', [ReferralPrintController::class, 'pdf'])->name('referrals.pdf');

    // Consultation notes
    Route::get('/consultation-notes/{consultationNote}/print', [ConsultationNotePrintController::class, 'print'])->name('consultation-notes.print');
    Route::get('/consultation-notes/{consultationNote}/pdf', [ConsultationNotePrintController::class, 'pdf'])->name('consultation-notes.pdf');

    // Ward round notes
    Route::get('/ward-round-notes/{wardRoundNote}/print', [WardRoundNotePrintController::class, 'print'])->name('ward-round-notes.print');
    Route::get('/ward-round-notes/{wardRoundNote}/pdf', [WardRoundNotePrintController::class, 'pdf'])->name('ward-round-notes.pdf');
    Route::get('/ward-round-notes/admission/{admissionId}/bundle', [WardRoundNotePrintController::class, 'admissionBundle'])->name('ward-round-notes.bundle');

    // Discharge summary
    Route::get('/admissions/{admission}/discharge-summary/print', [DischargeSummaryPrintController::class, 'print'])->name('discharge-summaries.print');
    Route::get('/admissions/{admission}/discharge-summary/pdf', [DischargeSummaryPrintController::class, 'pdf'])->name('discharge-summaries.pdf');

    // Reporting Table export (Excel/CSV)
    Route::get('/reporting-table/export', [ReportingTableExportController::class, 'export'])->name('reporting-table.export');
    Route::get('/reporting-table/export-csv', [ReportingTableExportController::class, 'exportCsv'])->name('reporting-table.export-csv');

    // Stock reorder report export (Excel/CSV)
    Route::get('/stock-reorder/export', [StockReorderExportController::class, 'export'])->name('stock-reorder.export');
    Route::get('/stock-reorder/export-csv', [StockReorderExportController::class, 'exportCsv'])->name('stock-reorder.export-csv');

    // Dispensing log export (Excel/CSV)
    Route::get('/dispensing-log/export', [DispensingLogExportController::class, 'export'])->name('dispensing-log.export');
    Route::get('/dispensing-log/export-csv', [DispensingLogExportController::class, 'exportCsv'])->name('dispensing-log.export-csv');

    // Insurance / NHIA claims
    Route::get('/insurance-claims/{insuranceClaim}/print', [InsuranceClaimPrintController::class, 'print'])->name('insurance-claims.print');
    Route::get('/insurance-claims/{insuranceClaim}/pdf', [InsuranceClaimPrintController::class, 'pdf'])->name('insurance-claims.pdf');

    // Mortality review export
    Route::get('/mortality-review/export', [MortalityReviewExportController::class, 'export'])->name('mortality-review.export');
    Route::get('/mortality-review/export-csv', [MortalityReviewExportController::class, 'exportCsv'])->name('mortality-review.export-csv');

    // Duty roster export
    Route::get('/duty-roster/export', [DutyRosterExportController::class, 'export'])->name('duty-roster.export');

    // Inventory valuation export
    Route::get('/inventory-valuation/export', [InventoryValuationExportController::class, 'export'])->name('inventory-valuation.export');
    Route::get('/inventory-valuation/export-csv', [InventoryValuationExportController::class, 'exportCsv'])->name('inventory-valuation.export-csv');
});
