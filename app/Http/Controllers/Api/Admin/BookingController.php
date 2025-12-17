<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Booking::with(['meetingRoom.company', 'meetingRoom.department']);

        // Filter by company
        if ($request->has('company_id')) {
            $query->whereHas('meetingRoom', function ($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // Filter by department
        if ($request->has('department_id')) {
            $query->whereHas('meetingRoom', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        // Filter by room
        if ($request->has('meeting_room_id')) {
            $query->where('meeting_room_id', $request->meeting_room_id);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('start_time', $request->date);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('start_time', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => BookingResource::collection($bookings),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
        ]);
    }

    public function show(Booking $booking): JsonResponse
    {
        $booking->load(['meetingRoom.company', 'meetingRoom.department']);

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking),
        ]);
    }

    public function cancel(Booking $booking): JsonResponse
    {
        $result = $this->bookingService->cancelBooking($booking);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        $booking->load(['meetingRoom.company', 'meetingRoom.department']);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => new BookingResource($booking),
        ]);
    }

    public function statistics(): JsonResponse
    {
        $dailyStats = $this->bookingService->getDailyStats();
        $roomUsage = $this->bookingService->getRoomUsageStats();

        return response()->json([
            'success' => true,
            'data' => [
                'daily_statistics' => $dailyStats,
                'room_usage' => $roomUsage,
            ],
        ]);
    }
}

