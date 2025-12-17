@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('content')
<h1 class="h3 mb-4">لوحة التحكم</h1>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded">
                        <i class="bi bi-building text-primary fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0 small">الشركات</p>
                        <h4 class="mb-0">{{ $stats['companies_count'] }}</h4>
                        <small class="text-success">{{ $stats['active_companies'] }} نشطة</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded">
                        <i class="bi bi-door-open text-success fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0 small">غرف الاجتماعات</p>
                        <h4 class="mb-0">{{ $stats['rooms_count'] }}</h4>
                        <small class="text-success">{{ $stats['active_rooms'] }} نشطة</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                        <i class="bi bi-calendar-check text-warning fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0 small">حجوزات اليوم</p>
                        <h4 class="mb-0">{{ $stats['today_bookings'] }}</h4>
                        <small class="text-success">{{ $stats['confirmed_bookings'] }} مؤكدة</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger bg-opacity-10 p-3 rounded">
                        <i class="bi bi-x-circle text-danger fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <p class="text-muted mb-0 small">الملغية اليوم</p>
                        <h4 class="mb-0">{{ $dailyStats['cancelled_bookings'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Usage & Recent Bookings -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">استخدام الغرف اليوم</h5>
            </div>
            <div class="card-body">
                @if(count($roomUsage) > 0)
                    @foreach($roomUsage as $room)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>{{ $room['room_name'] }}</strong>
                                <br><small class="text-muted">{{ $room['company'] }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $room['bookings_count'] }} حجز</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">لا توجد حجوزات اليوم</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">آخر الحجوزات</h5>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    @foreach($recentBookings as $booking)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <strong>{{ $booking->employee_name }}</strong>
                                <br><small class="text-muted">{{ $booking->meetingRoom->name }}</small>
                            </div>
                            <div class="text-end">
                                @if($booking->status === 'confirmed')
                                    <span class="badge bg-success">مؤكد</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">ملغي</span>
                                @else
                                    <span class="badge bg-secondary">مكتمل</span>
                                @endif
                                <br><small class="text-muted">{{ $booking->start_time->format('H:i') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center mb-0">لا توجد حجوزات</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
