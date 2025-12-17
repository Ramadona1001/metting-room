@extends('layouts.admin')

@section('title', 'تعديل شركة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تعديل الشركة: {{ $company->name }}</h1>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.companies.update', $company) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">اسم الشركة <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">الحالة</label>
                <select name="status" id="status" class="form-select">
                    <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>نشطة</option>
                    <option value="inactive" {{ old('status', $company->status) === 'inactive' ? 'selected' : '' }}>غير نشطة</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> تحديث
                </button>
                <a href="{{ route('admin.companies.index') }}" class="btn btn-light">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
