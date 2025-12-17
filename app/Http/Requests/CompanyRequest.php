<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'sometimes|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الشركة مطلوب',
            'name.max' => 'اسم الشركة يجب ألا يتجاوز 255 حرف',
            'status.in' => 'حالة الشركة يجب أن تكون نشطة أو غير نشطة',
        ];
    }
}

