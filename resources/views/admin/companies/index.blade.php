@extends('layouts.admin')

@section('title', 'الشركات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">الشركات</h1>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> إضافة شركة
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>قائمة الشركات</span>
        <button type="button" class="btn btn-danger btn-sm" id="multi-delete-btn" disabled onclick="submitMultiDelete('multi-delete-form')">
            <i class="bi bi-trash"></i> حذف المحدد
        </button>
    </div>
    <div class="card-body p-0">
        <form id="multi-delete-form" action="{{ route('admin.companies.multi-delete') }}" method="POST">
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
                            <th>الأقسام</th>
                            <th>الغرف</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr>
                                <td>
                                    <input type="checkbox" name="ids[]" value="{{ $company->id }}" class="form-check-input select-item">
                                </td>
                                <td>{{ $company->id }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->departments_count }}</td>
                                <td>{{ $company->meeting_rooms_count }}</td>
                                <td>
                                    <span class="badge {{ $company->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $company->status === 'active' ? 'نشطة' : 'غير نشطة' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-primary btn-sm">عرض</a>
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-warning btn-sm">تعديل</a>
                                    <form action="{{ route('admin.companies.toggle-status', $company) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-{{ $company->status === 'active' ? 'secondary' : 'success' }} btn-sm">
                                            {{ $company->status === 'active' ? 'تعطيل' : 'تفعيل' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الشركة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">لا توجد شركات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    @if($companies->hasPages())
        <div class="card-footer">
            {{ $companies->links() }}
        </div>
    @endif
</div>
@endsection
