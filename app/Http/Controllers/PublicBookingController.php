<?php

namespace App\Http\Controllers;

use App\Models\MeetingRoom;
use App\Services\BookingService;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function show(string $token)
    {
        $room = MeetingRoom::where('qr_token', $token)
            ->with(['company', 'department'])
            ->first();

        if (!$room) {
            abort(404, 'غرفة الاجتماعات غير موجودة');
        }

        if ($room->status !== 'active') {
            return view('public.booking-unavailable', [
                'message' => 'غرفة الاجتماعات غير متاحة حالياً'
            ]);
        }

        if ($room->company->status !== 'active') {
            return view('public.booking-unavailable', [
                'message' => 'الشركة غير نشطة حالياً'
            ]);
        }

        $todayBookings = $room->todayBookings()->get();

        return view('public.booking', compact('room', 'todayBookings'));
    }

    public function store(string $token, Request $request)
    {
        $room = MeetingRoom::where('qr_token', $token)
            ->with('company')
            ->first();

        if (!$room) {
            abort(404, 'غرفة الاجتماعات غير موجودة');
        }

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ], [
            'employee_name.required' => 'اسم الموظف مطلوب',
            'employee_email.required' => 'البريد الإلكتروني مطلوب',
            'employee_email.email' => 'البريد الإلكتروني غير صالح',
            'start_time.required' => 'وقت البداية مطلوب',
            'end_time.required' => 'وقت الانتهاء مطلوب',
            'end_time.after' => 'وقت الانتهاء يجب أن يكون بعد وقت البداية',
        ]);

        $result = $this->bookingService->createBooking($room, $validated);

        if (!$result['success']) {
            return back()->withInput()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}

