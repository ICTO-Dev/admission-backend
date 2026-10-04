<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class StoreVenueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'campus_id' => 'sometimes|exists:campuses,id',
            'venue_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ];
    }
}
