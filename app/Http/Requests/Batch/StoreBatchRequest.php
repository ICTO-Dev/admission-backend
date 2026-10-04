<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest
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
            'batch_name' => 'required|string|max:50',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
