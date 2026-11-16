<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'status'];

    protected function casts(): array
    {
        return ['status' => 'boolean'];
    }

    public function departments(): HasMany { return $this->hasMany(Department::class); }
    public function clinics(): HasMany { return $this->hasMany(Clinic::class); }
    public function staff(): HasMany { return $this->hasMany(Staff::class); }
    public function patients(): HasMany { return $this->hasMany(Patient::class); }
    public function users(): HasMany { return $this->hasMany(User::class); }
    public function encounters(): HasMany { return $this->hasMany(Encounter::class); }
    public function appointments(): HasMany { return $this->hasMany(Appointment::class); }
    public function insuranceProviders(): HasMany { return $this->hasMany(InsuranceProvider::class); }
    public function dutyRosters(): HasMany { return $this->hasMany(DutyRoster::class); }
    public function dutyPostings(): HasMany { return $this->hasMany(DutyPosting::class); }
    public function services(): HasMany { return $this->hasMany(Service::class); }
    public function invoices(): HasMany { return $this->hasMany(Invoice::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}
