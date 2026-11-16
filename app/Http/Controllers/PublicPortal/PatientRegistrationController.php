<?php

namespace App\Http\Controllers\PublicPortal;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\PatientPortalUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PatientRegistrationController extends Controller
{
    /**
     * Show the new-patient registration form, pre-selected to a hospital.
     */
    public function create(Request $request, Hospital $hospital = null)
    {
        $hospitals = Hospital::public()
            ->where('accepts_online_registration', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'city', 'state']);

        if ($hospitals->isEmpty()) {
            return view('public.patient-register-unavailable');
        }

        return view('public.patient-register', [
            'hospitals' => $hospitals,
            'selected'  => $hospital ?? $hospitals->first(),
        ]);
    }

    /**
     * Store the new patient registration + create a portal login (pending approval).
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, $this->rules());

        $hospital = Hospital::public()
            ->where('accepts_online_registration', true)
            ->findOrFail($data['hospital_id']);

        $existing = Patient::where('email', $data['email'])
            ->orWhere(function ($q) use ($data) {
                $q->where('phonemain', $data['phonemain'])
                  ->where('firstname', $data['firstname'])
                  ->where('lastname', $data['lastname']);
            })
            ->first();

        if ($existing) {
            return back()->withInput()
                ->with('warning', 'A patient record with this email or name + phone already exists. Please sign in to your patient portal instead, or visit the front desk to link your record.');
        }

        DB::transaction(function () use ($data, $hospital, $request, &$patient, &$portalUser) {
            // 1) Create the patient record (verification_status = Pending)
            $patient = new Patient([
                'hospital_id'        => $hospital->id,
                'patient_type'        => $data['patient_type'],
                'firstname'           => $data['firstname'],
                'middlename'          => $data['middlename'] ?? null,
                'lastname'            => $data['lastname'],
                'dob'                 => $data['dob'],
                'gender'              => $data['gender'],
                'marital'             => $data['marital'] ?? null,
                'religion'            => $data['religion'] ?? null,
                'occupation'          => $data['occupation'] ?? null,
                'phonemain'           => $data['phonemain'],
                'phonealt'            => $data['phonealt'] ?? null,
                'email'               => $data['email'],
                'address'             => $data['address'],
                'city'                => $data['city'],
                'state'               => $data['state'],
                'country'             => $data['country'] ?? 'Nigeria',
                'national_id'          => $data['national_id'] ?? null,
                'national_id_type'     => $data['national_id_type'] ?? null,
                'nokname'              => $data['nokname'],
                'nok_firstname'        => $data['nok_firstname'] ?? null,
                'relationship'         => $data['relationship'],
                'nokphone'             => $data['nokphone'],
                'nokaddress'           => $data['nokaddress'],
                'hereabout'            => $data['hereabout'] ?? 'Online Portal',
                'verification_status'  => 'Pending',
                'status'               => true,
            ]);
            $patient->save();

            // 2) Create the portal login (status = pending until admin verifies the patient record)
            $username = $this->generateUsername($data['firstname'], $data['lastname']);
            $portalUser = PatientPortalUser::create([
                'patient_id'  => $patient->id,
                'username'    => $username,
                'email'       => $data['email'],
                'password'    => $data['password'],
                'phone'       => $data['phonemain'],
                'status'      => 'pending',
                'verification_token' => Str::random(40),
            ]);
        });

        return redirect()->route('public.patient.register.success', [
            'hospital' => $hospital->id,
            'portal_user' => $portalUser->id,
            'token' => $portalUser->verification_token,
        ]);
    }

    public function success(Request $request)
    {
        $portalUser = PatientPortalUser::where('id', $request->input('portal_user'))
            ->where('verification_token', $request->input('token'))
            ->firstOrFail();

        $patient = $portalUser->patient;

        return view('public.patient-register-success', [
            'portalUser' => $portalUser,
            'patient'    => $patient,
            'hospital'   => $patient?->hospital,
        ]);
    }

    protected function rules(): array
    {
        return [
            'hospital_id'       => ['required', 'integer', 'exists:hospitals,id'],
            'patient_type'       => ['required', Rule::in(['private', 'nhia', 'nashia', 'retainership', 'corporate', 'other_insurance'])],
            'firstname'          => ['required', 'string', 'max:200'],
            'middlename'         => ['nullable', 'string', 'max:200'],
            'lastname'           => ['required', 'string', 'max:200'],
            'dob'                => ['required', 'date', 'before:today'],
            'gender'             => ['required', Rule::in(['Male', 'Female', 'Other'])],
            'marital'            => ['nullable', 'string', 'max:20'],
            'religion'           => ['nullable', 'string', 'max:200'],
            'occupation'         => ['nullable', 'string', 'max:255'],
            'phonemain'          => ['required', 'string', 'max:13'],
            'phonealt'           => ['nullable', 'string', 'max:13'],
            'email'              => ['required', 'email', 'max:250'],
            'address'            => ['required', 'string', 'max:500'],
            'city'               => ['required', 'string', 'max:250'],
            'state'              => ['required', 'string', 'max:250'],
            'country'            => ['nullable', 'string', 'max:250'],
            'national_id'        => ['nullable', 'string', 'max:50'],
            'national_id_type'   => ['nullable', Rule::in(['NIN', 'Voter Card', 'Driver License', 'International Passport', 'Birth Certificate', 'Other'])],
            'nokname'            => ['required', 'string', 'max:250'],
            'nok_firstname'      => ['nullable', 'string', 'max:250'],
            'relationship'       => ['required', 'string', 'max:250'],
            'nokphone'           => ['required', 'string', 'max:250'],
            'nokaddress'         => ['required', 'string', 'max:500'],
            'hereabout'          => ['nullable', 'string', 'max:250'],
            'password'           => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    protected function generateUsername(string $first, string $last): string
    {
        $base = Str::slug(strtolower($first . '.' . $last), '.');
        $username = $base;
        $i = 1;
        while (PatientPortalUser::where('username', $username)->exists()) {
            $username = $base . $i++;
        }
        return $username;
    }
}
