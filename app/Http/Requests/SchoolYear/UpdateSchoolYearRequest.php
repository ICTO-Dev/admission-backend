<?php

namespace App\Http\Requests\SchoolYear;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSchoolYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('school_year') ?? $this->route('id');

        return [
            'name' => ['sometimes', 'string', 'max:20', Rule::unique('school_years', 'name')->ignore($id)],
            'is_active' => 'sometimes|boolean',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
