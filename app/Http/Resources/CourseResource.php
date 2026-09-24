<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'courseName' => $this->courseName,
            'campus_id' => $this->campus_id,
            'status' => $this->status,
            'college' => $this->college,
            'is_open_program' => (bool) $this->is_open_program,
            'date_added' => $this->date_added?->toIso8601String(),
            'campus' => new CampusResource($this->whenLoaded('campus')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
