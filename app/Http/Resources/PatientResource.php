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
            'patient_details' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name.' '.$this->user->last_name,
                'phone' => $this->user->phone,
            ],
            'appointments' => $this->appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->appointment_date,
                    'time' => $appointment->appointment_time,
                    'doctor' => [
                        'id' => $appointment->doctor->id,
                        'name' => $appointment->doctor->first_name.' '.$appointment->doctor->last_name,
                    ],
                ];
            }),
        ];
    }
}
