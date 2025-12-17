@extends('layouts.admin')

@section('title', 'عرض حجز')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تفاصيل الحجز #{{ $booking->id }}</h1>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات الحجز</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">رقم الحجز:</th>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success">مؤكد</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="badge bg-danger">ملغي</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge bg-secondary">مكتمل</span>
                            @else
                                <span class="badge bg-warning">قيد الانتظار</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>التاريخ:</th>
                        <td>{{ $booking->start_time->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>الوقت:</th>
                        <td>{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $booking->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>

                @if($booking->status === 'confirmed')
                    <hr>
                    <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST"
                          onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-x-circle"></i> إلغاء الحجز
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات الموظف</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">الاسم:</th>
                        <td>{{ $booking->employee_name }}</td>
                    </tr>
                    <tr>
                        <th>البريد:</th>
                        <td>{{ $booking->employee_email }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات الغرفة</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">الغرفة:</th>
                        <td>
                            <a href="{{ route('admin.meeting-rooms.show', $booking->meetingRoom) }}" class="text-decoration-none">
                                {{ $booking->meetingRoom->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>الشركة:</th>
                        <td>
                            <a href="{{ route('admin.companies.show', $booking->meetingRoom->company) }}" class="text-decoration-none">
                                {{ $booking->meetingRoom->company->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>القسم:</th>
                        <td>{{ $booking->meetingRoom->department?->name ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
