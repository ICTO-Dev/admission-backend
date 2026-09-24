<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class GetCoursesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'college' => 'nullable|string|max:45',
            'is_open_program' => 'nullable|boolean',
            'search' => 'nullable|string|max:255',
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
