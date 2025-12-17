@extends('layouts.admin')

@section('title', 'عرض شركة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $company->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات الشركة</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">الاسم:</th>
                        <td>{{ $company->name }}</td>
                    </tr>
                    <tr>
                        <th>الحالة:</th>
                        <td>
                            <span class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ $company->status === 'active' ? 'نشطة' : 'غير نشطة' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $company->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">الأقسام ({{ $company->departments->count() }})</h5>
            </div>
            <div class="card-body">
                @if($company->departments->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($company->departments as $dept)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dept->name }}
                                <a href="{{ route('admin.departments.show', $dept) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center mb-0">لا توجد أقسام</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">غرف الاجتماعات ({{ $company->meetingRooms->count() }})</h5>
            </div>
            <div class="card-body">
                @if($company->meetingRooms->count() > 0)
                    <div class="row g-3">
                        @foreach($company->meetingRooms as $room)
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $room->name }}</h6>
                                        <p class="card-text small text-muted mb-2">السعة: {{ $room->capacity }}</p>
                                        <span class="badge {{ $room->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $room->status === 'active' ? 'نشطة' : 'غير نشطة' }}
                                        </span>
                                        <a href="{{ route('admin.meeting-rooms.show', $room) }}" class="btn btn-sm btn-outline-primary mt-2">عرض</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted text-center mb-0">لا توجد غرف اجتماعات</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
