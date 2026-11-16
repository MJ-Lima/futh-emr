<?php

namespace App\Http\Controllers\PatientPortal;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\PatientPortalMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $portalUser = Auth::guard('patient_portal')->user();
        $patient = $portalUser->patient;

        $query = PatientPortalMessage::where('patient_portal_user_id', $portalUser->id)
            ->with(['hospital', 'staffUser']);

        if ($hospitalId = $request->input('hospital_id')) {
            $query->where('hospital_id', $hospitalId);
        }

        $messages = $query->latest()->paginate(20)->withQueryString();
        $hospitals = $patient
            ? Hospital::whereIn('id', $patient->encounters()->pluck('hospital_id')->unique())->orWhereIn('id', [$patient->hospital_id])->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('portal.messages', compact('messages', 'hospitals'));
    }

    public function create()
    {
        $patient = Auth::guard('patient_portal')->user()->patient;
        $hospitals = $patient
            ? Hospital::whereIn('id', $patient->encounters()->pluck('hospital_id')->unique())
                ->orWhere('id', $patient->hospital_id)
                ->orderBy('name')->get(['id', 'name'])
            : Hospital::public()->orderBy('name')->get(['id', 'name']);

        return view('portal.message-create', compact('hospitals'));
    }

    public function store(Request $request)
    {
        $portalUser = Auth::guard('patient_portal')->user();
        $patient = $portalUser->patient;

        $data = $request->validate([
            'hospital_id' => ['required', 'integer', 'exists:hospitals,id'],
            'subject'     => ['required', 'string', 'max:200'],
            'body'        => ['required', 'string', 'max:5000'],
        ]);

        PatientPortalMessage::create([
            'patient_portal_user_id' => $portalUser->id,
            'patient_id'              => $patient?->id,
            'hospital_id'             => $data['hospital_id'],
            'direction'               => 'inbound',
            'status'                  => 'sent',
            'subject'                 => $data['subject'],
            'body'                    => $data['body'],
        ]);

        return redirect()->route('portal.messages')->with('success', 'Your message has been sent to the hospital team. They will reply in your inbox.');
    }

    public function show(PatientPortalMessage $message)
    {
        if ($message->patient_portal_user_id !== Auth::guard('patient_portal')->id()) {
            abort(403);
        }
        if ($message->direction === 'outbound' && ! $message->read_at) {
            $message->update(['read_at' => now(), 'status' => 'read']);
        }
        $message->load(['hospital', 'staffUser']);
        return view('portal.message-show', compact('message'));
    }
}
