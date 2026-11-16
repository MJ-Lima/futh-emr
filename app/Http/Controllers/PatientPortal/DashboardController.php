<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $portalUser = Auth::guard('patient_portal')->user();
        $patient = $portalUser->patient;

        if (! $patient) {
            return view('portal.dashboard', ['patient' => null]);
        }

        $upcomingAppointments = $patient->appointments()
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['Scheduled', 'Confirmed', 'Checked In'])
            ->with(['hospital', 'clinic', 'provider'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        $recentEncounters = $patient->encounters()
            ->with(['hospital', 'clinic'])
            ->latest('encounter_date')
            ->limit(5)
            ->get();

        $pendingInvoices = $patient->invoices()
            ->whereIn('status', ['Pending', 'Partially Paid', 'Overdue'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $recentLabOrders = $patient->labOrders()
            ->latest('ordered_at')
            ->limit(5)
            ->get();

        $unreadMessages = $patient->messages()
            ->where('direction', 'outbound')
            ->whereNull('read_at')
            ->count();

        $stats = [
            'appointments' => $upcomingAppointments->count(),
            'encounters'   => $patient->encounters()->count(),
            'invoices'     => $pendingInvoices->count(),
            'messages'     => $unreadMessages,
        ];

        return view('portal.dashboard', compact(
            'patient', 'stats', 'upcomingAppointments', 'recentEncounters',
            'pendingInvoices', 'recentLabOrders'
        ));
    }

    public function encounters()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $encounters = $patient
            ? $patient->encounters()->with(['hospital', 'clinic', 'vitals', 'clinicalNotes'])->latest('encounter_date')->paginate(15)
            : collect();

        return view('portal.encounters', compact('encounters'));
    }

    public function labResults()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $labOrders = $patient
            ? $patient->labOrders()->with(['items.test', 'items.results'])->latest('ordered_at')->paginate(15)
            : collect();

        return view('portal.lab-results', compact('labOrders'));
    }

    public function prescriptions()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $prescriptions = $patient
            ? $patient->prescriptions()->with(['items.drug', 'prescriber'])->latest('prescribed_at')->paginate(15)
            : collect();

        return view('portal.prescriptions', compact('prescriptions'));
    }

    public function invoices(Request $request)
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $query = $patient ? $patient->invoices()->with(['items', 'payments']) : null;

        if ($query && $status = $request->input('status')) {
            $query->where('status', $status);
        }
        $invoices = $query ? $query->orderByDesc('invoice_date')->paginate(15) : collect();

        return view('portal.invoices', compact('invoices'));
    }

    public function receipts()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $receipts = $patient
            ? $patient->receipts()->latest('receipt_date')->paginate(15)
            : collect();

        return view('portal.receipts', compact('receipts'));
    }
}
