@extends('layouts.admin')

@section('title', 'تعديل غرفة اجتماعات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تعديل الغرفة: {{ $meetingRoom->name }}</h1>
    <a href="{{ route('admin.meeting-rooms.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.meeting-rooms.update', $meetingRoom) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="company_id" class="form-label">الشركة <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                        <option value="">اختر الشركة</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $meetingRoom->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="department_id" class="form-label">القسم (اختياري)</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">بدون قسم</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $meetingRoom->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }} ({{ $department->company->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">اسم الغرفة <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $meetingRoom->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="capacity" class="form-label">السعة <span class="text-danger">*</span></label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $meetingRoom->capacity) }}" min="1"
                           class="form-control @error('capacity') is-invalid @enderror">
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="max_booking_duration" class="form-label">الحد الأقصى للحجز (دقيقة) <span class="text-danger">*</span></label>
                    <input type="number" name="max_booking_duration" id="max_booking_duration" 
                           value="{{ old('max_booking_duration', $meetingRoom->max_booking_duration) }}" min="15" max="480"
                           class="form-control @error('max_booking_duration') is-invalid @enderror">
                    @error('max_booking_duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="working_hours_start" class="form-label">بداية ساعات العمل</label>
                    <input type="time" name="working_hours_start" id="working_hours_start" 
                           value="{{ old('working_hours_start', $meetingRoom->working_hours_start ? \Carbon\Carbon::parse($meetingRoom->working_hours_start)->format('H:i') : '') }}"
                           class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="working_hours_end" class="form-label">نهاية ساعات العمل</label>
                    <input type="time" name="working_hours_end" id="working_hours_end" 
                           value="{{ old('working_hours_end', $meetingRoom->working_hours_end ? \Carbon\Carbon::parse($meetingRoom->working_hours_end)->format('H:i') : '') }}"
                           class="form-control">
                </div>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">الحالة</label>
                <select name="status" id="status" class="form-select">
                    <option value="active" {{ old('status', $meetingRoom->status) === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ old('status', $meetingRoom->status) === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> تحديث
                </button>
                <a href="{{ route('admin.meeting-rooms.index') }}" class="btn btn-light">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
