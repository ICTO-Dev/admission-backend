<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
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
            'batch_name' => $this->batch_name,
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
