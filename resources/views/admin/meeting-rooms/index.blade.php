@extends('layouts.admin')

@section('title', 'غرف الاجتماعات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">غرف الاجتماعات</h1>
    <a href="{{ route('admin.meeting-rooms.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة غرفة
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
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
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> فلترة</button>
                <a href="{{ route('admin.meeting-rooms.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>قائمة الغرف</span>
        <button type="button" class="btn btn-danger btn-sm" id="multi-delete-btn" disabled onclick="submitMultiDelete('multi-delete-form')">
            <i class="bi bi-trash"></i> حذف المحدد
        </button>
    </div>
    <div class="card-body p-0">
        <form id="multi-delete-form" action="{{ route('admin.meeting-rooms.multi-delete') }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" onclick="toggleSelectAll(this)">
                            </th>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الشركة</th>
                            <th>القسم</th>
                            <th>السعة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $room->id }}" class="form-check-input select-item">
                                </td>
                                <td>{{ $room->id }}</td>
                                <td>{{ $room->name }}</td>
                                <td>{{ $room->company->name }}</td>
                                <td>{{ $room->department?->name ?? '-' }}</td>
                                <td>{{ $room->capacity }}</td>
                                <td>
                                    <span class="badge {{ $room->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $room->status === 'active' ? 'نشطة' : 'غير نشطة' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.meeting-rooms.show', $room) }}" class="btn btn-primary btn-sm">عرض</a>
                                    <a href="{{ route('admin.meeting-rooms.qr-code', $room) }}" class="btn btn-dark btn-sm">QR</a>
                                    <a href="{{ route('admin.meeting-rooms.edit', $room) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route('admin.meeting-rooms.toggle-status', $room) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-{{ $room->status === 'active' ? 'secondary' : 'success' }} btn-sm">
                                            {{ $room->status === 'active' ? 'تعطيل' : 'تفعيل' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.meeting-rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الغرفة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">لا توجد غرف اجتماعات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @if($rooms->hasPages())
        <div class="card-footer">
            {{ $rooms->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
