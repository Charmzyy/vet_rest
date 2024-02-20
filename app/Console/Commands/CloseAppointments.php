<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class CloseAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:close-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $unattendedAppointments = Appointment::where('status', 'confirmed')
        ->where('book_date', '<', now())
        ->get();

foreach ($unattendedAppointments as $appointment) {
$appointment->status = 'close';
$appointment->save();
}

$this->info('Appointments closed successfully.');
    }
}
