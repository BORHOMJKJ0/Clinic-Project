<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'appointment_date' => Carbon::parse($this->appointment_date)->format('d-m-Y'),
            'appointment_time' => Carbon::parse($this->appointment_time)->format('H:i'),
            'doctor' => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->user->first_name.' '.$this->doctor->user->last_name,
                'specialization' => $this->doctor->specialization,
                'phone' => $this->doctor->user->phone,
            ],
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->first_name.' '.$this->patient->user->last_name,
                'age' => $this->patient->age,
                'phone' => $this->patient->user->phone,
            ],
        ];
    }
}
