<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'الشركة مطلوبة',
            'company_id.exists' => 'الشركة غير موجودة',
            'name.required' => 'اسم القسم مطلوب',
            'name.max' => 'اسم القسم يجب ألا يتجاوز 255 حرف',
        ];
    }
}

