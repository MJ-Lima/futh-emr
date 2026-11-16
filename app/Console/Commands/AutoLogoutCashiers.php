<?php

namespace App\Console\Commands;

use App\Models\DutyPosting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoLogoutCashiers extends Command
{
    protected $signature = 'cashier:auto-logout';
    protected $description = 'Automatically log out cashiers whose shift has ended';

    public function handle(): int
    {
        $today = now()->toDateString();
        $now = now()->format('H:i:s');

        $endedPostings = DutyPosting::where('status', 'Active')
            ->where('is_cashier_shift', true)
            ->where('is_active_now', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->whereNotNull('shift_end_time')
            ->where('shift_end_time', '<=', $now)
            ->get();

        $count = 0;
        foreach ($endedPostings as $posting) {
            $posting->update([
                'is_active_now' => false,
                'force_logout_at' => now(),
            ]);

            $user = User::find($posting->user_id);
            if ($user && $user->hasRole('cashier')) {
                DB::table('sessions')->where('user_id', $user->id)->delete();
                $this->info("Logged out: {$user->name} (shift ended at {$posting->shift_end_time})");
                $count++;
            }
        }

        $this->info("Auto-logged out {$count} cashier(s).");
        return Command::SUCCESS;
    }
}