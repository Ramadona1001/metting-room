<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::withCount(['departments', 'meetingRooms'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => CompanyResource::collection($companies),
            'meta' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
            ],
        ]);
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $company = Company::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الشركة بنجاح',
            'data' => new CompanyResource($company),
        ], 201);
    }

    public function show(Company $company): JsonResponse
    {
        $company->load(['departments', 'meetingRooms']);

        return response()->json([
            'success' => true,
            'data' => new CompanyResource($company),
        ]);
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $company->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الشركة بنجاح',
            'data' => new CompanyResource($company),
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الشركة بنجاح',
        ]);
    }

    public function toggleStatus(Company $company): JsonResponse
    {
        $company->status = $company->status === 'active' ? 'inactive' : 'active';
        $company->save();

        $statusText = $company->status === 'active' ? 'تفعيل' : 'تعطيل';

        return response()->json([
            'success' => true,
            'message' => "تم {$statusText} الشركة بنجاح",
            'data' => new CompanyResource($company),
        ]);
    }
}

