<?php

namespace App\Http\Requests\Batch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => 'sometimes|exists:campuses,id',
            'school_year_id' => 'sometimes|exists:school_years,id',
            'batch_name' => 'sometimes|string|max:50',
            'status' => 'sometimes|string|max:20',
        ];
    }
}
