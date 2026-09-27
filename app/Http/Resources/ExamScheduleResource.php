<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamScheduleResource extends JsonResource
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
            'school_year_id' => $this->school_year_id,
            'school_year' => $this->relationLoaded('schoolYear') && $this->schoolYear ? [
                'id' => $this->schoolYear->id,
                'name' => $this->schoolYear->name,
                'is_active' => (bool) $this->schoolYear->is_active,
                'status' => $this->schoolYear->status,
            ] : null,
            'batch_id' => $this->batch_id,
            'batch' => $this->relationLoaded('batch') && $this->batch ? [
                'id' => $this->batch->id,
                'batch_name' => $this->batch->batch_name,
                'status' => $this->batch->status,
            ] : null,
            'room_id' => $this->room_id,
            'room' => $this->relationLoaded('room') && $this->room ? [
                'id' => $this->room->id,
                'venue_id' => $this->room->venue_id,
                'room_name' => $this->room->room_name,
                'total_seat' => (int) $this->room->total_seat,
                'status' => $this->room->status,
                'venue' => $this->room->relationLoaded('venue') && $this->room->venue ? [
                    'id' => $this->room->venue->id,
                    'venue_name' => $this->room->venue->venue_name,
                    'description' => $this->room->venue->description,
                ] : null,
            ] : null,
            'day_label' => $this->day_label,
            'exam_date' => $this->exam_date?->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_seats' => (int) ($this->room?->total_seat ?? $this->max_capacity),
            'max_capacity' => (int) ($this->room?->total_seat ?? $this->max_capacity),
            'total_applicants' => (int) ($this->applications_count ?? 0),
            'applications_count' => (int) ($this->applications_count ?? 0),
            'is_full' => ((int) ($this->applications_count ?? 0)) >= ((int) ($this->room?->total_seat ?? $this->max_capacity)),
            'remaining_seats' => max(0, ((int) ($this->room?->total_seat ?? $this->max_capacity)) - ((int) ($this->applications_count ?? 0))),
            'status' => $this->status,
            'user_id' => $this->user_id,
            'creator' => $this->relationLoaded('creator') && $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
                'email' => $this->creator->email,
            ] : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
