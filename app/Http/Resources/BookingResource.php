<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'meeting_room_id' => $this->meeting_room_id,
            'employee_name' => $this->employee_name,
            'employee_email' => $this->employee_email,
            'start_time' => $this->start_time->format('Y-m-d H:i:s'),
            'end_time' => $this->end_time->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'meeting_room' => new MeetingRoomResource($this->whenLoaded('meetingRoom')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

