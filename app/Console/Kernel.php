<?php

namespace App\Console;

use App\Models\Appointment;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

     protected $commands = [
        Commands\CloseAppointments::class,
    ];
    
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('rooms:update-status')->daily();
        $schedule->command('appointments:close')->daily();
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
