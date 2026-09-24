<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'courseName' => 'required|string|max:255',
            'campus_id' => 'required|integer',
            'status' => 'nullable|integer',
            'college' => 'nullable|string|max:45',
            'is_open_program' => 'nullable|boolean',
            'date_added' => 'nullable|date',
        ];
    }
}
