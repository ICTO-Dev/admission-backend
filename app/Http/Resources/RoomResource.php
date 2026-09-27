<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'venue_id' => $this->venue_id,
            'venue' => $this->relationLoaded('venue') && $this->venue ? [
                'id' => $this->venue->id,
                'campus_id' => $this->venue->campus_id,
                'venue_name' => $this->venue->venue_name,
                'description' => $this->venue->description,
            ] : null,
            'room_name' => $this->room_name,
            'total_seat' => (int) $this->total_seat,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'creator' => $this->relationLoaded('creator') && $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ] : null,
            'exam_schedules_count' => (int) ($this->exam_schedules_count ?? 0),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
