<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\PublicBookingResource;
use App\Http\Resources\PublicMeetingRoomResource;
use App\Models\MeetingRoom;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class PublicBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    public function getRoomByToken(string $token): JsonResponse
    {
        $room = MeetingRoom::where('qr_token', $token)
            ->with(['company', 'department'])
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'غرفة الاجتماعات غير موجودة',
            ], 404);
        }

        if ($room->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'غرفة الاجتماعات غير متاحة حالياً',
            ], 400);
        }

        if ($room->company->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'الشركة غير نشطة حالياً',
            ], 400);
        }

        $todayBookings = $room->todayBookings()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'room' => new PublicMeetingRoomResource($room),
                'today_bookings' => PublicBookingResource::collection($todayBookings),
            ],
        ]);
    }

    public function createBooking(string $token, PublicBookingRequest $request): JsonResponse
    {
        $room = MeetingRoom::where('qr_token', $token)
            ->with('company')
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'غرفة الاجتماعات غير موجودة',
            ], 404);
        }

        $result = $this->bookingService->createBooking($room, $request->validated());

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => new BookingResource($result['booking']->load('meetingRoom')),
        ], 201);
    }
}

