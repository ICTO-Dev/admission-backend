<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'courseName' => 'sometimes|required|string|max:255',
            'campus_id' => 'sometimes|required|integer',
            'status' => 'nullable|integer',
            'college' => 'nullable|string|max:45',
            'is_open_program' => 'nullable|boolean',
            'date_added' => 'nullable|date',
        ];
    }
}
