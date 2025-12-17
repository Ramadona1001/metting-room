@extends('layouts.admin')

@section('title', 'عرض قسم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">{{ $department->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-warning btn-sm">
            <i class="bi bi-pencil"></i> تعديل
        </a>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">معلومات القسم</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="120">الاسم:</th>
                        <td>{{ $department->name }}</td>
                    </tr>
                    <tr>
                        <th>الشركة:</th>
                        <td>
                            <a href="{{ route('admin.companies.show', $department->company) }}" class="text-decoration-none">
                                {{ $department->company->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>تاريخ الإنشاء:</th>
                        <td>{{ $department->created_at->format('Y-m-d') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">غرف الاجتماعات ({{ $department->meetingRooms->count() }})</h5>
            </div>
            <div class="card-body">
                @if($department->meetingRooms->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($department->meetingRooms as $room)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $room->name }}</strong>
                                    <small class="text-muted">({{ $room->capacity }} شخص)</small>
                                </div>
                                <a href="{{ route('admin.meeting-rooms.show', $room) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted text-center mb-0">لا توجد غرف اجتماعات</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
