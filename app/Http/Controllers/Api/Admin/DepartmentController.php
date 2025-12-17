<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Department::with('company');

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $departments = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => DepartmentResource::collection($departments),
            'meta' => [
                'current_page' => $departments->currentPage(),
                'last_page' => $departments->lastPage(),
                'per_page' => $departments->perPage(),
                'total' => $departments->total(),
            ],
        ]);
    }

    public function store(DepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());
        $department->load('company');

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء القسم بنجاح',
            'data' => new DepartmentResource($department),
        ], 201);
    }

    public function show(Department $department): JsonResponse
    {
        $department->load(['company', 'meetingRooms']);

        return response()->json([
            'success' => true,
            'data' => new DepartmentResource($department),
        ]);
    }

    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());
        $department->load('company');

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث القسم بنجاح',
            'data' => new DepartmentResource($department),
        ]);
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف القسم بنجاح',
        ]);
    }
}

