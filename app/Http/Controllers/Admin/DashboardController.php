<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\MeetingRoom;
use App\Services\BookingService;

class DashboardController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function index()
    {
        $stats = [
            'companies_count' => Company::count(),
            'active_companies' => Company::where('status', 'active')->count(),
            'rooms_count' => MeetingRoom::count(),
            'active_rooms' => MeetingRoom::where('status', 'active')->count(),
            'today_bookings' => Booking::whereDate('start_time', today())->count(),
            'confirmed_bookings' => Booking::whereDate('start_time', today())->where('status', 'confirmed')->count(),
        ];

        $dailyStats = $this->bookingService->getDailyStats();
        $roomUsage = $this->bookingService->getRoomUsageStats();

        $recentBookings = Booking::with(['meetingRoom.company'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'dailyStats', 'roomUsage', 'recentBookings'));
    }
}

