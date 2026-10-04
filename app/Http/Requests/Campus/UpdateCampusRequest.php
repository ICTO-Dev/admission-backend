<?php

namespace App\Http\Requests\Campus;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCampusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:60',
            'aname' => 'sometimes|required|string|max:10',
            'campus_address' => 'sometimes|required|string|max:255',
        ];
    }
}
