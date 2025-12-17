@extends('layouts.admin')

@section('title', 'الحجوزات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">الحجوزات</h1>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">الشركة</label>
                <select name="company_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">الغرفة</label>
                <select name="meeting_room_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ request('meeting_room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">التاريخ</label>
                <input type="date" name="date" value="{{ request('date') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> فلترة</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>قائمة الحجوزات</span>
        <button type="button" class="btn btn-danger btn-sm" id="multi-delete-btn" disabled onclick="submitMultiDelete('multi-delete-form')">
            <i class="bi bi-x-circle"></i> إلغاء المحدد
        </button>
    </div>
    <div class="card-body p-0">
        <form id="multi-delete-form" action="{{ route('admin.bookings.multi-cancel') }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" onclick="toggleSelectAll(this)">
                            </th>
                            <th>#</th>
                            <th>الموظف</th>
                            <th>شركة الموظف</th>
                            <th>قسم الموظف</th>
                            <th>الغرفة</th>
                            <th>الوقت</th>
                            <th>السبب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    @if($booking->status === 'confirmed')
                                        <input type="checkbox" name="ids[]" value="{{ $booking->id }}" class="form-check-input select-item">
                                    @endif
                                </td>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <strong>{{ $booking->employee_name }}</strong><br>
                                    <small class="text-muted">{{ $booking->employee_email }}</small>
                                </td>
                                <td>{{ $booking->company?->name ?? '-' }}</td>
                                <td>{{ $booking->department?->name ?? '-' }}</td>
                                <td>
                                    <strong>{{ $booking->meetingRoom->name }}</strong><br>
                                    <small class="text-muted">{{ $booking->meetingRoom->company->name }}</small>
                                </td>
                                <td>
                                    <strong>{{ $booking->start_time->format('Y-m-d') }}</strong><br>
                                    <small>{{ $booking->start_time->format('h:i A') }} - {{ $booking->end_time->format('h:i A') }}</small>
                                </td>
                                <td>
                                    {{ $booking->reason ?? '-' }}
                                </td>
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
                                <td>
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-primary btn-sm">عرض</a>
                                    @if($booking->status === 'confirmed')
                                        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">إلغاء</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">لا توجد حجوزات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @if($bookings->hasPages())
        <div class="card-footer">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
