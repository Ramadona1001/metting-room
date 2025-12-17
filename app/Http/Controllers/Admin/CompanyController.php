<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount(['departments', 'meetingRooms'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'اسم الشركة مطلوب',
            'name.max' => 'اسم الشركة يجب ألا يتجاوز 255 حرف',
            'status.in' => 'حالة الشركة يجب أن تكون نشطة أو غير نشطة',
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'تم إنشاء الشركة بنجاح');
    }

    public function show(Company $company)
    {
        $company->load(['departments', 'meetingRooms']);
        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'اسم الشركة مطلوب',
            'name.max' => 'اسم الشركة يجب ألا يتجاوز 255 حرف',
            'status.in' => 'حالة الشركة يجب أن تكون نشطة أو غير نشطة',
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'تم تحديث الشركة بنجاح');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'تم حذف الشركة بنجاح');
    }

    public function toggleStatus(Company $company)
    {
        $company->status = $company->status === 'active' ? 'inactive' : 'active';
        $company->save();

        $statusText = $company->status === 'active' ? 'تفعيل' : 'تعطيل';

        return back()->with('success', "تم {$statusText} الشركة بنجاح");
    }

    public function multiDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:companies,id']);
        
        Company::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'تم حذف الشركات المحددة بنجاح');
    }
}

