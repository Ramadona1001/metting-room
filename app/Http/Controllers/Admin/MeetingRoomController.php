<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use App\Models\MeetingRoom;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    public function index(Request $request)
    {
        $query = MeetingRoom::with(['company', 'department']);

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy('created_at', 'desc')->paginate(15);
        $companies = Company::where('status', 'active')->get();

        return view('admin.meeting-rooms.index', compact('rooms', 'companies'));
    }

    public function create()
    {
        $companies = Company::where('status', 'active')->get();
        $departments = Department::all();
        return view('admin.meeting-rooms.create', compact('companies', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'max_booking_duration' => 'required|integer|min:15|max:480',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ], [
            'company_id.required' => 'الشركة مطلوبة',
            'name.required' => 'اسم غرفة الاجتماعات مطلوب',
            'capacity.required' => 'السعة مطلوبة',
            'capacity.min' => 'السعة يجب أن تكون على الأقل 1',
            'max_booking_duration.required' => 'مدة الحجز القصوى مطلوبة',
            'working_hours_end.after' => 'وقت نهاية العمل يجب أن يكون بعد وقت البداية',
        ]);

        MeetingRoom::create($validated);

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', 'تم إنشاء غرفة الاجتماعات بنجاح');
    }

    public function show(MeetingRoom $meetingRoom)
    {
        $meetingRoom->load(['company', 'department', 'bookings' => function ($query) {
            $query->whereDate('start_time', today())->orderBy('start_time');
        }]);

        return view('admin.meeting-rooms.show', compact('meetingRoom'));
    }

    public function edit(MeetingRoom $meetingRoom)
    {
        $companies = Company::where('status', 'active')->get();
        $departments = Department::all();
        return view('admin.meeting-rooms.edit', compact('meetingRoom', 'companies', 'departments'));
    }

    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'max_booking_duration' => 'required|integer|min:15|max:480',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ], [
            'company_id.required' => 'الشركة مطلوبة',
            'name.required' => 'اسم غرفة الاجتماعات مطلوب',
            'capacity.required' => 'السعة مطلوبة',
            'capacity.min' => 'السعة يجب أن تكون على الأقل 1',
            'max_booking_duration.required' => 'مدة الحجز القصوى مطلوبة',
            'working_hours_end.after' => 'وقت نهاية العمل يجب أن يكون بعد وقت البداية',
        ]);

        $meetingRoom->update($validated);

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', 'تم تحديث غرفة الاجتماعات بنجاح');
    }

    public function destroy(MeetingRoom $meetingRoom)
    {
        $meetingRoom->delete();

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', 'تم حذف غرفة الاجتماعات بنجاح');
    }

    public function toggleStatus(MeetingRoom $meetingRoom)
    {
        $meetingRoom->status = $meetingRoom->status === 'active' ? 'inactive' : 'active';
        $meetingRoom->save();

        $statusText = $meetingRoom->status === 'active' ? 'تفعيل' : 'تعطيل';

        return back()->with('success', "تم {$statusText} غرفة الاجتماعات بنجاح");
    }

    public function showQrCode(MeetingRoom $meetingRoom)
    {
        $qrCode = $this->qrCodeService->generateQrCode($meetingRoom);
        $bookingUrl = $this->qrCodeService->getBookingUrl($meetingRoom);

        return view('admin.meeting-rooms.qr-code', compact('meetingRoom', 'qrCode', 'bookingUrl'));
    }

    public function regenerateQrToken(MeetingRoom $meetingRoom)
    {
        $this->qrCodeService->regenerateToken($meetingRoom);

        return back()->with('success', 'تم تجديد رمز QR بنجاح');
    }

    public function multiDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:meeting_rooms,id']);
        
        MeetingRoom::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'تم حذف الغرف المحددة بنجاح');
    }
}

