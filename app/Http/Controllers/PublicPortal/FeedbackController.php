<?php

namespace App\Http\Controllers\PublicPortal;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\PatientSatisfactionSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    /**
     * Public feedback form (no login required). Lives at /feedback.
     */
    public function create(Request $request)
    {
        $hospitals = Hospital::public()->orderBy('name')->pluck('name', 'id');

        return view('public.feedback', [
            'hospitals'  => $hospitals,
            'selectedHosp' => (int) $request->input('hospital_id', Hospital::public()->value('id')),
            'ratingOptions' => PatientSatisfactionSurvey::RATINGS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, $this->rules());

        $survey = new PatientSatisfactionSurvey([
            'hospital_id'        => $data['hospital_id'],
            'patient_id'          => $data['patient_id'] ?? null,
            'encounter_id'       => null,
            'survey_date'        => now()->toDateString(),
            'department_visited' => $data['department_visited'] ?? null,
            'respondent_name'    => $data['respondent_name'] ?? 'Anonymous',
            'respondent_phone'   => $data['respondent_phone'] ?? null,
            'rating_reception'   => $data['rating_reception'] ?? null,
            'rating_nursing_care' => $data['rating_nursing_care'] ?? null,
            'rating_doctor_care' => $data['rating_doctor_care'] ?? null,
            'rating_cleanliness' => $data['rating_cleanliness'] ?? null,
            'rating_waiting_time' => $data['rating_waiting_time'] ?? null,
            'rating_pharmacy'    => $data['rating_pharmacy'] ?? null,
            'rating_lab_service' => $data['rating_lab_service'] ?? null,
            'rating_overall'     => $data['rating_overall'] ?? null,
            'best_experience'    => $data['best_experience'] ?? null,
            'worst_experience'   => $data['worst_experience'] ?? null,
            'suggestions'         => $data['suggestions'] ?? null,
            'complaints'          => $data['complaints'] ?? null,
            'would_recommend'    => isset($data['would_recommend']) ? (bool) $data['would_recommend'] : null,
            'status'              => 'Pending Review',
        ]);
        $survey->save();

        $surveyNumber = $survey->survey_number;

        return redirect()->route('public.feedback.success', ['survey' => $survey->id])
            ->with('survey_number', $surveyNumber);
    }

    public function success(int $surveyId)
    {
        $survey = PatientSatisfactionSurvey::findOrFail($surveyId);

        return view('public.feedback-success', [
            'surveyNumber' => $survey->survey_number,
        ]);
    }

    protected function rules(): array
    {
        return [
            'hospital_id'           => ['required', 'integer', 'exists:hospitals,id'],
            'patient_id'            => ['nullable', 'integer', 'exists:patients,id'],
            'department_visited'    => ['nullable', 'string', 'max:191'],
            'respondent_name'       => ['nullable', 'string', 'max:191'],
            'respondent_phone'      => ['nullable', 'string', 'max:30'],
            'rating_reception'      => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_nursing_care'   => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_doctor_care'    => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_cleanliness'    => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_waiting_time'   => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_pharmacy'       => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_lab_service'    => ['nullable', 'integer', 'min:1', 'max:5'],
            'rating_overall'        => ['nullable', 'integer', 'min:1', 'max:5'],
            'best_experience'       => ['nullable', 'string', 'max:2000'],
            'worst_experience'      => ['nullable', 'string', 'max:2000'],
            'suggestions'           => ['nullable', 'string', 'max:5000'],
            'complaints'            => ['nullable', 'string', 'max:5000'],
            'would_recommend'      => ['nullable', 'boolean'],
        ];
    }
}
