<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use App\Models\MeetingRoom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MeetingRoomFactory extends Factory
{
    protected $model = MeetingRoom::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'department_id' => null,
            'name' => 'قاعة ' . fake()->randomElement(['الاجتماعات', 'التدريب', 'المؤتمرات']) . ' ' . fake()->numberBetween(1, 10),
            'capacity' => fake()->randomElement([5, 10, 15, 20, 30]),
            'qr_token' => Str::uuid()->toString(),
            'status' => 'active',
            'max_booking_duration' => fake()->randomElement([60, 90, 120, 180]),
            'working_hours_start' => '08:00',
            'working_hours_end' => '18:00',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }
}

