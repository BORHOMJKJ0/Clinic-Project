<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'age' => $this->age,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                // 'phone' => $this->user->phone,
            ],
        ];
    }
}
