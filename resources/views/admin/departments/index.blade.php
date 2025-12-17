@extends('layouts.admin')

@section('title', 'الأقسام')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">الأقسام</h1>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة قسم
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
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
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> فلترة</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>قائمة الأقسام</span>
        <button type="button" class="btn btn-danger btn-sm" id="multi-delete-btn" disabled onclick="submitMultiDelete('multi-delete-form')">
            <i class="bi bi-trash"></i> حذف المحدد
        </button>
    </div>
    <div class="card-body p-0">
        <form id="multi-delete-form" action="{{ route('admin.departments.multi-delete') }}" method="POST">
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
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $department->id }}" class="form-check-input select-item">
                                </td>
                                <td>{{ $department->id }}</td>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->company->name }}</td>
                                <td>
                                    <a href="{{ route('admin.departments.show', $department) }}" class="btn btn-primary btn-sm">عرض</a>
                                    <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">لا توجد أقسام</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @if($departments->hasPages())
        <div class="card-footer">
            {{ $departments->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
