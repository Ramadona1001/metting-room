<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\MeetingRoom;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $startHour = fake()->numberBetween(9, 16);
        $startTime = Carbon::today()->setHour($startHour)->setMinute(0);
        $endTime = $startTime->copy()->addHours(fake()->numberBetween(1, 2));

        return [
            'meeting_room_id' => MeetingRoom::factory(),
            'employee_name' => fake()->name(),
            'employee_email' => fake()->email(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'confirmed',
        ];
    }
}

