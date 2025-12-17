<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->randomElement(['الموارد البشرية', 'تقنية المعلومات', 'المالية', 'التسويق', 'المبيعات', 'الإدارة']),
        ];
    }
}

