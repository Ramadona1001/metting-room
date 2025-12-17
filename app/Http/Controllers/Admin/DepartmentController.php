<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with('company');

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $departments = $query->orderBy('created_at', 'desc')->paginate(15);
        $companies = Company::where('status', 'active')->get();

        return view('admin.departments.index', compact('departments', 'companies'));
    }

    public function create()
    {
        $companies = Company::where('status', 'active')->get();
        return view('admin.departments.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
        ], [
            'company_id.required' => 'الشركة مطلوبة',
            'company_id.exists' => 'الشركة غير موجودة',
            'name.required' => 'اسم القسم مطلوب',
            'name.max' => 'اسم القسم يجب ألا يتجاوز 255 حرف',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم إنشاء القسم بنجاح');
    }

    public function show(Department $department)
    {
        $department->load(['company', 'meetingRooms']);
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $companies = Company::where('status', 'active')->get();
        return view('admin.departments.edit', compact('department', 'companies'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
        ], [
            'company_id.required' => 'الشركة مطلوبة',
            'company_id.exists' => 'الشركة غير موجودة',
            'name.required' => 'اسم القسم مطلوب',
            'name.max' => 'اسم القسم يجب ألا يتجاوز 255 حرف',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }

    public function multiDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:departments,id']);
        
        Department::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'تم حذف الأقسام المحددة بنجاح');
    }
}

