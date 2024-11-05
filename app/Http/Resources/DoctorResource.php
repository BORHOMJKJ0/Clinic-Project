<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'specialization' => $this->specialization,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                // 'phone' => $this->user->phone,
            ],
        ];
    }
}
