<?php

namespace App\Services;

use App\Mail\ProviderApplicationApproved;
use App\Mail\ProviderApplicationRejected;
use App\Models\Department;
use App\Models\ProviderApplication;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ProviderApprovalService
{
    /**
     * Approve a provider application.
     *
     * Doctor  -> creates a User with role 'private_doctor' linked to a default hospital.
     * Hospital -> creates a Hospital record, a default department, and an admin User
     *            linked to that hospital with role 'hospital_administrator'.
     *
     * @return array{user: User, hospital: ?Hospital}
     */
    public function approve(ProviderApplication $application, int $approverId): array
    {
        return DB::transaction(function () use ($application, $approverId) {
            $password = $application->password;

            if ($application->account_type === 'doctor') {
                $hospital = $this->ensureDefaultHospital();
                $department = $hospital->departments()->first() ?? $this->ensureDefaultDepartment($hospital);

                $user = User::create([
                    'name'          => trim(($application->title ? $application->title . ' ' : '') . $application->applicant_name),
                    'email'         => $application->email,
                    'password'      => $password,
                    'hospital_id'   => $hospital->id,
                    'department_id' => $department->id,
                    'phone'         => $application->phone,
                    'status'        => 'active',
                ]);
                $user->assignRole($this->ensureRole('private_doctor'));

                $application->update([
                    'status'       => 'approved',
                    'reviewed_by'   => $approverId,
                    'reviewed_at'   => now(),
                    'user_id'       => $user->id,
                    'hospital_id'   => $hospital->id,
                ]);

                $this->dispatchApprovalEmail($application);

                return ['user' => $user, 'hospital' => null];
            }

            // Hospital / clinic
            $code = $this->generateHospitalCode($application->facility_name);
            $hospital = Hospital::create([
                'name'          => $application->facility_name,
                'code'          => $code,
                'status'        => true,
                'type'          => $application->facility_type ?? 'General',
                'tagline'       => $application->account_type === 'clinic' ? 'Clinic' : 'Hospital',
                'description'   => $application->bio,
                'address_line1' => $application->facility_address,
                'city'          => $application->facility_city,
                'state'         => $application->facility_state,
                'country'       => $application->facility_country ?: 'Nigeria',
                'phone_main'    => $application->phone,
                'email'         => $application->email,
                'website'       => $application->website,
                'services'      => $application->services_offered,
                'is_public'     => true,
                'accepts_online_registration' => false,
            ]);

            $department = $this->ensureDefaultDepartment($hospital);

            $user = User::create([
                'name'          => $application->applicant_name,
                'email'         => $application->email,
                'password'      => $password,
                'hospital_id'   => $hospital->id,
                'department_id' => $department->id,
                'phone'         => $application->phone,
                'status'        => 'active',
            ]);
            $user->assignRole($this->ensureRole('hospital_administrator'));

            $application->update([
                'status'       => 'approved',
                'reviewed_by'   => $approverId,
                'reviewed_at'   => now(),
                'user_id'       => $user->id,
                'hospital_id'   => $hospital->id,
            ]);

            $this->dispatchApprovalEmail($application);

            return ['user' => $user, 'hospital' => $hospital];
        });
    }

    public function reject(ProviderApplication $application, int $approverId, string $notes): void
    {
        $application->update([
            'status'        => 'rejected',
            'reviewed_by'    => $approverId,
            'reviewed_at'    => now(),
            'review_notes'   => $notes,
        ]);

        $this->dispatchRejectionEmail($application);
    }

    /**
     * Send the approval email. Failures are logged but never crash the
     * approval action — the user is already created.
     */
    protected function dispatchApprovalEmail(ProviderApplication $application): void
    {
        try {
            Mail::to($application->email)->send(new ProviderApplicationApproved($application));
        } catch (\Throwable $e) {
            logger()->warning('Failed to send provider approval email', [
                'application' => $application->id,
                'email'       => $application->email,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    protected function dispatchRejectionEmail(ProviderApplication $application): void
    {
        try {
            Mail::to($application->email)->send(new ProviderApplicationRejected($application));
        } catch (\Throwable $e) {
            logger()->warning('Failed to send provider rejection email', [
                'application' => $application->id,
                'email'       => $application->email,
                'error'       => $e->getMessage(),
            ]);
        }
    }

    protected function ensureRole(string $name): Role
    {
        return Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
    }

    protected function ensureDefaultHospital(): Hospital
    {
        return Hospital::firstOrCreate(
            ['code' => 'FUTH-LF'],
            ['name' => 'Federal University Teaching Hospital, Lafia', 'status' => true]
        );
    }

    protected function ensureDefaultDepartment(Hospital $hospital): Department
    {
        return Department::firstOrCreate(
            ['hospital_id' => $hospital->id, 'code' => 'GEN'],
            ['name' => 'General', 'status' => true]
        );
    }

    protected function generateHospitalCode(string $name): string
    {
        $words = collect(preg_split('/\s+/', trim($name)))->take(3)->filter();
        $base = $words->map(fn ($w) => strtoupper(Str::substr(preg_replace('/[^A-Za-z]/', '', $w), 0, 2)))->implode('');
        $base = $base ?: 'HSP';
        $code = $base;
        $i = 1;
        while (Hospital::withTrashed()->where('code', $code)->exists()) {
            $code = $base . $i++;
        }
        return $code;
    }
}
