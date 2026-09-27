<?php

namespace App\Http\Requests\ExamSchedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => 'sometimes|exists:campuses,id',
            'school_year_id' => 'required|exists:school_years,id',
            'batch_id' => 'required|exists:batches,id',
            'room_id' => 'required|exists:rooms,id',
            'day_label' => 'nullable|string|max:50',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i,H:i:s',
            'end_time' => 'required|date_format:H:i,H:i:s|after:start_time',
            'max_capacity' => 'nullable|integer|min:1',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
