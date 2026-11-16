<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ConsultationNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'encounter_id', 'patient_id', 'clinic_id', 'clinic_template_id', 'author_id',
        'note_number',
        'physical_examination_general', 'physical_examination_systemic', 'physical_examination_specific',
        'history_presenting_illness', 'history_past_medical', 'history_past_surgical',
        'history_drug_allergies', 'history_family', 'history_social',
        'history_obstetric', 'history_developmental',
        'examination_findings', 'assessment', 'plan', 'doctor_notes',
        'clinic_specific_data',
        'status', 'countersigned_by', 'countersigned_at',
    ];

    public const STATUSES = [
        'Draft' => 'Draft',
        'Finalized' => 'Finalized',
        'Counter-signed' => 'Counter-signed',
        'Amended' => 'Amended',
    ];

    public const STATUS_COLORS = [
        'Draft' => 'warning',
        'Finalized' => 'info',
        'Counter-signed' => 'success',
        'Amended' => 'gray',
    ];

    protected $casts = [
        'clinic_specific_data' => 'array',
        'countersigned_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($note) {
            if (empty($note->author_id) && Auth::check()) {
                $note->author_id = Auth::id();
            }
            if (empty($note->hospital_id) && Auth::check()) {
                $note->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($note->hospital_id)) {
                $note->hospital_id = 1;
            }
            $year = now()->year;
            $last = self::whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->note_number, -6)) + 1 : 1;
            $note->note_number = sprintf('CN-%s-%06d', $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function clinic(): BelongsTo { return $this->belongsTo(Clinic::class); }
    public function template(): BelongsTo { return $this->belongsTo(ClinicTemplate::class, 'clinic_template_id'); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function countersigner(): BelongsTo { return $this->belongsTo(User::class, 'countersigned_by'); }
}
