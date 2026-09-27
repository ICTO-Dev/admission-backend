<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueResource extends JsonResource
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
            'campus_id' => $this->campus_id,
            'campus' => $this->relationLoaded('campus') && $this->campus ? [
                'id' => $this->campus->id,
                'name' => $this->campus->name,
                'code' => $this->campus->aname ?? null,
                'aname' => $this->campus->aname ?? null,
            ] : null,
            'venue_name' => $this->venue_name,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'creator' => $this->relationLoaded('creator') && $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ] : null,
            'rooms_count' => (int) ($this->rooms_count ?? 0),
            'rooms' => $this->relationLoaded('rooms') ? $this->rooms->map(function ($room) {
                return [
                    'id' => $room->id,
                    'venue_id' => $room->venue_id,
                    'room_name' => $room->room_name,
                    'total_seat' => (int) $room->total_seat,
                    'status' => $room->status,
                    'exam_schedules_count' => (int) ($room->exam_schedules_count ?? 0),
                ];
            }) : [],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
