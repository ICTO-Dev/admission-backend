<?php

namespace App\Http\Requests\SchoolYear;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:20|unique:school_years,name',
            'is_active' => 'sometimes|boolean',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
