<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MedicalCertificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_id', 'patient_id', 'encounter_id', 'admission_id', 'issued_by',
        'certificate_number', 'certificate_type', 'patient_category',
        'issued_date', 'valid_from', 'valid_until',
        'diagnosis', 'clinical_findings', 'treatment_given',
        'recommendations', 'restrictions', 'rest_days',
        'issued_at_location', 'status', 'notes',
    ];

    public const TYPES = [
        'Sick Leave'            => 'Sick Leave',
        'Fitness to Work'       => 'Fitness to Work',
        'Medical Examination'   => 'Medical Examination',
        'Death Certificate'     => 'Death Certificate',
        'Birth Notification'     => 'Birth Notification',
        'Discharge Summary'     => 'Discharge Summary',
        'Medical Report'        => 'Medical Report',
        'Fitness to Travel'     => 'Fitness to Travel',
        'Fitness for Surgery'   => 'Fitness for Surgery',
        'Custom'                => 'Custom',
    ];

    public const CATEGORIES = [
        'Outpatient' => 'Outpatient',
        'Inpatient'  => 'Inpatient',
        'Emergency'  => 'Emergency',
    ];

    public const STATUSES = [
        'Draft'    => 'Draft',
        'Issued'   => 'Issued',
        'Printed'  => 'Printed',
        'Cancelled' => 'Cancelled',
        'Revoked'   => 'Revoked',
    ];

    public const STATUS_COLORS = [
        'Draft'     => 'warning',
        'Issued'    => 'success',
        'Printed'   => 'info',
        'Cancelled' => 'danger',
        'Revoked'   => 'danger',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'valid_from'  => 'date',
        'valid_until' => 'date',
        'rest_days'   => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($c) {
            if (empty($c->issued_by) && Auth::check()) {
                $c->issued_by = Auth::id();
            }
            if (empty($c->hospital_id) && Auth::check()) {
                $c->hospital_id = Auth::user()->hospital_id;
            }
            if (empty($c->issued_date)) {
                $c->issued_date = now();
            }
            $year = now()->year;
            $last = self::withTrashed()->whereYear('created_at', $year)->latest('id')->first();
            $next = $last ? ((int) substr($last->certificate_number, -6)) + 1 : 1;
            $c->certificate_number = sprintf('MC-%s-%06d', $year, $next);
        });
    }

    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function encounter(): BelongsTo { return $this->belongsTo(Encounter::class); }
    public function admission(): BelongsTo { return $this->belongsTo(Admission::class); }
    public function issuer(): BelongsTo { return $this->belongsTo(User::class, 'issued_by'); }
}
