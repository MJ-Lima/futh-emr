<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProviderApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'account_type', 'application_number',
        'applicant_name', 'email', 'phone', 'password', 'title',
        'professional_registration_number', 'specialty', 'qualification', 'years_of_experience',
        'facility_name', 'facility_type', 'facility_license_number', 'facility_address',
        'facility_city', 'facility_state', 'facility_country',
        'bio', 'logo_path', 'website', 'services_offered', 'notes',
        'status', 'reviewed_by', 'reviewed_at', 'review_notes',
        'user_id', 'hospital_id',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'services_offered' => 'array',
        'reviewed_at'      => 'datetime',
        'password'         => 'hashed',
    ];

    protected static function booted()
    {
        static::creating(function (self $app) {
            if (empty($app->application_number)) {
                $prefix = $app->account_type === 'hospital' ? 'HOS' : ($app->account_type === 'clinic' ? 'CLN' : 'DOC');
                $year   = now()->format('Y');
                do {
                    $app->application_number = sprintf('%s-%s-%s', $prefix, $year, strtoupper(Str::random(6)));
                } while (self::withTrashed()->where('application_number', $app->application_number)->exists());
            }
        });
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    public function isPending(): bool
    {
        return in_array($this->status, ['pending', 'under_review']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->account_type === 'doctor'
            ? trim(($this->title ? $this->title . ' ' : '') . $this->applicant_name)
            : ($this->facility_name ?: $this->applicant_name);
    }
}
