<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\MeetingRoom;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function createBooking(MeetingRoom $room, array $data): array
    {
        $startTime = Carbon::parse($data['start_time']);
        $endTime = Carbon::parse($data['end_time']);

        // Validate room is active
        if ($room->status !== 'active') {
            return ['success' => false, 'message' => 'غرفة الاجتماعات غير متاحة حالياً'];
        }

        // Validate company is active
        if ($room->company->status !== 'active') {
            return ['success' => false, 'message' => 'الشركة غير نشطة حالياً'];
        }

        // Validate booking date is not in the past
        if ($startTime->isPast()) {
            return ['success' => false, 'message' => 'لا يمكن الحجز في وقت ماضي'];
        }


        // Validate end time is after start time
        if ($endTime->lte($startTime)) {
            return ['success' => false, 'message' => 'وقت الانتهاء يجب أن يكون بعد وقت البداية'];
        }

        // Validate booking duration
        $durationMinutes = $startTime->diffInMinutes($endTime);
        if ($durationMinutes > $room->max_booking_duration) {
            return [
                'success' => false,
                'message' => "مدة الحجز يجب ألا تتجاوز {$room->max_booking_duration} دقيقة"
            ];
        }

        // Validate working hours if set
        if ($room->working_hours_start && $room->working_hours_end) {
            $workStart = Carbon::parse($room->working_hours_start);
            $workEnd = Carbon::parse($room->working_hours_end);

            if ($startTime->format('H:i:s') < $workStart->format('H:i:s') ||
                $endTime->format('H:i:s') > $workEnd->format('H:i:s')) {
                return [
                    'success' => false,
                    'message' => "الحجز متاح فقط خلال ساعات العمل من {$room->working_hours_start} إلى {$room->working_hours_end}"
                ];
            }
        }

        // Check for conflicts
        if ($this->hasConflict($room->id, $startTime, $endTime)) {
            return ['success' => false, 'message' => 'يوجد حجز آخر في هذا الوقت'];
        }

        // Create booking
        $booking = DB::transaction(function () use ($room, $data, $startTime, $endTime) {
            return Booking::create([
                'meeting_room_id' => $room->id,
                'company_id' => $data['company_id'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'employee_name' => $data['employee_name'],
                'employee_email' => $data['employee_email'],
                'reason' => $data['reason'] ?? null,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'confirmed',
            ]);
        });

        return [
            'success' => true,
            'message' => 'تم الحجز بنجاح',
            'booking' => $booking,
        ];
    }

    public function hasConflict(int $roomId, Carbon $startTime, Carbon $endTime, ?int $excludeBookingId = null): bool
    {
        $query = Booking::where('meeting_room_id', $roomId)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($inner) use ($startTime, $endTime) {
                    $inner->where('start_time', '<', $endTime)
                          ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }

    public function cancelBooking(Booking $booking): array
    {
        if ($booking->status === 'cancelled') {
            return ['success' => false, 'message' => 'الحجز ملغي بالفعل'];
        }

        if ($booking->status === 'completed') {
            return ['success' => false, 'message' => 'لا يمكن إلغاء حجز منتهي'];
        }

        $booking->update(['status' => 'cancelled']);

        return ['success' => true, 'message' => 'تم إلغاء الحجز بنجاح'];
    }

    public function expireFinishedBookings(): int
    {
        return Booking::where('status', 'confirmed')
            ->where('end_time', '<', now())
            ->update(['status' => 'completed']);
    }

    public function getDailyStats(): array
    {
        $today = today();

        return [
            'total_bookings_today' => Booking::whereDate('start_time', $today)->count(),
            'confirmed_bookings' => Booking::whereDate('start_time', $today)
                ->where('status', 'confirmed')->count(),
            'cancelled_bookings' => Booking::whereDate('start_time', $today)
                ->where('status', 'cancelled')->count(),
            'completed_bookings' => Booking::whereDate('start_time', $today)
                ->where('status', 'completed')->count(),
        ];
    }

    public function getRoomUsageStats(): array
    {
        return MeetingRoom::withCount(['bookings' => function ($query) {
            $query->whereDate('start_time', today())
                  ->whereIn('status', ['confirmed', 'completed']);
        }])
        ->get()
        ->map(fn($room) => [
            'room_id' => $room->id,
            'room_name' => $room->name,
            'company' => $room->company->name,
            'bookings_count' => $room->bookings_count,
        ])
        ->toArray();
    }
}

