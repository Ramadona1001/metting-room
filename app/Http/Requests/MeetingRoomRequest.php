<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'name' => 'required|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:active,inactive',
            'max_booking_duration' => 'sometimes|integer|min:15|max:480',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'الشركة مطلوبة',
            'company_id.exists' => 'الشركة غير موجودة',
            'department_id.exists' => 'القسم غير موجود',
            'name.required' => 'اسم غرفة الاجتماعات مطلوب',
            'name.max' => 'اسم الغرفة يجب ألا يتجاوز 255 حرف',
            'capacity.integer' => 'السعة يجب أن تكون رقماً صحيحاً',
            'capacity.min' => 'السعة يجب أن تكون على الأقل 1',
            'status.in' => 'حالة الغرفة يجب أن تكون نشطة أو غير نشطة',
            'max_booking_duration.integer' => 'مدة الحجز القصوى يجب أن تكون رقماً صحيحاً',
            'max_booking_duration.min' => 'مدة الحجز القصوى يجب أن تكون على الأقل 15 دقيقة',
            'max_booking_duration.max' => 'مدة الحجز القصوى يجب ألا تتجاوز 480 دقيقة',
            'working_hours_start.date_format' => 'صيغة وقت بداية العمل غير صحيحة',
            'working_hours_end.date_format' => 'صيغة وقت نهاية العمل غير صحيحة',
            'working_hours_end.after' => 'وقت نهاية العمل يجب أن يكون بعد وقت البداية',
        ];
    }
}

