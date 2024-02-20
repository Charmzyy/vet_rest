<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Booking_room;
use Illuminate\Console\Command;

class UpdateRoomStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-room-status';

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
        $expiredBookings = Booking_room::where('end', '<', Carbon::now())->get();

        foreach ($expiredBookings as $booking) {
            $booking->room->status = 'available';
            $booking->room->save();
        }

        $this->info('Room statuses updated successfully.');
    
    }
}
