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
            'doctor_details' => [
                'id' => $this->user->id,
                'name' => $this->user->first_name.' '.$this->user->last_name,
                'phone' => $this->user->phone,
            ],
            'appointments' => $this->appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'date' => $appointment->appointment_date,
                    'time' => $appointment->appointment_time,
                    'patient' => [
                        'id' => $appointment->patient->id,
                        'name' => $appointment->patient->first_name.' '.$appointment->patient->last_name,
                    ],
                ];
            }),
        ];
    }
}
