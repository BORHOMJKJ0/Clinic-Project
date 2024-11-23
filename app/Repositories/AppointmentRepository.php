<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Traits\Lockable;

class AppointmentRepository
{
    use Lockable;

    public function getAll($items, $page)
    {
        return Appointment::paginate($items, ['*'], 'page', $page);
    }

    public function orderBy($column, $direction, $page, $items)
    {
        return Appointment::orderBy($column, $direction)->paginate($items, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return $this->lockForCreate(function () use ($data) {
            return Appointment::create($data);
        });
    }

    public function update(Appointment $appointment, array $data)
    {
        return $this->lockForUpdate(Appointment::class, $appointment->id, function ($lockedAppointment) use ($data) {
            $lockedAppointment->update($data);

            return $lockedAppointment;
        });
    }

    public function delete(Appointment $appointment)
    {
        return $this->lockForDelete(Appointment::class, $appointment->id, function ($lockedAppointment) {
            return $lockedAppointment->delete();
        });
    }
}
