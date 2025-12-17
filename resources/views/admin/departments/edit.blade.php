@extends('layouts.admin')

@section('title', 'تعديل قسم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">تعديل القسم: {{ $department->name }}</h1>
    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-right"></i> رجوع
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('admin.departments.update', $department) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="company_id" class="form-label">الشركة <span class="text-danger">*</span></label>
                <select name="company_id" id="company_id" class="form-select @error('company_id') is-invalid @enderror">
                    <option value="">اختر الشركة</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $department->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="form-label">اسم القسم <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}"
                       class="form-control @error('name') is-invalid @enderror">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> تحديث
                </button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-light">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
