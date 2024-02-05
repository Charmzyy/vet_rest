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
    protected function schedule(Schedule $schedule): void
    {
    
        $schedule->call(function () {

            $unattendedappointments = Appointment::where('status','confirmed')
                                                   ->where('book_date', '<' , now())
                                                   ->get();
            foreach ($unattendedappointments as $appointment) {
                $appointment->status  = 'close';
                $appointment->save();
                # code...
            }

        });
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
