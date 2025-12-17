<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMeetingRoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'capacity' => $this->capacity,
            'company_name' => $this->company->name,
            'department_name' => $this->department?->name,
            'working_hours_start' => $this->working_hours_start,
            'working_hours_end' => $this->working_hours_end,
            'max_booking_duration' => $this->max_booking_duration,
        ];
    }
}

