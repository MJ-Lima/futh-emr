<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentSlot;
use App\Models\AppointmentType;
use App\Models\Clinic;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Step 1 — pick a hospital + clinic.
     */
    public function create(Request $request)
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        if (! $patient) {
            return back()->with('danger', 'Your patient record must be verified before you can book appointments.');
        }

        $hospitals = Hospital::public()->where('accepts_online_registration', true)->orderBy('name')->get();
        $selectedHospital = $request->filled('hospital_id')
            ? $hospitals->firstWhere('id', (int) $request->input('hospital_id'))
            : $hospitals->firstWhere('id', $patient->hospital_id) ?? $hospitals->first();

        $clinics = $selectedHospital
            ? Clinic::where('hospital_id', $selectedHospital->id)->where('status', true)->orderBy('name')->get()
            : collect();

        $types = AppointmentType::orderBy('name')->get();

        return view('portal.appointment-create', compact('hospitals', 'selectedHospital', 'clinics', 'types'));
    }

    /**
     * Step 2 — list available slots for a clinic + date.
     */
    public function slots(Request $request)
    {
        $data = $request->validate([
            'hospital_id' => ['required', 'integer', 'exists:hospitals,id'],
            'clinic_id'   => ['required', 'integer', 'exists:clinics,id'],
            'date'        => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = AppointmentSlot::where('hospital_id', $data['hospital_id'])
            ->where('clinic_id', $data['clinic_id'])
            ->where('slot_date', $data['date'])
            ->where('status', 'Available')
            ->whereRaw('booked_count < capacity')
            ->orderBy('start_time')
            ->get();

        return response()->json(['slots' => $slots]);
    }

    /**
     * Step 3 — store the booking.
     */
    public function store(Request $request)
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        if (! $patient) {
            return back()->with('danger', 'Your patient record must be verified before you can book appointments.');
        }

        $data = $request->validate([
            'hospital_id'      => ['required', 'integer', 'exists:hospitals,id'],
            'clinic_id'        => ['required', 'integer', 'exists:clinics,id'],
            'slot_id'          => ['required', 'integer', 'exists:appointment_slots,id'],
            'visit_type'       => ['required', Rule::in(['New', 'Follow-up', 'Review', 'Telemedicine'])],
            'reason_for_visit' => ['required', 'string', 'max:1000'],
        ]);

        $slot = AppointmentSlot::where('id', $data['slot_id'])
            ->where('hospital_id', $data['hospital_id'])
            ->where('clinic_id', $data['clinic_id'])
            ->where('status', 'Available')
            ->whereRaw('booked_count < capacity')
            ->lockForUpdate()
            ->first();

        if (! $slot) {
            return back()->with('danger', 'That slot is no longer available. Please pick another time.');
        }

        $appointment = Appointment::create([
            'hospital_id'        => $data['hospital_id'],
            'patient_id'         => $patient->id,
            'clinic_id'          => $data['clinic_id'],
            'provider_id'        => $slot->provider_id,
            'appointment_number' => 'APT-' . strtoupper(Str::random(8)),
            'appointment_date'   => $slot->slot_date,
            'appointment_time'   => $slot->start_time,
            'estimated_duration' => $slot->duration_minutes,
            'visit_type'         => $data['visit_type'],
            'reason_for_visit'   => $data['reason_for_visit'],
            'status'             => 'Scheduled',
            'created_by'         => 1, // system user; portal bookings
        ]);

        $slot->increment('booked_count');
        if ($slot->booked_count >= $slot->capacity) {
            $slot->update(['status' => 'Booked']);
        }

        return redirect()->route('portal.appointments')->with('success', 'Appointment booked. Reference ' . $appointment->appointment_number);
    }

    public function index()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $appointments = $patient
            ? $patient->appointments()->with(['hospital', 'clinic', 'provider'])->latest('appointment_date')->paginate(15)
            : collect();

        return view('portal.appointments', compact('appointments'));
    }

    public function cancel(Appointment $appointment)
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        if (! $patient || $appointment->patient_id !== $patient->id) {
            abort(403);
        }
        if (! in_array($appointment->status, ['Scheduled', 'Confirmed'])) {
            return back()->with('danger', 'Only upcoming appointments can be cancelled.');
        }

        $appointment->update(['status' => 'Cancelled', 'cancellation_reason' => 'Cancelled by patient via portal.']);

        $slot = AppointmentSlot::where('hospital_id', $appointment->hospital_id)
            ->where('clinic_id', $appointment->clinic_id)
            ->where('slot_date', $appointment->appointment_date)
            ->where('start_time', $appointment->appointment_time)
            ->first();
        if ($slot) {
            $slot->decrement('booked_count');
            if ($slot->booked_count < $slot->capacity && $slot->status === 'Booked') {
                $slot->update(['status' => 'Available']);
            }
        }

        return back()->with('success', 'Appointment cancelled.');
    }
}
