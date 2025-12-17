<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\MeetingRoom;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function index(Request $request)
    {
        $query = Booking::with(['meetingRoom.company', 'meetingRoom.department', 'company', 'department']);

        if ($request->filled('company_id')) {
            $query->whereHas('meetingRoom', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        if ($request->filled('department_id')) {
            $query->whereHas('meetingRoom', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('meeting_room_id')) {
            $query->where('meeting_room_id', $request->meeting_room_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('start_time', 'desc')->paginate(15);
        $companies = Company::where('status', 'active')->get();
        $rooms = MeetingRoom::where('status', 'active')->get();

        return view('admin.bookings.index', compact('bookings', 'companies', 'rooms'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['meetingRoom.company', 'meetingRoom.department']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $result = $this->bookingService->cancelBooking($booking);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function multiCancel(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:bookings,id']);
        
        Booking::whereIn('id', $request->ids)
            ->where('status', 'confirmed')
            ->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الحجوزات المحددة بنجاح');
    }
}

