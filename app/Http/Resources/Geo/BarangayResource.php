<?php

namespace App\Http\Resources\Geo;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'municipality_id' => (string) $this->municipality_id,
        ];
    }
}
