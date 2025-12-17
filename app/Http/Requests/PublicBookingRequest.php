<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublicBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|max:255',
            'start_time' => 'required|date_format:Y-m-d H:i',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_name.required' => 'اسم الموظف مطلوب',
            'employee_name.max' => 'اسم الموظف يجب ألا يتجاوز 255 حرف',
            'employee_email.required' => 'البريد الإلكتروني مطلوب',
            'employee_email.email' => 'البريد الإلكتروني غير صالح',
            'employee_email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرف',
            'start_time.required' => 'وقت البداية مطلوب',
            'start_time.date_format' => 'صيغة وقت البداية غير صحيحة (Y-m-d H:i)',
            'end_time.required' => 'وقت الانتهاء مطلوب',
            'end_time.date_format' => 'صيغة وقت الانتهاء غير صحيحة (Y-m-d H:i)',
            'end_time.after' => 'وقت الانتهاء يجب أن يكون بعد وقت البداية',
        ];
    }
}

