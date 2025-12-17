<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRoomRequest;
use App\Http\Resources\MeetingRoomResource;
use App\Models\MeetingRoom;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = MeetingRoom::with(['company', 'department']);

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => MeetingRoomResource::collection($rooms),
            'meta' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
            ],
        ]);
    }

    public function store(MeetingRoomRequest $request): JsonResponse
    {
        $room = MeetingRoom::create($request->validated());
        $room->load(['company', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء غرفة الاجتماعات بنجاح',
            'data' => new MeetingRoomResource($room),
        ], 201);
    }

    public function show(MeetingRoom $meetingRoom): JsonResponse
    {
        $meetingRoom->load(['company', 'department', 'bookings' => function ($query) {
            $query->whereDate('start_time', today())->orderBy('start_time');
        }]);

        return response()->json([
            'success' => true,
            'data' => new MeetingRoomResource($meetingRoom),
        ]);
    }

    public function update(MeetingRoomRequest $request, MeetingRoom $meetingRoom): JsonResponse
    {
        $meetingRoom->update($request->validated());
        $meetingRoom->load(['company', 'department']);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث غرفة الاجتماعات بنجاح',
            'data' => new MeetingRoomResource($meetingRoom),
        ]);
    }

    public function destroy(MeetingRoom $meetingRoom): JsonResponse
    {
        $meetingRoom->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف غرفة الاجتماعات بنجاح',
        ]);
    }

    public function toggleStatus(MeetingRoom $meetingRoom): JsonResponse
    {
        $meetingRoom->status = $meetingRoom->status === 'active' ? 'inactive' : 'active';
        $meetingRoom->save();

        $statusText = $meetingRoom->status === 'active' ? 'تفعيل' : 'تعطيل';

        return response()->json([
            'success' => true,
            'message' => "تم {$statusText} غرفة الاجتماعات بنجاح",
            'data' => new MeetingRoomResource($meetingRoom),
        ]);
    }

    public function getQrCode(MeetingRoom $meetingRoom): JsonResponse
    {
        $qrCode = $this->qrCodeService->generateQrCode($meetingRoom);
        $bookingUrl = $this->qrCodeService->getBookingUrl($meetingRoom);

        return response()->json([
            'success' => true,
            'data' => [
                'qr_code_svg' => $qrCode,
                'booking_url' => $bookingUrl,
                'qr_token' => $meetingRoom->qr_token,
            ],
        ]);
    }

    public function regenerateQrToken(MeetingRoom $meetingRoom): JsonResponse
    {
        $newToken = $this->qrCodeService->regenerateToken($meetingRoom);

        return response()->json([
            'success' => true,
            'message' => 'تم تجديد رمز QR بنجاح',
            'data' => [
                'qr_token' => $newToken,
                'booking_url' => $this->qrCodeService->getBookingUrl($meetingRoom),
            ],
        ]);
    }
}

