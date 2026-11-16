<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientSatisfactionSurvey extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'survey_number',
        'survey_date', 'department_visited', 'respondent_name', 'respondent_phone',
        'rating_reception', 'rating_nursing_care', 'rating_doctor_care',
        'rating_cleanliness', 'rating_waiting_time', 'rating_pharmacy',
        'rating_lab_service', 'rating_overall',
        'best_experience', 'worst_experience', 'suggestions', 'complaints',
        'would_recommend', 'collected_by', 'status', 'review_notes',
    ];

    public const RATINGS = [
        1 => 'Very Poor',
        2 => 'Poor',
        3 => 'Fair',
        4 => 'Good',
        5 => 'Excellent',
    ];

    public const RATING_COLORS = [
        1 => 'danger', 2 => 'warning', 3 => 'gray', 4 => 'info', 5 => 'success',
    ];

    public const STATUSES = [
        'Pending Review' => 'Pending Review',
        'Reviewed' => 'Reviewed',
        'Action Taken' => 'Action Taken',
        'Archived' => 'Archived',
    ];

    public const STATUS_COLORS = [
        'Pending Review' => 'warning',
        'Reviewed' => 'info',
        'Action Taken' => 'success',
        'Archived' => 'gray',
    ];

    protected $casts = [
        'survey_date' => 'date',
        'would_recommend' => 'boolean',
        'rating_reception' => 'integer',
        'rating_nursing_care' => 'integer',
        'rating_doctor_care' => 'integer',
        'rating_cleanliness' => 'integer',
        'rating_waiting_time' => 'integer',
        'rating_pharmacy' => 'integer',
        'rating_lab_service' => 'integer',
        'rating_overall' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($survey) {
            $year = now()->year;
            $last = self::whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->survey_number, -6)) + 1 : 1;
            $survey->survey_number = sprintf('SUR-%s-%06d', $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }

    /**
     * Average score across all rating fields.
     */
    public function getAverageRatingAttribute(): ?float
    {
        $ratings = array_filter([
            $this->rating_reception, $this->rating_nursing_care, $this->rating_doctor_care,
            $this->rating_cleanliness, $this->rating_waiting_time, $this->rating_pharmacy,
            $this->rating_lab_service, $this->rating_overall,
        ]);
        return count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : null;
    }
}
