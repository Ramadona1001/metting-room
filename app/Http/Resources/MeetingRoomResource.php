<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingRoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'department_id' => $this->department_id,
            'name' => $this->name,
            'capacity' => $this->capacity,
            'qr_token' => $this->when($request->user(), $this->qr_token),
            'status' => $this->status,
            'max_booking_duration' => $this->max_booking_duration,
            'working_hours_start' => $this->working_hours_start,
            'working_hours_end' => $this->working_hours_end,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'bookings' => BookingResource::collection($this->whenLoaded('bookings')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

