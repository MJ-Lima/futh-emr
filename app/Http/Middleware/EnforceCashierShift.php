<?php

namespace App\Http\Middleware;

use App\Models\DutyPosting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceCashierShift
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if (!$user->hasRole('cashier')) {
            return $next($request);
        }

        $today = now()->toDateString();
        $now = now()->format('H:i:s');

        $posting = DutyPosting::where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('is_cashier_shift', true)
            ->latest('id')
            ->first();

        if (!$posting) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('filament.admin.auth.login')
                ->with('error', 'No active cashier shift found. Contact your supervisor to assign you a shift.');
        }

        if ($posting->shift_start_time && $posting->shift_end_time) {
            $shiftStart = $posting->shift_start_time instanceof \Carbon\Carbon
                ? $posting->shift_start_time->format('H:i:s')
                : $posting->shift_start_time;
            $shiftEnd = $posting->shift_end_time instanceof \Carbon\Carbon
                ? $posting->shift_end_time->format('H:i:s')
                : $posting->shift_end_time;

            if ($now < $shiftStart) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('filament.admin.auth.login')
                    ->with('error', "Your shift starts at {$shiftStart}. Please wait.");
            }

            if ($now >= $shiftEnd) {
                $posting->update([
                    'is_active_now' => false,
                    'force_logout_at' => now(),
                ]);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('filament.admin.auth.login')
                    ->with('error', "Your shift ended at {$shiftEnd}. You have been automatically logged out.");
            }
        }

        $posting->update([
            'is_active_now' => true,
            'last_login_at' => now(),
        ]);

        return $next($request);
    }
}