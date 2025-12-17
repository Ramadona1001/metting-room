<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class ExpireFinishedBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'تحديث حالة الحجوزات المنتهية إلى مكتملة';

    public function handle(BookingService $bookingService): int
    {
        $count = $bookingService->expireFinishedBookings();

        $this->info("تم تحديث {$count} حجز إلى حالة مكتمل");

        return Command::SUCCESS;
    }
}

