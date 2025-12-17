<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'departments_count' => $this->whenCounted('departments'),
            'meeting_rooms_count' => $this->whenCounted('meetingRooms'),
            'departments' => DepartmentResource::collection($this->whenLoaded('departments')),
            'meeting_rooms' => MeetingRoomResource::collection($this->whenLoaded('meetingRooms')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

