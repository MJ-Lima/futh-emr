<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PatientPortalUser extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $guard = 'patient_portal';

    protected $fillable = [
        'patient_id', 'username', 'email', 'password', 'phone',
        'status', 'verification_token', 'email_verified_at', 'approved_at',
        'approved_by', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token', 'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at'        => 'datetime',
        'last_login_at'      => 'datetime',
        'password'           => 'hashed',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(PatientPortalMessage::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function markApproved(int $approverId): void
    {
        $this->update([
            'status'       => 'active',
            'approved_at'  => now(),
            'approved_by'  => $approverId,
        ]);
    }
}
