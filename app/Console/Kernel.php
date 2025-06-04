<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\BorrowRequest;
use App\Notifications\ReturnReminderNotification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $loans = BorrowRequest::with('user')
                ->where('status', 'approved')
                ->whereDate('return_date_expected', now()->addDays(1))
                ->get();

            foreach ($loans as $loan) {
                $loan->user->notify(new ReturnReminderNotification($loan));
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
