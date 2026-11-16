<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientPortalMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_portal_user_id', 'patient_id', 'hospital_id', 'staff_user_id',
        'direction', 'status', 'subject', 'body', 'attachments', 'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'read_at'     => 'datetime',
    ];

    public function portalUser(): BelongsTo
    {
        return $this->belongsTo(PatientPortalUser::class, 'patient_portal_user_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    public function staffUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_user_id');
    }
}
