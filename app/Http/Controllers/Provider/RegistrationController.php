<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\ProviderApplication;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    /**
     * Landing page for provider registration — lets the visitor choose between
     * a private doctor account or a hospital/clinic account.
     */
    public function index()
    {
        return view('provider.index');
    }

    /* =========================================================
     *  PRIVATE DOCTOR REGISTRATION
     * ========================================================= */

    public function createDoctor()
    {
        return view('provider.doctor-register');
    }

    public function storeDoctor(Request $request)
    {
        $data = $request->validate($this->doctorRules());

        $application = ProviderApplication::create(array_merge($data, [
            'account_type' => 'doctor',
        ]));

        return redirect()->route('provider.success', $application);
    }

    protected function doctorRules(): array
    {
        return [
            'title'                    => ['nullable', 'string', 'max:30'],
            'applicant_name'           => ['required', 'string', 'max:255'],
            'email'                    => ['required', 'email', 'max:255', 'unique:provider_applications,email'],
            'phone'                    => ['required', 'string', 'max:30'],
            'password'                 => ['required', 'string', 'min:8', 'confirmed'],
            'professional_registration_number' => ['required', 'string', 'max:100'],
            'specialty'                => ['required', 'string', 'max:255'],
            'qualification'             => ['required', 'string', 'max:255'],
            'years_of_experience'      => ['nullable', 'integer', 'min:0', 'max:80'],
            'bio'                      => ['nullable', 'string', 'max:2000'],
            'website'                  => ['nullable', 'string', 'max:255'],
            'services_offered'          => ['nullable', 'array'],
            'services_offered.*'        => ['string', 'max:100'],
            'notes'                    => ['nullable', 'string', 'max:2000'],
        ];
    }

    /* =========================================================
     *  HOSPITAL / CLINIC REGISTRATION
     * ========================================================= */

    public function createHospital()
    {
        return view('provider.hospital-register');
    }

    public function storeHospital(Request $request)
    {
        $data = $request->validate($this->hospitalRules());

        $application = ProviderApplication::create(array_merge($data, [
            'account_type' => $data['account_type'],
        ]));

        return redirect()->route('provider.success', $application);
    }

    protected function hospitalRules(): array
    {
        return [
            'account_type'            => ['required', Rule::in(['hospital', 'clinic'])],
            'facility_name'            => ['required', 'string', 'max:255'],
            'email'                    => ['required', 'email', 'max:255', 'unique:provider_applications,email'],
            'phone'                    => ['required', 'string', 'max:30'],
            'password'                 => ['required', 'string', 'min:8', 'confirmed'],
            'applicant_name'           => ['required', 'string', 'max:255'],
            'facility_type'            => ['required', 'string', 'max:100'],
            'facility_license_number'  => ['required', 'string', 'max:100'],
            'facility_address'         => ['required', 'string', 'max:500'],
            'facility_city'            => ['required', 'string', 'max:150'],
            'facility_state'           => ['required', 'string', 'max:150'],
            'facility_country'         => ['nullable', 'string', 'max:150'],
            'website'                  => ['nullable', 'string', 'max:255'],
            'bio'                      => ['nullable', 'string', 'max:2000'],
            'services_offered'          => ['nullable', 'array'],
            'services_offered.*'        => ['string', 'max:100'],
            'notes'                    => ['nullable', 'string', 'max:2000'],
        ];
    }

    /* =========================================================
     *  CONFIRMATION
     * ========================================================= */

    public function success(ProviderApplication $application)
    {
        if (! in_array($application->status, ['pending', 'under_review'])) {
            abort(404);
        }
        return view('provider.success', compact('application'));
    }
}
