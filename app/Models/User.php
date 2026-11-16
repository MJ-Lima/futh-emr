<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password',
        'hospital_id', 'department_id', 'default_clinic_id',
        'phone', 'avatar_url', 'status',
        'last_login_at', 'last_login_ip',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /* ---------------------------------------------------------------------
     *  Relationships
     * ------------------------------------------------------------------- */
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function defaultClinic(): BelongsTo { return $this->belongsTo(Clinic::class, 'default_clinic_id'); }
    public function staff(): HasMany { return $this->hasMany(Staff::class); }

    public function dutyPostings(): HasMany
    {
        return $this->hasMany(DutyPosting::class);
    }

    /**
     * Active duty posting as of "today" — drives unit-based data scoping.
     */
    public function currentDutyPosting()
    {
        $today = now()->toDateString();

        return $this->hasOne(DutyPosting::class)
            ->where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->latest('id');
    }

    /* ---------------------------------------------------------------------
     *  Role helpers
     * ------------------------------------------------------------------- */
    public function hasAnyRole(array|string $roles): bool
    {
        foreach ((array) $roles as $role) {
            if ($this->hasRole($role)) return true;
        }
        return false;
    }

    public function isSuperAdmin(): bool { return $this->hasRole('super_admin'); }
    public function isMedicalAdmin(): bool { return $this->hasAnyRole(['super_admin', 'medical_administrator']); }

    /* ---------------------------------------------------------------------
     *  Unit-based access scope helper
     *  Returns the clinic_id and department_id the user is currently posted to.
     *  Super Admin / Medical Administrator returns null (full access).
     * ------------------------------------------------------------------- */
    public function scopeContext(): ?array
    {
        if ($this->isMedicalAdmin()) return null;

        $posting = $this->currentDutyPosting;

        if ($posting) {
            return [
                'hospital_id'   => $posting->hospital_id,
                'department_id' => $posting->department_id,
                'clinic_id'     => $posting->clinic_id,
            ];
        }

        // Fall back to user's default assignment
        return [
            'hospital_id'   => $this->hospital_id,
            'department_id' => $this->department_id,
            'clinic_id'     => $this->default_clinic_id,
        ];
    }

    public function updateLastLogin(string $ip = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);
    }
}
