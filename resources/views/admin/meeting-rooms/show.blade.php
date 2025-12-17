@extends('layouts.admin')

@section('title', 'عرض غرفة اجتماعات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $meetingRoom->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.meeting-rooms.qr-code', $meetingRoom) }}" class="btn btn-dark">
            <i class="bi bi-qr-code"></i> QR Code
        </a>
        <a href="{{ route('admin.meeting-rooms.edit', $meetingRoom) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.meeting-rooms.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات الغرفة</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">الاسم:</th>
                        <td>{{ $meetingRoom->name }}</td>
                    </tr>
                    <tr>
                        <th>الشركة:</th>
                        <td>
                            <a href="{{ route('admin.companies.show', $meetingRoom->company) }}" class="text-decoration-none">
                                {{ $meetingRoom->company->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>القسم:</th>
                        <td>{{ $meetingRoom->department?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>السعة:</th>
                        <td>{{ $meetingRoom->capacity }} شخص</td>
                    </tr>
                    <tr>
                        <th>الحد الأقصى للحجز:</th>
                        <td>{{ $meetingRoom->max_booking_duration }} دقيقة</td>
                    </tr>
                    <tr>
                        <th>ساعات العمل:</th>
                        <td>
                            @if($meetingRoom->working_hours_start && $meetingRoom->working_hours_end)
                                {{ $meetingRoom->working_hours_start }} - {{ $meetingRoom->working_hours_end }}
                            @else
                                غير محدد
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            <span class="badge {{ $meetingRoom->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $meetingRoom->status === 'active' ? 'نشطة' : 'غير نشطة' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">حجوزات اليوم ({{ $meetingRoom->bookings->count() }})</h5>
            </div>
            <div class="card-body">
                @if($meetingRoom->bookings->count() > 0)
                    @foreach($meetingRoom->bookings as $booking)
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                            <div>
                                <strong>{{ $booking->employee_name }}</strong>
                                <br><small class="text-muted">{{ $booking->employee_email }}</small>
                            </div>
                            <div class="text-end">
                                @if($booking->status === 'confirmed')
                                    <span class="badge bg-success">مؤكد</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">ملغي</span>
                                @else
                                    <span class="badge bg-secondary">مكتمل</span>
                                @endif
                                <br><small>{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">لا توجد حجوزات اليوم</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
