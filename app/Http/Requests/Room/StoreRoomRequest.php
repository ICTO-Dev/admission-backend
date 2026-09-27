<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venue_id' => 'required|exists:venues,id',
            'room_name' => 'required|string|max:100',
            'total_seat' => 'sometimes|integer|min:1',
            'status' => 'sometimes|string|max:30',
        ];
    }
}
