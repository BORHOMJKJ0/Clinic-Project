<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::all()->random()->id,
            'patient_id' => Patient::all()->random()->id,
            'appointment_time' => fake()->time('H:i'),
            'appointment_date' => fake()->date('Y-m-d'),
        ];
    }
}
