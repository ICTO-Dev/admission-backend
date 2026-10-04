<?php

namespace App\Http\Requests\Campus;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'aname' => 'required|string|max:10',
            'campus_address' => 'required|string|max:255',
        ];
    }
}
