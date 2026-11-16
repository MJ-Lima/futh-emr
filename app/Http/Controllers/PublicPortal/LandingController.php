<?php

namespace App\Http\Controllers\PublicPortal;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Public landing page — replaces the default Laravel welcome.
     */
    public function index()
    {
        $hospitals = Hospital::public()
            ->orderBy('name')
            ->limit(6)
            ->get();

        $stats = [
            'hospitals' => Hospital::public()->count(),
            'states'    => Hospital::public()->whereNotNull('state')->distinct()->count('state'),
            'specialties' => 14,
            'years'     => 18,
        ];

        return view('public.landing', compact('hospitals', 'stats'));
    }

    /**
     * Full hospital directory.
     */
    public function directory(Request $request)
    {
        $query = Hospital::public()->with(['clinics', 'departments']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('tagline', 'like', "%{$search}%");
            });
        }
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }
        if ($state = $request->input('state')) {
            $query->where('state', $state);
        }

        $hospitals = $query->orderBy('name')->paginate(12)->withQueryString();
        $states = Hospital::public()->whereNotNull('state')->distinct()->orderBy('state')->pluck('state');
        $types = Hospital::public()->distinct()->orderBy('type')->pluck('type');

        return view('public.directory', compact('hospitals', 'states', 'types'));
    }

    /**
     * Single hospital profile.
     */
    public function showHospital(Hospital $hospital)
    {
        if (! $hospital->is_public || ! $hospital->status) {
            abort(404);
        }

        $hospital->load(['clinics' => fn ($q) => $q->where('status', true), 'departments' => fn ($q) => $q->where('status', true)]);

        return view('public.hospital', compact('hospital'));
    }

    /**
     * Contact / about page.
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * AJAX: list clinics for a hospital (used by the appointment booking form).
     */
    public function clinics(Request $request)
    {
        $data = $request->validate(['hospital_id' => 'required|integer|exists:hospitals,id']);
        $clinics = \App\Models\Clinic::where('hospital_id', $data['hospital_id'])
            ->where('status', true)
            ->orderBy('name')
            ->get(['id', 'name']);
        return response()->json(['clinics' => $clinics]);
    }
}
