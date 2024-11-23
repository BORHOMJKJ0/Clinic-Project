<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyAppointmentSeeder extends Seeder
{
    public function run()
    {

        $startTime = Carbon::createFromTime(8, 0);
        $endTime = Carbon::createFromTime(17, 0);

        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            $currentTime = $startTime->copy();
            while ($currentTime < $endTime) {
                Appointment::create([
                    'doctor_id' => $doctor->id,
                    'appointment_date' => Carbon::today()->toDateString(),
                    'appointment_time' => $currentTime->format('H:i:s'),
                    'patient_id' => null,
                ]);

                $currentTime->addHour();
            }
        }
    }
}
